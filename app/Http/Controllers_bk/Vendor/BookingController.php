<?php

namespace App\Http\Controllers\Vendor;

use App\Exports\EquipmentBookingsExport;
use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Instrument\EquipmentBooking;
use App\Models\Instrument\EquipmentContent;
use App\Models\Instrument\SecurityDepositRefund;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\Language;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\User;
use App\Models\UserCard;
use App\Models\BookingDriver; // code by AG
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Driver; // code by AG
use App\Models\Instrument\Equipment; // code by AG
use App\Models\Instrument\EquipmentCategory; // code by AG
use App\Models\BookingUpdate; // code by AG
use PDF;

use App\Models\EquipmentFieldsValue; // code by AG 
use App\Http\Helpers\UploadFile;
use App\Models\AdditionalInvoice; // code by AG
use App\Models\ShippingStatus; // code by AG
use App\Notifications\BasicNotify; // code by AG
use App\Models\Vendor; // code by AG  
use App\Models\Company;
use App\Models\CompanyBranch;
use App\Http\Requests\Instrument\BookingProcessRequest;
use App\Http\Controllers\FrontEnd\PaymentGateway\FlutterwaveController;
use App\Http\Controllers\FrontEnd\PaymentGateway\InstamojoController;
use App\Http\Controllers\FrontEnd\PaymentGateway\MercadoPagoController;
use App\Http\Controllers\FrontEnd\PaymentGateway\MollieController;
use App\Http\Controllers\FrontEnd\PaymentGateway\OfflineController;
use App\Http\Controllers\FrontEnd\PaymentGateway\PayPalController;
use App\Http\Controllers\FrontEnd\PaymentGateway\PaystackController;
use App\Http\Controllers\FrontEnd\PaymentGateway\PaytmController;
use App\Http\Controllers\FrontEnd\PaymentGateway\RazorpayController;
use App\Http\Controllers\FrontEnd\PaymentGateway\StripeController;
use App\Http\Controllers\FrontEnd\PaymentGateway\StaxController; // code by AG
use App\Http\Controllers\FrontEnd\PaymentGateway\ResolvepayController; // code by AG
use App\Http\Controllers\FrontEnd\Instrument\BookingProcessController;
use Illuminate\Support\Facades\File;
use App\Models\BookingDriverSchedule;
class BookingController extends Controller
{
    public function indexb(Request $request)
  {
    if (!$request->exists('gateway')) {
      Session::flash('error', 'Please select a payment method.');

      return redirect()->back()->withInput();
    } else if ($request['gateway'] == 'paypal') {
      $paypal = new PayPalController();

      return $paypal->index($request, 'equipment booking');
    } else if ($request['gateway'] == 'instamojo') {
      $instamojo = new InstamojoController();

      return $instamojo->index($request, 'equipment booking');
    } else if ($request['gateway'] == 'paystack') {
      $paystack = new PaystackController();

      return $paystack->index($request, 'equipment booking');
    } else if ($request['gateway'] == 'flutterwave') {
      $flutterwave = new FlutterwaveController();

      return $flutterwave->index($request, 'equipment booking');
    } else if ($request['gateway'] == 'razorpay') {
      $razorpay = new RazorpayController();

      return $razorpay->index($request, 'equipment booking');
    } else if ($request['gateway'] == 'mercadopago') {
      $mercadopago = new MercadoPagoController();

      return $mercadopago->index($request, 'equipment booking');
    } else if ($request['gateway'] == 'mollie') {
      $mollie = new MollieController();

      return $mollie->index($request, 'equipment booking');
    } else if ($request['gateway'] == 'stripe') {
      $stripe = new StripeController();

      return $stripe->index($request, 'equipment booking');
    } 
    else if ($request['gateway'] == 'paytm') {
      $paytm = new PaytmController();
      return $paytm->index($request, 'equipment booking');
    }

    // code by AG start
    else if ($request['gateway'] == 'stax') {
      $stax = new StaxController();
      return $stax->index($request, 'equipment booking');
      
    }
    
     else if ($request['gateway'] == 'resolve') {
      $resolve = new ResolvepayController();

      return $resolve->index($request, 'equipment booking');
    }
    // code by AG end

    else {
      $offline = new OfflineController();

      return $offline->index($request, 'equipment booking');
    }
  }
    public function create()
    {
        $data['equipments'] = Equipment::where('vendor_id',auth()->user()->id)->with('content')->get();
        $data['customers'] = User::where('vendor_id',auth()->user()->id)->get();
        $data['onlineGateways'] = OnlineGateway::where('status', 1)->get();
        $data['offlineGateways'] = OfflineGateway::where('status', 1)->orderBy('serial_number', 'asc')->get();
        $data['basicData'] = Basic::select('self_pickup_status', 'two_way_delivery_status', 'equipment_tax_amount', 'guest_checkout_status')->first();
        return view('vendors.booking.create',$data);
    }
    public function edit($id)
    {
        $data['equipments'] = Equipment::where('vendor_id',auth()->user()->id)->with('content')->get();
        $data['customers'] = User::where('vendor_id',auth()->user()->id)->get();
        $data['onlineGateways'] = OnlineGateway::where('status', 1)->get();
        $data['offlineGateways'] = OfflineGateway::where('status', 1)->orderBy('serial_number', 'asc')->get();
        $data['basicData'] = Basic::select('self_pickup_status', 'two_way_delivery_status', 'equipment_tax_amount', 'guest_checkout_status')->first();
        $data['details'] = EquipmentBooking::find($id);
        return view('vendors.booking.edit',$data);
    }
    public function update(Request $request,$id)
    {
        $bookingProcess = new BookingProcessController();
        $calculatedData = $bookingProcess->calculation($request);
        $dates = $bookingProcess->getDates($request['dates']);
        
        $booking = EquipmentBooking::find($id);
        $arrData = array(
        'name' => $request['name'],
        'contact_number' => $request['contact_number'],
        'email' => $request['email'],
        'equipment_id' => $request['equipment_id'],
        'start_date' => $dates['startDate'],
        'end_date' => $dates['endDate'],
        'delivery_location' => $request['delivery_location'],
        'total' => $calculatedData['total'],
        'discount' => $calculatedData['discount'],
        'shipping_cost' => $calculatedData['shippingCharge'],
        'tax' => $calculatedData['tax'],
        'grand_total' => $calculatedData['grandTotal'],
        'security_deposit_amount' => $calculatedData['security_deposit_amount'],
        'lat' => $request['lat'],
        'lng' => $request['long'],
        'branch_id' => $request['branch_id'] ?? null,
        'user_id' => $request['user_id'] ?? null,
        'company_id' => $request['company_id'] ?? null,
        'invoice' => '',
      );
      
        // Check if additional charges items are set
        if (isset($calculatedData['additional_charges_items_json'])) {
            $arrData['additional_charges_line_items'] = $calculatedData['additional_charges_items_json'];
        }
        
        // Check if additional booking parameters are set
        if (isset($calculatedData['additional_booking_parameters_json'])) {
            $arrData['additional_booking_parameters'] = $calculatedData['additional_booking_parameters_json'];
        }

      
      if ($booking) {
            $up_booking = $booking->update($arrData);
            $invoice = $bookingProcess->generateInvoice($booking);
            
            $booking->update([
                'invoice' => $invoice
              ]);
              return redirect()->back()->with('success','Booking Updated Succussfully!');
        } else {
              return redirect()->back()->with('error','Something went wrong!');
        }
        
    }
    
    
    public function get_equipment(Request $request){
        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();
        $details = Equipment::query()->join('equipment_contents', 'equipments.id', '=', 'equipment_contents.equipment_id')
          ->join('equipment_categories', 'equipment_categories.id', '=', 'equipment_contents.equipment_category_id')
          ->where('equipment_contents.language_id', '=', $language->id)
          ->where('equipment_contents.equipment_id', '=', $request->id)
          ->select('equipments.id', 'equipments.vendor_id', 'equipments.slider_images', 'equipment_contents.title', 'equipment_categories.name as categoryName', 'equipment_categories.slug as categorySlug', 'equipment_contents.description', 'equipment_contents.features', 'equipments.lowest_price', 'equipments.per_day_price', 'equipments.per_week_price', 'equipments.per_month_price', 'equipments.min_booking_days', 'equipments.max_booking_days', 'equipments.security_deposit_amount', 'equipment_contents.meta_keywords', 'equipment_contents.meta_description', 'equipment_contents.equipment_category_id')
          ->firstOrFail();
          $basicData = Basic::select('self_pickup_status', 'two_way_delivery_status', 'equipment_tax_amount', 'guest_checkout_status')->first();
         
            $currTotal = $details->lowest_price;
                  
          if(is_equipment_multiple_charges($details->equipment_category_id) || is_equipment_temporary_toilet_type($details->equipment_category_id) || is_equipment_storage_container_type($details->equipment_category_id)){
            if (!empty($multiple_charges_settings['base_price'])){
                $currTotal = $multiple_charges_settings['base_price']??0;
            }
          }
          
          $currTotal = amount_with_commission($currTotal);
          
          $taxAmount = $basicData->equipment_tax_amount;
          $calculatedTax = $currTotal * ($taxAmount / 100);

          // calculate grand total
          $grandTotal = $currTotal + $calculatedTax + $details->security_deposit_amount;
          $currencyInfo = $this->getCurrencyInfo();
          $symbol = $currencyInfo->base_currency_symbol;
    
        return response()->json(["details"=>$details,'tax'=>$calculatedTax,'grandTotal'=>$grandTotal,'symbol'=>$symbol]);
    }


    public function bookings(Request $request)
    {
        // $mailData = array();
        // $mailData['subject'] = 'Notification of shipping status';

        // $mailData['body'] = 'Test EMail ';

        // $mailData['recipient'] = 'goyalatul47@gmail.com';

        // $mailData['sessionMessage'] = 'Shipping status updated & mail has been sent successfully!';

        // BasicMailer::sendMail($mailData);
        
        $information['basicData'] = Basic::select('self_pickup_status', 'two_way_delivery_status')->first();
        
         $information['shippingStatus'] = ShippingStatus::get(); // code by Ag

        $language = Language::where('code', $request->language)->first();

        $bookingNumber = $paymentStatus = $shippingType = $shippingStatus = $returnStatuss = null;

        if ($request->filled('booking_no')) {
            $bookingNumber = $request['booking_no'];
        }
        if ($request->filled('payment_status')) {
            $paymentStatus = $request['payment_status'];
        }
        if ($request->filled('shipping_type')) {
            $shippingType = $request['shipping_type'];
        }
        if ($request->filled('shipping_status')) {
            $shippingStatus = $request['shipping_status'];
        }
        if ($request->filled('return_status')) {
            $returnStatus = $request['return_status'];
            $returnStatuss = 1;
        } else {
            $returnStatus = null;
        }

        $bookings = EquipmentBooking::query()->where('vendor_id', Auth::guard('vendor')->user()->id)
        // code by AG start
        ->leftJoin('booking_drivers', 'equipment_bookings.id', '=', 'booking_drivers.booking_id')
        ->select('equipment_bookings.*','booking_drivers.id as bid', 'booking_drivers.driver_id as driver_id')
        // code by AG end
        ->when($bookingNumber, function ($query, $bookingNumber) {
            return $query->where('booking_number', 'like', '%' . $bookingNumber . '%');
        })
            ->when($paymentStatus, function ($query, $paymentStatus) {
                return $query->where('payment_status', '=', $paymentStatus);
            })
            ->when($shippingType, function ($query, $shippingType) {
                return $query->where('shipping_method', '=', $shippingType);
            })
            ->when($shippingStatus, function ($query, $shippingStatus) {
                return $query->where('shipping_status', '=', $shippingStatus);
            })
            ->when($returnStatuss, function ($query) use ($returnStatus) {
                return $query->where('return_status', '=', $returnStatus);
            })
            ->orderBy('equipment_bookings.id', 'desc')
            ->paginate(10);

        $bookings->map(function ($booking) use ($language) {
            $equipment = $booking->equipmentInfo()->first();
            $booking['equipmentTitle'] = $equipment->content()->where('language_id', $language->id)->select('title', 'slug')->first();
        });

        
        $information['bookings'] = $bookings;

        // code by AG start
        
        $declined_bookings = EquipmentBooking::query()->where('accept_status', 'decline')->where('vendor_id','!=', Auth::guard('vendor')->user()->id)
        ->select('equipment_bookings.*')
            ->orderBy('equipment_bookings.id', 'desc')->get();

        $declined_bookings->map(function ($declined_booking) use ($language) {
            $equipment = $declined_booking->equipmentInfo()->first();
            $declined_booking['equipmentTitle'] = $equipment->content()->where('language_id', $language->id)->select('title', 'slug')->first();
        });

        
        $information['declined_bookings'] = $declined_bookings;
        
        $drivers = Driver::where('vendor_id', Auth::guard('vendor')->user()->id)->get()->toArray();
        $information['drivers'] = $drivers;
        // code by AG end

        return view('vendors.booking.index', $information);
    }

    public function get_cards(Request $request)
    {
        $cards = UserCard::where('user_id',$request->id)->get();
        return response()->json($cards);
    }

    public function get_user_data(Request $request)
    {
        $user = User::find($request->id);
        if($user->account_type == 'corperate_account' && $user->owner_id == null)
        {
            $company = Company::where('cutomer_id', $user->id)->first();
            $branches = CompanyBranch::where('company_id',$company->id)->get();
            return response()->json(['user'=>$user,'company'=>$company,'branches'=>$branches]);
        }
        else if($user->account_type == 'corperate_account' && $user->owner_id != null)
        {
            $company = Company::where('customer_id', $user->owner_id)->first();
            $branches = CompanyBranch::where('company_id',$company->id)->get();
            return response()->json(['user'=>$user,'company'=>$company,'branches'=>$branches]);
        }
            return response()->json(['user'=>$user]);
    }
    public function updatePaymentStatus(Request $request, $id)
    {
        $booking = EquipmentBooking::find($id);

        if ($request['payment_status'] == 'completed') {
            $booking->update([
                'payment_status' => 'completed'
            ]);

            $statusMsg = 'Your payment is complete.';

            // generate an invoice in pdf format
            $invoice = $this->generateInvoice($booking);

            // then, update the invoice field info in database
            $booking->update([
                'invoice' => $invoice
            ]);
        } else if ($request['payment_status'] == 'pending') {
            $booking->update([
                'payment_status' => 'pending'
            ]);

            $statusMsg = 'Your payment is pending.';
        } else {
            $booking->update([
                'payment_status' => 'rejected'
            ]);

            $statusMsg = 'Your payment has been rejected.';
        }

        $mailData = [];

        if (isset($invoice)) {
            $mailData['invoice'] = 'assets/file/invoices/equipment/' . $invoice;
        }

        $mailData['subject'] = 'Notification of payment status';

        $mailData['body'] = 'Hi ' . $booking->name . ',<br/><br/>This email is to notify the payment status of your equipment booking. ' . $statusMsg;

        $mailData['recipient'] = $booking->email;

        $mailData['sessionMessage'] = 'Payment status updated & mail has been sent successfully!';

        BasicMailer::sendMail($mailData);

        return redirect()->back();
    }

    //updateReturnStatus
    public function updateReturnStatus(Request $request)
    {
        $booking = EquipmentBooking::where('id', $request->booking_id)->first();

        
        // code by AG start
        if($request->status == "1"){
            $booking_update = new BookingUpdate();
            $booking_update->booking_id = $booking->id;
            $booking_update->status = "returned";
            $booking_update->status_type = 'return_status';
            $booking_update->update_by_user_id = Auth::guard('vendor')->user()->id;
            $booking_update->user_type = 'vendor';
            $booking_update->save();
            
            $booking->shipping_status = 'returned';
            $booking->save();
        }
        
        // code by AG end
        
        $amount = intval($booking->security_deposit_amount);
        if ($amount < 1) {
            $booking->return_status = $request->status;
            $booking->save();
            Session::flash('success', 'Change Return Status Successfully..!');
            return back();
        }
        
        
        
        $rules = [
            'booking_id' => 'required',
            'refund_type' => 'required',
        ];

        if (
            $request->refund_type == 'partial'
        ) {
            $rules['partial_amount'] = "required|numeric|between:1, $amount";
        }


        $message = [
            'language_id.required' => 'The language field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $in = $request->all();

        $data = SecurityDepositRefund::where('booking_id', $request->booking_id)->first();
        if (
            $request->refund_type == 'full'
        ) {
            $in['status'] = 1;
        }
        if ($data) {
            $data->update($in);
        } else {
            $data = SecurityDepositRefund::create($in);
        }

        //booking return status 
        $booking->return_status = 1;
        $booking->save();

        $user = User::where('id', $booking->user_id)->select('username')->first();
        if ($user) {
            $user_name = $user->username;
        } else {
            $user_name = $booking->name;
        }
        $equipment_content = EquipmentContent::where('equipment_id', $booking->equipment_id)->first();

        //send mail to user
        $url = URL::to('/');
        $agree_url = "<a href='" . $url . "/security-diposit-refund/agree/" . $data->id . "'>Click here</a>";
        $raise_dispute_url = "<a href='" . $url . "/security-diposit-refund/raise-dispute/" . $data->id . "'>Raise Dispute</a>";

        $mailData = [];

        if ($request->refund_type == 'partial') {
            if ($booking->currency_symbol_position == 'left') {
                $amount =  $booking->currency_symbol . $request->partial_amount;
            } elseif ($booking->currency_symbol_position == 'right') {
                $amount =  $request->partial_amount . $booking->currency_symbol;
            }
        } elseif ($request->refund_type == 'full') {
            if ($booking->currency_symbol_position == 'left') {
                $amount =  $booking->currency_symbol . $booking->security_deposit_amount;
            } elseif ($booking->currency_symbol_position == 'right') {
                $amount =  $booking->security_deposit_amount . $booking->currency_symbol;
            }
        } else {
            if ($booking->currency_symbol_position == 'left') {
                $amount =  $booking->currency_symbol . '0';
            } elseif ($booking->currency_symbol_position == 'right') {
                $amount = '0' . $booking->currency_symbol;
            }
        }

        if ($booking->currency_symbol_position == 'left') {
            $security_deposit_amount =  $booking->currency_symbol . $booking->security_deposit_amount;
        } elseif ($booking->currency_symbol_position == 'right') {
            $security_deposit_amount =  $booking->security_deposit_amount . $booking->currency_symbol;
        }


        $mailTemplate = MailTemplate::query()->where('mail_type', '=', 'security_deposit_refund')->first();
        $mailData['subject'] = $mailTemplate->mail_subject;
        $mailBody = $mailTemplate->mail_body;

        // replacing with actual data
        $mailBody = str_replace('{username}', $user_name, $mailBody);
        $mailBody = str_replace('{equipment_name}', $equipment_content->title, $mailBody);
        $mailBody = str_replace('{amount}', $amount, $mailBody);
        $mailBody = str_replace('{booking_number}', '#' . $booking->booking_number, $mailBody);
        $mailBody = str_replace('{actual_security_deposit_amount}', $security_deposit_amount, $mailBody);

        $mailBody = str_replace('{refund_type}', ucfirst(str_replace('_', ' ', $data->refund_type)), $mailBody);

        if ($request->refund_type != 'full') {
            $mailBody .= '<p>If you agree then ' . $agree_url . ' or ' . $raise_dispute_url . '</p>';
        }

        $mailData['body'] = $mailBody;

        $mailData['recipient'] = $booking->email;

        $mailData['sessionMessage'] = 'Change Return Status Successfully..!';

        BasicMailer::sendMail($mailData);
        //end sendmail

        Session::flash('success', 'Change Return Status Successfully..!');

        return Response::json(['status' => 'success'], 200);
    }

    public function generateInvoice($bookingInfo)
    {
        $fileName = $bookingInfo->booking_number . '.pdf';

        $data['bookingInfo'] = $bookingInfo;

        $directory = public_path('assets/file/invoices/equipment/');
        @mkdir($directory, 0775, true);

        $fileLocated = $directory . $fileName;

        $data['taxData'] = Basic::select('equipment_tax_amount')->first();

        PDF::loadView('frontend.equipment.invoice', $data)->save($fileLocated);

        return $fileName;
    }

    // code by AG start
    
    public function assign_booking_route(Request $request, $id){
        $booking = EquipmentBooking::find($id);
        if(!empty($booking) && isset($request['selected_route'])){
            $booking->selected_route = $request['selected_route'];
            $booking->save();
            return redirect()->back()->with('success','Route Assigned to Booking');
        }
        else{
            return redirect()->back();
        }
    }
    
    public function select_equipment_for_accept_booking(Request $request, $id){
        $language = Language::where('code', $_GET['language']??'en')->first();
        $booking = EquipmentBooking::find($id);
        
        $details = Equipment::query()->join('equipment_contents', 'equipments.id', '=', 'equipment_contents.equipment_id')
      ->join('equipment_categories', 'equipment_categories.id', '=', 'equipment_contents.equipment_category_id')
      ->where('equipment_contents.language_id', '=', $language->id)
      ->where('equipments.id', '=', $booking->equipment_id)
      ->select('equipments.id', 'equipments.vendor_id', 'equipments.slider_images', 'equipment_contents.title', 'equipment_categories.name as categoryName', 'equipment_categories.slug as categorySlug', 'equipment_contents.description', 'equipment_contents.features', 'equipments.lowest_price', 'equipments.per_day_price', 'equipments.per_week_price', 'equipments.per_month_price', 'equipments.min_booking_days', 'equipments.max_booking_days', 'equipments.security_deposit_amount', 'equipment_contents.meta_keywords', 'equipment_contents.meta_description', 'equipment_contents.equipment_category_id')
      ->firstOrFail();
        if(!empty($details)){
            
            
            $allEquipment = Equipment::query()->join('equipment_contents', 'equipments.id', '=', 'equipment_contents.equipment_id')
              ->where('equipment_contents.language_id', '=', $language->id)
              ->where('equipments.vendor_id', Auth::guard('vendor')->user()->id)
              ->when($details, function ($query, $details) {
                $categoryId = $details->equipment_category_id;
        
                return $query->where('equipment_contents.equipment_category_id', '=', $categoryId);
              })
              ->select('equipments.id', 'equipments.thumbnail_image', 'equipments.lowest_price', 'equipment_contents.title', 'equipment_contents.slug', 'equipments.per_day_price', 'equipments.per_week_price', 'equipments.per_month_price', 'equipment_contents.features', 'equipments.offer', 'equipments.vendor_id', 'equipment_contents.equipment_category_id')
              ->get();
              
              $information['allEquipment'] = $allEquipment;
              $information['booking_id'] = $id;
              return view('vendors.booking.select-equipment', $information);
              
        }
        else{
            return redirect()->back();
        }
    }
    public function accept_declined_booking(Request $request, $id){
        $booking = EquipmentBooking::find($id);

        if (isset($request['selected_booking_equipment'])) {
            $booking->update([
                'vendor_id' => Auth::guard('vendor')->user()->id,
                'equipment_id'=> $request['selected_booking_equipment'],
                'accept_status' => 'accepted',
            ]);
            
            $booking_update = new BookingUpdate();
            $booking_update->booking_id = $id;
            $booking_update->status = 'accepted';
            $booking_update->status_type = 'accept_status';
            $booking_update->update_by_user_id = Auth::guard('vendor')->user()->id;
            $booking_update->user_type = 'vendor';
            $booking_update->save();

            $statusMsg = 'The shipping status of your booked equipment is pending.';
        }
        
        return redirect()->route('vendor.equipment_booking.bookings',['language' => 'en']);
    }
    
    // update the booking accept/decline status when vendor accept the booking
    public function update_accept_status(Request $request, $id)
    {
        $booking = EquipmentBooking::find($id);

        if ($request['accept_status'] == 'accepted') {
            $booking->update([
                'accept_status' => 'accepted'
            ]);
            
            $booking_update = new BookingUpdate();
            $booking_update->booking_id = $id;
            $booking_update->status = 'accepted';
            $booking_update->status_type = 'accept_status';
            $booking_update->update_by_user_id = Auth::guard('vendor')->user()->id;
            $booking_update->user_type = 'vendor';
            $booking_update->save();

            $statusMsg = 'is Accepted';
            
            $mailData['subject'] = 'Your booking has been accepted';

            $mailData['body'] = 'Hi ' . $booking->name . ',<br/><br/>This email is to notify the status of your booked equipment ' . $statusMsg;
    
            $mailData['recipient'] = $booking->email;
    
            $mailData['sessionMessage'] = 'Booking Accepted & mail has been sent successfully!';
    
            BasicMailer::sendMail($mailData);
        } else if ($request['accept_status'] == 'decline') {
            $booking->update([
                'accept_status' => 'decline'
            ]);

            $statusMsg = 'We want to inform you that you have taken your booked equipment.<br/><br/>Thank you.';
        }
        return redirect()->back();
    }
    
    
    public function assign_driver(Request $request, $id)
    {
        
        $booking = EquipmentBooking::find($id);
        
        $booking_updates = BookingUpdate::where('booking_id',$id)->get()->toArray();
        $booking_updates_status = array_column($booking_updates,'status');
        
        $driver = $request['driver']??'';

        $driver_data = Driver::find($driver);

        if( $driver != ''){
            
            $booking_driver_schedule = BookingDriverSchedule::where('booking_id',$id)->first();
            if( !empty( $booking_driver_schedule ) ){
                $booking_driver_schedule->driver_id = $driver;
                $booking_driver_schedule->date_and_time = $request->date_and_time;
                $booking_driver_schedule->status = 1;
                $booking_driver_schedule->save();
            }
            else{
                $booking_driver_schedule = new BookingDriverSchedule();
                $booking_driver_schedule->booking_id = $id;
                $booking_driver_schedule->driver_id = $driver;
                $booking_driver_schedule->date_and_time = $request->date_and_time;
                $booking_driver_schedule->status = 1;
                $booking_driver_schedule->save();
            }
            
            // $booking_driver = BookingDriver::where('booking_id',$id)->first();
            // if( !empty( $booking_driver ) ){
            //     $booking_driver->driver_id = $driver;
            //     $booking_driver->save();
            // }
            // else{
            //     $booking_driver = new BookingDriver();
            //     $booking_driver->booking_id = $id;
            //     $booking_driver->driver_id = $driver;
            //     $booking_driver->save();
            // }
            
            
            $booking_update = new BookingUpdate();
            $booking_update->booking_id = $id;
            
            $booking_update->status = 'assigned';
            $booking_update->status_type = 'shipping_status';
            
            $booking->shipping_status = 'assigned';
            
            $statusMsg = 'Booking assigned to your for delivery.';
            
            if($booking_updates_status[count($booking_updates_status) - 1] == 'swap_requested'){ //if(in_array('swap_requested',$booking_updates_status)){
                $booking_update->status = 'assigned_to_swap';
                $booking_update->status_type = 'swapping_status';
                $booking->shipping_status = 'assigned_to_swap';
                
                $statusMsg = 'Booking assigned to your for swap.';
            }
            if($booking_updates_status[count($booking_updates_status) - 1] == 'relocation_requested'){
                $booking_update->status = 'assigned_to_relocate';
                $booking_update->status_type = 'relocation_status';
                $booking->shipping_status = 'assigned_to_relocate';
                
                $statusMsg = 'Booking assigned to your for relocation.';
            }
            if(in_array('pickup_requested',$booking_updates_status)){
                $booking_update->status = 'assigned_for_pickup';
                $booking_update->status_type = 'return_status';
                
                $booking->shipping_status = 'assigned_for_pickup';
                
                $statusMsg = 'Booking assigned to your for pickup.';
            }
            
            $booking_update->update_by_user_id = Auth::guard('vendor')->user()->id;
            $booking_update->user_type = 'vendor';
            $booking_update->save();
            
           
            $booking->save();
        }

        
       
        
        return redirect()->back();
    }
    
    
    public function process_charge_additional_tonnage( Request $request, $id ){
        $details = EquipmentBooking::find($id);
        
        $equipment_fields = EquipmentFieldsValue::where('equipment_id', $details->equipment_id)->first();
        
        $additional_tonnage_charge_rate = 0;
        $allowed_weight = 0;
        
        if($equipment_fields){
			$multiple_charges_settings = json_decode($equipment_fields->multiple_charges_settings, true);
			
			if(isset($multiple_charges_settings['additional_tonnage_cost']) && $multiple_charges_settings['additional_tonnage_cost'] > 0){
			    $additional_tonnage_charge_rate = $multiple_charges_settings['additional_tonnage_cost'];
			}
			
			$allowed_weight = $multiple_charges_settings['allowed_ton']??0;
		}
        
        $weight_proof_img_data = array();
        // store proof image in storage
        if($request->file('helly_proof_of_weight')){
            $weight_proof_img = UploadFile::store(public_path('assets/img/delivery-proofs/'), $request->file('helly_proof_of_weight'));   
        }else{
            $weight_proof_img = "";
        }
        $weight_proof_img_data = array(
                'helly_proof_of_weight_image' => asset('assets/img/delivery-proofs/' . $weight_proof_img)
            );
        $allowed_weight = str_replace("ton", "",$allowed_weight);
        $allowed_weight = (int) $allowed_weight;
        $weight = $request->input('total_weight');
        
        $additonal_weigth = $weight - $allowed_weight;
        
        if($additonal_weigth > 0){
            $additional_charge_total = round(($additonal_weigth * $additional_tonnage_charge_rate), 2);
            
            $add_invoice = new AdditionalInvoice();
            $add_invoice->user_id = $details->user_id;
            $add_invoice->vendor_id = $details->vendor_id;
            $add_invoice->booking_id = $details->id;
            $add_invoice->additional_day = now();
            $add_invoice->amount = $additional_charge_total;
            $add_invoice->details = 'Additional Tonnage Charge | Weight Proof: <a href="'.$weight_proof_img_data['helly_proof_of_weight_image'].'">View Weight Proof</a>';
            $add_invoice->save();
      
            Session::flash('success', 'Addtional Tonnage Charged to Customer Successfully.');
            return redirect()->route('vendor.equipment_booking.bookings',['language'=>'en'])->with('success','Addtional Tonnage Charged to Customer Successfully.');
        }
        else{
            return redirect()->route('vendor.equipment_booking.bookings');
        }
    }
    
    
    public function charge_additional_tonnage( Request $request, $id ){
        
        $details = EquipmentBooking::find($id);

        $information['details'] = $details;

        if ($details->vendor_id != Auth::guard('vendor')->user()->id) {
            return redirect()->route('vendor.dashboard');
        }
        
        $equipment_fields = EquipmentFieldsValue::where('equipment_id', $details->equipment_id)->first();
        
        $additional_tonnage_charge_rate = 0;
        $allowed_weight = 0;

		if($equipment_fields){
			$multiple_charges_settings = json_decode($equipment_fields->multiple_charges_settings, true);
			
			if(isset($multiple_charges_settings['additional_tonnage_cost']) && $multiple_charges_settings['additional_tonnage_cost'] > 0){
			    $additional_tonnage_charge_rate = $multiple_charges_settings['additional_tonnage_cost'];
			}
			
			$allowed_weight = $multiple_charges_settings['allowed_ton']??0;
		}
		
		$information['additional_tonnage_charge_rate'] = $additional_tonnage_charge_rate;
		$information['allowed_weight'] = $allowed_weight;

        $information['language'] = Language::where('code', 'en')->first();

        $information['tax'] = Basic::select('equipment_tax_amount')->first();

        return view('vendors.booking.charge-additional-tonnage', $information);
        
    }
    // code by AG end

    public function updateShippingStatus(Request $request, $id)
    {
        $booking = EquipmentBooking::find($id);
        
        // code by AG start
        $booking_updates = BookingUpdate::where('booking_id',$id)->get()->toArray();
        $booking_updates_status = array_column($booking_updates,'status');
        
        
        $delivery_proof_img_data = array();
        if($request['shipping_status'] == 'delivered' || $request['shipping_status'] == 'swaped'){
            // store proof image in storage
            $delivery_proof_img = UploadFile::store(public_path('assets/img/delivery-proofs/'), $request->file('helly_proof_of_delivery'));
            $delivery_proof_img_data = array(
                    'delivery_from_image' => asset('assets/img/delivery-proofs/' . $delivery_proof_img)
                );
        }
        
        $booking->update([
                'shipping_status' => $request['shipping_status']
            ]);
            
        $booking_update = new BookingUpdate();
        $booking_update->booking_id = $id;
        $booking_update->status = $request['shipping_status'];
        
         $booking_update->status_type = 'shipping_status';
        
        if($booking_updates_status[count($booking_updates_status) - 1] == 'swap_requested'){ //if(in_array('swap_requested',$booking_updates_status)){
            
            $booking_update->status_type = 'swapping_status';
        }
        if($booking_updates_status[count($booking_updates_status) - 1] == 'relocation_requested'){
            
            $booking_update->status_type = 'relocation_status';
            
        }
        if(in_array('pickup_requested',$booking_updates_status)){
            
            $booking_update->status_type = 'return_status';
            
        }
       
        $booking_update->update_by_user_id = Auth::guard('vendor')->user()->id;
        $booking_update->user_type = 'vendor';
        $booking_update->update_details = json_encode($delivery_proof_img_data);
        $booking_update->save();
        
        
        $statusMsg = 'The shipping status of your booked equipment is '.$request['shipping_status'].'.';
        // code by AG end
        
        // commented by AG start

        // if ($request['shipping_status'] == 'pending') {
        //     $booking->update([
        //         'shipping_status' => 'pending'
        //     ]);

        //     $statusMsg = 'The shipping status of your booked equipment is pending.';
        // } else if ($request['shipping_status'] == 'taken') {
        //     $booking->update([
        //         'shipping_status' => 'taken'
        //     ]);

        //     $statusMsg = 'We want to inform you that you have taken your booked equipment.<br/><br/>Thank you.';
        // } else if ($request['shipping_status'] == 'delivered') {
        //     $booking->update([
        //         'shipping_status' => 'delivered'
        //     ]);

        //     $statusMsg = 'The equipment you have booked has been successfully delivered to your location.';
        // } else {
        //     $booking->update([
        //         'shipping_status' => 'returned'
        //     ]);

        //     $statusMsg = 'You have returned your booked equipment.<br/><br/>Thank you.';
        // }
        
        // commented by AG end
        $equipment_content = EquipmentContent::where('equipment_id', $booking->equipment_id)->first();
        $vendor_detail = Vendor::where('id', $booking->vendor_id)->first();
        
        if($request['shipping_status'] == "assigned"){
            
            $mailData['subject'] = 'Your Equipment Shipment Notification';

            $mailData['body'] = 'Dear ' .$booking->name.',';
            $mailData['body'] .= "Your equipment order has been processed and is now being prepared for shipment. <br><br>";
            $mailData['body'] .= "Your order details are as follows: <br><br>";
            
            $mailData['body'] .= "Order Number: ".$booking->booking_number." <br>";
            $mailData['body'] .= "Equipment: ".$equipment_content->title." <br>";
            $mailData['body'] .= "Quantity: "."1"." <br>";
            $mailData['body'] .= "Shipping Address: ".$booking->location." <br><br>";
            
            $mailData['body'] .= "Your equipment is scheduled to be shipped on ".$booking->start_date ." ".$booking->end_date.".<br><br>";
            $mailData['body'] .= "You will receive a separate email with the tracking information once your package has been dispatched. <br><br>";
            $mailData['body'] .= "Thank you for choosing ".$vendor_detail->username.". Should you have any questions or concerns regarding your order, feel free to contact our customer support team at .".$vendor_detail->email."<br><br>";
            
            $mailData['body'] .= "Best regards,<br>";
            $mailData['body'] .= $vendor_detail->username ."Team";

            $mailData['recipient'] = $booking->email;
    
            $mailData['sessionMessage'] = 'Shipping status updated & mail has been sent successfully!';
    
            BasicMailer::sendMail($mailData);
            
        }
        elseif($request['shipping_status'] == "pickedup"){
            $invoice = $this->generateInvoice($booking);
            $mailData = [];
            
        
        
            
            $mailData['subject'] = 'Final Equipment Pickup Notification';

            $mailData['body'] = 'Dear ' .$booking->name.',';
            $mailData['body'] .= "We are reaching out to inform you that the final pickup of the equipment is scheduled as follows: <br><br>";

            $mailData['body'] .= "Order Number: ".$booking->booking_number." <br>";
            $mailData['body'] .= "Equipment: ".$equipment_content->title." <br>";
            $mailData['body'] .= "Final Pickup Date: ".$booking->start_date ." ". $booking->end_date ." <br>";
            $mailData['body'] .= "Pickup Address: ".$booking->location." <br><br>";
            
            $mailData['body'] .= "Our team will be arriving at the specified location on the designated date for the final pickup of the equipment.<br><br>";
             
            $mailData['body'] .= "Should you have any questions or require further assistance, please don’t hesitate to contact us at .".$vendor_detail->email."<br><br>";
            $mailData['body'] .= "Thank you for your cooperation throughout this process.<br><br>";
            if (isset($invoice)) {
                $file = asset("/public/assets/file/invoices/equipment/")."/".$invoice;
                if($file){
                    // $mailData['invoice'] = $file;   
                    $mailData['body'] .= "<a href=".$file.">View PDF</a><br><br>";
                }
            }
            
            $mailData['body'] .= "Best regards,<br>";
            $mailData['body'] .= $vendor_detail->username ."Team";

            $mailData['recipient'] = $booking->email;
    
            $mailData['sessionMessage'] = 'Shipping status updated & mail has been sent successfully!';
    
            BasicMailer::sendMail($mailData);
            
        }
        elseif($request['shipping_status'] == "out_for_delivery"){
            
            $mailData['subject'] = 'Your Equipment is Out for Delivery';

            $mailData['body'] = 'Dear ' .$booking->name.',';
            $mailData['body'] .= "Exciting news! Your equipment order is now out for delivery and is on its way to you. Here are the details of your order: <br><br>";
            
            $mailData['body'] .= "Order Number: ".$booking->booking_number." <br>";
            $mailData['body'] .= "Equipment: ".$equipment_content->title." <br>";
            $mailData['body'] .= "Quantity: "."1"." <br>";
            $mailData['body'] .= "Shipping Address: ".$booking->location." <br><br>";
            
            $mailData['body'] .= "Our delivery partner will be bringing your (Equipment Type) to you soon. Please ensure someone is available to receive the delivery at the provided shipping address.<br><br>";
            $mailData['body'] .= "If you have any questions or need further assistance, please don’t hesitate to contact our customer support team at ".$vendor_detail->email.". <br><br>";
            $mailData['body'] .= "Thank you for choosing ".$vendor_detail->username.".  We appreciate your business.<br><br>";
            
            $mailData['body'] .= "Best regards,<br>";
            $mailData['body'] .= $vendor_detail->username ."Team";

            $mailData['recipient'] = $booking->email;
    
            $mailData['sessionMessage'] = 'Shipping status updated & mail has been sent successfully!';
    
            BasicMailer::sendMail($mailData);
            
        }
        elseif($request['shipping_status'] == "delivered"){
            
            $mailData['subject'] = 'Your Equipment is Delivered';

            $mailData['body'] = 'Dear ' .$booking->name.',';
            $mailData['body'] .= "Your equipment order has been Delivered. <br><br>";
            $mailData['body'] .= "Your order details are as follows: <br><br>";
            
            $mailData['body'] .= "Order Number: ".$booking->booking_number." <br>";
            $mailData['body'] .= "Equipment: ".$equipment_content->title." <br>";
            $mailData['body'] .= "Quantity: "."1"." <br>";
            $mailData['body'] .= "Shipping Address: ".$booking->location." <br><br>";
        
            $mailData['body'] .= "Thank you for choosing ".$vendor_detail->username.". Should you have any questions or concerns regarding your order, feel free to contact our customer support team at .".$vendor_detail->email."<br><br>";
            
            $mailData['body'] .= "Best regards,<br>";
            $mailData['body'] .= $vendor_detail->username ."Team";

            $mailData['recipient'] = $booking->email;
    
            $mailData['sessionMessage'] = 'Shipping status updated & mail has been sent successfully!';
    
            BasicMailer::sendMail($mailData);
            
        }
       
        
        
        
        
        $vendor = Vendor::find($booking->vendor_id);
        $mailData2['subject'] = 'Notification of shipping status';

        $mailData2['body'] = 'Hi ' . $booking->name . ',<br/><br/>This email is to notify the shipping status of your booked equipment. ' . $statusMsg;

        $mailData2['recipient'] = $vendor->email;

        $mailData2['sessionMessage'] = 'Shipping status updated & mail has been sent successfully!';

        BasicMailer::sendMail($mailData2);

        return redirect()->back();
    }

    public function show($id, Request $request)
    {
        $details = EquipmentBooking::find($id);

        $information['details'] = $details;

        if ($details->vendor_id != Auth::guard('vendor')->user()->id) {
            return redirect()->route('vendor.dashboard');
        }

        $information['language'] = Language::where('code', $request->language)->first();

        $information['tax'] = Basic::select('equipment_tax_amount')->first();
        
        
        
        // code by AG start
      $booking_updates = BookingUpdate::where('booking_id',$id)->get();
      $status_timeline_html = '';
      $status_timeline = array(
            'accepted'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">1</span>
                                </span>
                                <h5>Accepted</h5>
                              </div>
                              <div class="seperator"></div>',
            'assigned'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">2</span>
                                </span>
                                <h5>Assigned</h5>
                              </div>
                              <div class="seperator"></div>',
            'pickedup'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">3</span>
                                </span>
                                <h5>Pickedup</h5>
                              </div>
                              <div class="seperator"></div>',
            'out_for_delivery'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">4</span>
                                </span>
                                <h5>Out For Delivery</h5>
                              </div>
                              <div class="seperator"></div>',
            'delivered'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">5</span>
                                </span>
                                <h5>Delivered</h5>
                              </div>
                              '
          );
         $status_timeline_for_swaping = array(
            'assigned_to_swap'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">1</span>
                                </span>
                                <h5>Assigned to swap</h5>
                              </div>
                              <div class="seperator"></div>',
            'pickedup_to_swap'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">2</span>
                                </span>
                                <h5>Pickedup to swap</h5>
                              </div>
                              <div class="seperator"></div>',
            'out_for_swap'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">3</span>
                                </span>
                                <h5>Out For Swap</h5>
                              </div>
                              <div class="seperator"></div>',
            'swaped'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">4</span>
                                </span>
                                <h5>Swaped</h5>
                              </div>
                              '
          );
          
          $status_timeline_for_pickup = array(
            'assigned_for_pickup'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">1</span>
                                </span>
                                <h5>Assigned</h5>
                              </div>
                              <div class="seperator"></div>',
            'out_for_pickup'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">2</span>
                                </span>
                                <h5>Out for pickup</h5>
                              </div>
                              <div class="seperator"></div>',
            'pickedup_from_customer'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">2</span>
                                </span>
                                <h5>Pickedup From Customer</h5>
                              </div>
                              <div class="seperator"></div>',
            'returned'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">3</span>
                                </span>
                                <h5>Returned</h5>
                              </div>',
          );
          
          $status_timeline_for_relocation = array(
            'assigned_to_relocate'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">1</span>
                                </span>
                                <h5>Assigned to relocate</h5>
                              </div>
                              <div class="seperator"></div>',
            'pickedup_to_relocate'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">2</span>
                                </span>
                                <h5>Pickedup to relocate</h5>
                              </div>
                              <div class="seperator"></div>',
            'out_for_relocate'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">3</span>
                                </span>
                                <h5>Out For relocate</h5>
                              </div>
                              <div class="seperator"></div>',
            'relocated'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">4</span>
                                </span>
                                <h5>Relocated</h5>
                              </div>
                              '
          );
          
        if( !empty($booking_updates)){
            $status_timeline_html .= '<div class="bar-progress mt-5 mb-5 mt-lg-0">';
            $step = 1;
            foreach($booking_updates as $key => $update){
                
                              
                if($update->status == 'swap_requested'){
                    $step = 0;
                    $status_timeline = $status_timeline_for_swaping;
                    $status_timeline_html .= '</div>';
                    $status_timeline_html .= '<div class="bar-progress mt-5 mb-5 mt-lg-0"><div class="step step-active">
                                <span class="number-container">
                                    <span class="number">S</span>
                                </span>
                                <h5>Swap Requested</h5>
                                <small class="status-at">at '.date("Y-m-d h:i A", strtotime($update->created_at)).'</small>
                              </div>
                              </div>';
                    $status_timeline_html .= '<div class="bar-progress mt-5 mb-5 mt-lg-0">';
                }
                else if($update->status == 'pickup_requested'){
                    $step = 0;
                    $status_timeline = $status_timeline_for_pickup;
                    $status_timeline_html .= '</div>';
                    $status_timeline_html .= '<div class="bar-progress mt-5 mb-5 mt-lg-0"><div class="step step-active">
                                <span class="number-container">
                                    <span class="number">P</span>
                                </span>
                                <h5>Pickup Requested</h5>
                                <small class="status-at">at '.date("Y-m-d h:i A", strtotime($update->created_at)).'</small>
                              </div>
                              </div>';
                    $status_timeline_html .= '<div class="bar-progress mt-5 mb-5 mt-lg-0">';
                }
                else if($update->status == 'relocation_requested'){
                    $step = 0;
                    $status_timeline = $status_timeline_for_relocation;
                    $status_timeline_html .= '</div>';
                    $status_timeline_html .= '<div class="bar-progress mt-5 mb-5 mt-lg-0"><div class="step step-active">
                                <span class="number-container">
                                    <span class="number">R</span>
                                </span>
                                <h5>Relocation Requested</h5>
                                <small class="status-at">at '.date("Y-m-d h:i A", strtotime($update->created_at)).'</small>
                              </div>
                              </div>';
                    $status_timeline_html .= '<div class="bar-progress mt-5 mb-5 mt-lg-0">';
                }
                else{
                    if($update->status != 'pickedup_from_customer'){
                        
                        $proof = '';
                        if($update->status == 'delivered' || $update->status == 'swaped'){
                           $update_details = $update->update_details();
                           if(isset($update_details['delivery_from_image'])){
                               $proof = '<a target="_blank" href="'.$update_details['delivery_from_image'].'">View Proof</a>';
                           }
                        }
                        $status_timeline_html .= '<div class="step step-active">
                                    <span class="number-container">
                                        <span class="number">'.$step.'</span>
                                    </span>
                                    <h5>'.$update->status.'
                                    </h5>
                                    <small class="status-at">at '.date("Y-m-d h:i A", strtotime($update->created_at)).'</small>
                                    '.$proof.'
                                  </div>
                                  ';
                        if($update->status != 'delivered' && $update->status != 'swaped' && $update->status != 'returned' && $update->status != 'relocated'){
                            $status_timeline_html .= '<div class="seperator"></div>';
                        }
                    }
                    
                    
                }
                if(( count($booking_updates) - 1 ) == $key){
                    $start_unfilled = false;
                    foreach($status_timeline as $key => $step_){
                        if($start_unfilled){
                            if($key != 'pickedup_from_customer'){
                                $status_timeline_html .= $step_;
                            }
                            
                        }
                        if($key == $update->status){
                            $start_unfilled = true;
                        }
                    }
                }             
                $step = $step + 1;
            }
            
            $status_timeline_html .= '</div>';
        }
      $information['status_timeline_html'] = $status_timeline_html;
      // code by AG end

        return view('vendors.booking.details', $information);
    }


    public function report(Request $request)
    {
        $queryResult['onlineGateways'] = OnlineGateway::query()->where('status', '=', 1)->get();
        $queryResult['offlineGateways'] = OfflineGateway::query()->where('status', '=', 1)->orderBy('serial_number', 'asc')->get();

        $from = $to = $paymentGateway = $paymentStatus = $shippingStatus = null;

        if ($request->filled('payment_gateway')) {
            $paymentGateway = $request->payment_gateway;
        }
        if ($request->filled('payment_status')) {
            $paymentStatus = $request->payment_status;
        }
        if ($request->filled('shipping_status')) {
            $shippingStatus = $request->shipping_status;
        }

        if ($request->filled('from') && $request->filled('to')) {
            $from = Carbon::parse($request->from)->toDateString();
            $to = Carbon::parse($request->to)->toDateString();

            $records = EquipmentBooking::query()
                ->where('vendor_id', Auth::guard('vendor')->user()->id)
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->when($paymentGateway, function (Builder $query, $paymentGateway) {
                    return $query->where('payment_method', '=', $paymentGateway);
                })
                ->when($paymentStatus, function (Builder $query, $paymentStatus) {
                    return $query->where('payment_status', '=', $paymentStatus);
                })
                ->when($shippingStatus, function (Builder $query, $shippingStatus) {
                    return $query->where('shipping_status', '=', $shippingStatus);
                })
                ->select('booking_number', 'name', 'contact_number', 'email', 'equipment_id', 'start_date', 'end_date', 'shipping_method', 'location', 'total', 'discount', 'shipping_cost', 'tax', 'grand_total', 'received_amount', 'vendor_id', 'comission', 'currency_symbol', 'currency_symbol_position', 'payment_method', 'payment_status', 'shipping_status', 'created_at')
                ->orderByDesc('id');

            $collection_1 = $this->manipulateCollection($records->get());
            Session::put('equipment_bookings', $collection_1);

            $collection_2 = $this->manipulateCollection($records->paginate(10));
            $queryResult['bookings'] = $collection_2;
        } else {
            Session::put('equipment_bookings', null);
            $queryResult['bookings'] = [];
        }

        return view('vendors.booking.report', $queryResult);
    }

    public function manipulateCollection($bookings)
    {
        $language = Language::query()->where('is_default', '=', 1)->first();

        $bookings->map(function ($booking) use ($language) {
            // equipment title
            $equipment = $booking->equipmentInfo()->first();
            $booking['equipmentTitle'] = $equipment->content()->where('language_id', $language->id)->pluck('title')->first();

            // format booking start date
            $startDateObj = Carbon::parse($booking->start_date);
            $booking['startDate'] = $startDateObj->format('M d, Y');

            // format booking end date
            $endDateObj = Carbon::parse($booking->end_date);
            $booking['endDate'] = $endDateObj->format('M d, Y');

            // format booking create date
            $createDateObj = Carbon::parse($booking->created_at);
            $booking['createdAt'] = $createDateObj->format('M d, Y');
        });

        return $bookings;
    }

    public function exportReport()
    {
        if (Session::has('equipment_bookings')) {
            $equipmentBookings = Session::get('equipment_bookings');

            if (count($equipmentBookings) == 0) {
                Session::flash('warning', 'No booking found to export!');

                return redirect()->back();
            } else {
                return Excel::download(new EquipmentBookingsExport($equipmentBookings), 'equipment-bookings.csv');
            }
        } else {
            Session::flash('error', 'There has no booking to export.');

            return redirect()->back();
        }
    }
    public function driver_schedule(Request $request)
    {
        
        $bookingDriverSchedules = BookingDriverSchedule::where('status', 1)->where('date_and_time', '<=' , Carbon::now())->get();
        
        if(count($bookingDriverSchedules) > 0){
            foreach($bookingDriverSchedules as $bookingDriverSchedule){
                $driver_data = Driver::where('id', $bookingDriverSchedule->driver_id)->first();
                $booking = EquipmentBooking::where('id', $bookingDriverSchedule->booking_id)->first();
                $booking_update = BookingDriverSchedule::find($bookingDriverSchedule->id);
                $booking_update->status = 0;
                $booking_update->update();
                $booking_driver = BookingDriver::where('booking_id',$bookingDriverSchedule->booking_id)->first();
                if( !empty( $booking_driver ) ){
                    $booking_driver->booking_id = $bookingDriverSchedule->booking_id;
                    $booking_driver->driver_id = $bookingDriverSchedule->driver_id;
                    $booking_driver->save();
                }
                else{
                    $bookingDriver = new BookingDriver();
                    $bookingDriver->booking_id = $bookingDriverSchedule->booking_id;
                    $bookingDriver->driver_id = $bookingDriverSchedule->driver_id;
                    $bookingDriver->save();
                }
                
                 $mailData['subject'] = 'New booking assigned to you';

                $mailData['body'] = 'Hi ' . $driver_data->username . ',<br/><br/>Booking (#'.$booking->booking_number.')<br>';
        
                $mailData['recipient'] = $driver_data->email;
        
                $mailData['sessionMessage'] = 'Driver Assigned & mail has been sent successfully!';
                
                $driver_data->notify(new BasicNotify($mailData['subject']));
        
                BasicMailer::sendMail($mailData);
            }
            return "driver booking schedule process successfully!";
        }else{
            return "driver booking schedule not avalaible";
        }
        
       
    }
 
}
