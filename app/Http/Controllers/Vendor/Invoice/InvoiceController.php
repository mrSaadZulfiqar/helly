<?php

namespace App\Http\Controllers\Vendor\Invoice;

use Exception;
use Carbon\Carbon;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\Product;
// use App\Models\Invoice\Currency;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Exports\AdminInvoicesExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use App\Repositories\InvoiceRepository;
use App\Mail\InvoicePaymentReminderMail;
use App\Http\Requests\CreateInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    /** @var InvoiceRepository */
    public $invoiceRepository;

    public function __construct(InvoiceRepository $invoiceRepo)
    {
        $this->invoiceRepository = $invoiceRepo;
    }

    /**
     * @throws Exception
     */
    public function index(Request $request): View|Factory|Application
    {
        $this->updateInvoiceOverDueStatus();
        $statusArr = Invoice::STATUS_ARR;
        $status = $request->status;

        return view('invoices.index', compact('statusArr', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|Factory|Application
    {
        $data = $this->invoiceRepository->getSyncList();
        unset($data['statusArr'][0]);
        $data['currencies'] = []; 
        // getCurrencies();
        return view('invoices.create')->with($data);
    }

    public function store(CreateInvoiceRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $invoice = $this->invoiceRepository->saveInvoice($request->all());
            DB::commit();
            
            // code by AG start
            $attachments = $request->input('attachments_urls');
            if( isset($attachments) && !empty($attachments)){
                $invoice->attachments = json_encode( $attachments );
                $invoice->save();
            }
            $invoice->project_name = $request->input('project_name');
                $invoice->save();
            // code by AG end
            
            if ($request->status != Invoice::DRAFT) {
                $this->invoiceRepository->saveNotification($request->all(), $invoice);
                Flash::success(__('messages.flash.invoice_saved_sent'));

                return $this->sendResponse($invoice, __('messages.flash.invoice_saved_sent'));
            }
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError($e->getMessage());
        }

        Flash::success(__('messages.flash.invoice_saved'));

        return $this->sendResponse($invoice, __('messages.flash.invoice_saved'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice): View|Factory|Application
    {
        $invoiceData = $this->invoiceRepository->getInvoiceData($invoice);

        return view('invoices.show')->with($invoiceData);
    }

    public function edit(Invoice $invoice): View|Factory|RedirectResponse|Application
    {
        if ($invoice->status == Invoice::PAID || $invoice->status == Invoice::PARTIALLY) {
            Flash::error(__('messages.flash.paid_invoices_can_not_editable'));

            return redirect()->route('invoices.index');
        }

        $data = $this->invoiceRepository->prepareEditFormData($invoice);
        $data['currencies'] = [];
        // getCurrencies()
        $data['selectedInvoiceTaxes'] = $invoice->invoiceTaxes()->pluck('tax_id')->toArray();

        return view('invoices.edit')->with($data);
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): JsonResponse
    {
        $input = $request->all();
        try {
            DB::beginTransaction();
            $invoice = $this->invoiceRepository->updateInvoice($invoice->id, $input);
            DB::commit();
            
            // code by AG start
            $attachments = $request->input('attachments_urls');
            if( isset($attachments) && !empty($attachments)){
                $invoice->attachments = json_encode( $attachments );
                $invoice->save();
            }
            
            $invoice->project_name = $request->input('project_name');
            $invoice->save();
        
            // code by AG end
            
            if ($input['invoiceStatus'] === '1') {
                return $this->sendResponse($invoice, __('messages.flash.invoice_updated_sent'));
            }
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError($e->getMessage());
        }

        Flash::success(__('messages.flash.invoice_updated'));

        return $this->sendResponse($invoice, __('messages.flash.invoice_updated'));
    }

    public function destroy(Invoice $invoice): JsonResponse
    {
        if ($invoice->tenant_id != Auth::user()->tenant_id) {
            return $this->sendError('Seems, you are not allowed to access this record.');
        }
        $invoice->delete();

        return $this->sendSuccess(__('messages.flash.invoice_deleted'));
    }

    public function getProduct($productId): JsonResponse
    {
        $product = Product::toBase()->pluck('unit_price', 'id')->toArray();

        return $this->sendResponse($product, __('messages.flash.product_price_retrieved'));
    }

    public function getInvoiceCurrency($currencyId)
    {
        // $currency = Currency::whereId($currencyId)->first()->icon;

        // return $this->sendResponse($currency, __('messages.flash.invoice_currency_retrieved'));
    }

    public function convertToPdf($invoiceId): Response
    {
        $invoice = Invoice::whereId($invoiceId)->whereTenantId(Auth::user()->tenant_id)->firstOrFail();
        $invoice->load([
            'client.user', 'invoiceTemplate', 'invoiceItems.product', 'invoiceItems.invoiceItemTax', 'invoiceTaxes',
        ]);
        $invoiceData = $this->invoiceRepository->getPdfData($invoice);

        $invoiceTemplate = $this->invoiceRepository->getDefaultTemplate($invoice);
        $pdf = PDF::loadView("invoices.invoice_template_pdf.$invoiceTemplate", $invoiceData);

        return $pdf->stream('invoice.pdf');
    }

    public function updateInvoiceStatus(Invoice $invoice, $status): mixed
    {
        $this->invoiceRepository->draftStatusUpdate($invoice);

        return $this->sendSuccess(__('messages.flash.invoice_send'));
    }

    public function updateInvoiceOverDueStatus()
    {
        $invoice = Invoice::whereStatus(Invoice::UNPAID)->get();
        $currentDate = Carbon::today()->format('Y-m-d');
        foreach ($invoice as $invoices) {
            if ($invoices->due_date < $currentDate) {
                $invoices->update([
                    'status' => Invoice::OVERDUE,
                ]);
            }
        }
    }

    public function invoicePaymentReminder($invoiceId): mixed
    {
        $invoice = Invoice::with(['client.user', 'payments'])->whereId($invoiceId)->whereTenantId(Auth::user()->tenant_id)->firstOrFail();
      
		$paymentReminder = Mail::to($invoice->client->user->email)->send(new InvoicePaymentReminderMail($invoice));

        return $this->sendResponse($paymentReminder, __('messages.flash.payment_reminder_mail_send'));
    }

    public function exportInvoicesExcel(): BinaryFileResponse
    {
        return Excel::download(new AdminInvoicesExport(), 'invoice-excel.xlsx');
    }

    public function showPublicInvoice($invoiceId): View|Factory|Application
    {
        $invoice = Invoice::with('client.user')->whereInvoiceId($invoiceId)->firstOrFail();
        $invoiceData = $this->invoiceRepository->getInvoiceData($invoice);

        return view('invoices.public_view')->with($invoiceData);
    }

    public function getPublicInvoicePdf($invoiceId): Response
    {
        $invoice = Invoice::whereInvoiceId($invoiceId)->firstOrFail();
        $invoice->load('client.user', 'invoiceTemplate', 'invoiceItems.product', 'invoiceItems.invoiceItemTax');

        $invoiceData = $this->invoiceRepository->getPdfData($invoice);
        $invoiceTemplate = $this->invoiceRepository->getDefaultTemplate($invoice);
        $pdf = PDF::loadView("invoices.invoice_template_pdf.$invoiceTemplate", $invoiceData);

        return $pdf->stream('invoice.pdf');
    }

    public function updateRecurringStatus(Invoice $invoice)
    {
        if ($invoice->tenant_id != Auth::user()->tenant_id) {
            return $this->sendError(__('Seems, you are not allowed to access this record.'));
        }

        $recurringCycle = empty($invoice->recurring_cycle) ? 1 : $invoice->recurring_cycle;
        $invoice->update([
            'recurring_status' => ! $invoice->recurring_status,
            'recurring_cycle' => $recurringCycle,
        ]);

        return $this->sendSuccess(__('messages.flash.recurring_status_updated'));
    }

    public function exportInvoicesPdf(): Response
    {
        $data['invoices'] = Invoice::with('client.user', 'payments')->orderBy('created_at', 'desc')->get();
        $invoicesPdf = PDF::loadView('invoices.export_invoices_pdf', $data);

        return $invoicesPdf->download('Invoices.pdf');
    }
    
    // code by AG start
    public function invoice_duplicate($invoiceId){
        
        $original_invoice = Invoice::find($invoiceId);
        $original_invoice_items = $original_invoice->invoiceItems()->get();
        $original_invoice_taxes = $original_invoice->invoiceTaxes()->get();
        
        $duplicated_invoice = $original_invoice->replicate();
        $duplicated_invoice->invoice_id = Invoice::generateUniqueInvoiceId();
        $duplicated_invoice->invoice_date = date(currentDateFormat());
        $duplicated_invoice->due_date = date(currentDateFormat(), strtotime("+1 day"));
        $duplicated_invoice->status = 0;
        $duplicated_invoice->created_at = Carbon::now();
        $duplicated_invoice->save();
        
        if( !empty( $original_invoice_taxes ) ){
            foreach( $original_invoice_taxes as $original_invoice_tax ){
                $duplicated_invoice_tax = $original_invoice_tax->replicate();
                $duplicated_invoice_tax->invoice_id = $duplicated_invoice->id;
                $duplicated_invoice_tax->created_at = Carbon::now();
                $duplicated_invoice_tax->save();
            }
        }
        
        if( !empty( $original_invoice_items ) ){
            foreach( $original_invoice_items as $original_invoice_item ){
                $duplicated_invoice_item = $original_invoice_item->replicate();
                $duplicated_invoice_item->invoice_id = $duplicated_invoice->id;
                $duplicated_invoice_item->created_at = Carbon::now();
                $duplicated_invoice_item->save();
                
                $original_invoice_item_taxes = $original_invoice_item->invoiceItemTax()->get();
                if( !empty( $original_invoice_item_taxes ) ){
                    foreach( $original_invoice_item_taxes as $original_invoice_item_tax ){
                        $duplicated_invoice_item_tax = $original_invoice_item_tax->replicate();
                        $duplicated_invoice_item_tax->invoice_item_id = $duplicated_invoice_item->id;
                        $duplicated_invoice_item_tax->created_at = Carbon::now();
                        $duplicated_invoice_item_tax->save();
                    }
                }
            }
        }
        
        return redirect()->route('invoices.edit', $duplicated_invoice->id);
        //return redirect()->back();
    }
    
    public function invoice_upload_attachment(Request $request){
        
         if($request->file()) {
             
            $fileName = time().'_'.$request->file->getClientOriginalName();
            
            $filePath = $request->file('file')->storeAs('invoice-quote-attachments', $fileName, 'public');
            echo $filePath; die;
         }
    }
    // code by AG end
}
