<?php

namespace App\Http\Controllers\BackEnd\Instrument;

use App\Exports\EquipmentBookingsExport;
use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Commission;
use App\Models\Earning;
use App\Models\Instrument\Equipment;
use App\Models\Instrument\EquipmentBooking;
use App\Models\Instrument\EquipmentContent;
use App\Models\Instrument\SecurityDepositRefund;
use App\Models\Language;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Transcation;
use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class BookingController extends Controller
{
  public function bookings(Request $request)
  {
    $information['basicData'] = Basic::select('self_pickup_status', 'two_way_delivery_status')->first();

    $language = Language::where('code', $request->language)->first();

    $bookingNumber = $vendor = $paymentStatus = $shippingType = $shippingStatus = $returnStatuss = null;

    if ($request->filled('booking_no')) {
      $bookingNumber = $request['booking_no'];
    }
    if ($request->filled('vendor')) {
      $vendor = $request['vendor'];
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

    $bookings = EquipmentBooking::query()->when($bookingNumber, function ($query, $bookingNumber) {
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
      ->when($vendor, function ($query, $vendor) {
        if ($vendor == 'admin') {
          return $query->where('vendor_id', '=', NULL);
        } elseif ($vendor != 'all') {
          return $query->where('vendor_id', '=', $vendor);
        }
      })
      ->orderByDesc('id')
      ->paginate(10);

    $bookings->map(function ($booking) use ($language) {
      $equipment = $booking->equipmentInfo()->first();
      $booking['equipmentTitle'] = $equipment->content()->where('language_id', $language->id)->select('title', 'slug')->first();
    });

    $information['vendors'] = Vendor::where('status', 1)->get();

    $information['bookings'] = $bookings;

    return view('backend.instrument.booking.index', $information);
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

      //calculate commission start
      $equipment = Equipment::findOrFail($booking->equipment_id);
      if (!empty($equipment)) {
        if (
          $equipment->vendor_id != NULL
        ) {
          $vendor_id = $equipment->vendor_id;
        } else {
          $vendor_id = NULL;
        }
      } else {
        $vendor_id = NULL;
      }
      //calculate commission
      $percent = Commission::select('equipment_commission')->first();

      $commission = (($booking->total - $booking->discount) * $percent->equipment_commission) / 100;

      //get vendor
      $vendor = Vendor::where('id', $booking->vendor_id)->first();


      //add blance to admin revinue
      $earning = Earning::first();

      $earning->total_revenue = $earning->total_revenue + $booking->grand_total;
      if ($vendor) {
        $earning->total_earning = $earning->total_earning + $commission + $booking->tax;
      } else {
        $earning->total_earning = $earning->total_earning + ($booking->grand_total - $booking->security_deposit_amount);
      }
      $earning->save();


      //store Balance  to vendor
      if ($vendor) {
        $pre_balance = $vendor->amount;
        $vendor->amount = $vendor->amount + ($booking->grand_total - ($commission + $booking->tax + $booking->security_deposit_amount));
        $vendor->save();
        $after_balance = $vendor->amount;

        $received_amount = ($booking->grand_total - ($commission + $booking->tax + $booking->security_deposit_amount));

        // then, update the invoice field info in database
        $booking->update([
          'invoice' => $invoice,
          'comission' => $commission,
          'received_amount' => $received_amount,
        ]);
      } else {
        // then, update the invoice field info in database
        $booking->update([
          'invoice' => $invoice
        ]);
        $received_amount = $booking->grand_total - ($booking->security_deposit_amount + $booking->tax);
        $after_balance = NULL;
        $pre_balance = NULL;
      }
      //calculate commission end

      if (!is_null($vendor_id)) {
        $comission = $booking->comission;
      } else {
        $comission = $booking->grand_total - ($booking->security_deposit_amount + $booking->tax);
      }

      //store data to transcation table
      $transactionStoreArr = [
        'transcation_id' => time(),
        'booking_id' => $booking->id,
        'transcation_type' => 1,
        'user_id' => $booking->user_id,
        'vendor_id' => $vendor_id,
        'payment_status' => 1,
        'payment_method' => $booking->payment_method,
        'shipping_charge' => $booking->shipping_cost,
        'commission' => $comission,
        'security_deposit' => $booking->security_deposit_amount,
        'tax' => $booking->tax,
        'grand_total' => $received_amount,
        'pre_balance' => $pre_balance,
        'after_balance' => $after_balance,
        'gateway_type' => $booking->gateway_type,
        'currency_symbol' => $booking->currency_symbol,
        'currency_symbol_position' => $booking->currency_symbol_position,
      ];

      storeTranscation($transactionStoreArr);

      $transactionMsg = "The transaction id is " . $transactionStoreArr['transcation_id'];
    } else {
      $booking->update([
        'payment_status' => 'rejected'
      ]);
      $statusMsg = 'Your payment has been rejected.';
      $transactionMsg = NULL;
    }

    if ($booking->user_id != NULL) {
      $url = URL::to('/');
      $link = "<a href='" . $url . "/user/equipment-booking/" . $booking->id . "/details'>View Details</a>";
    } else {
      $link = NULL;
    }

    $mailData = [];

    if (isset($invoice)) {
      $mailData['invoice'] = public_path('assets/file/invoices/equipment/') . $invoice;
    }

    $mailData['subject'] = 'Notification of payment status';

    $mailData['body'] = 'Hi ' . $booking->name . ',<br/><br/>This email is to notify the payment status of your equipment booking. ' . $statusMsg . '<p> Booking Id : #' . $booking->booking_number . '</p> ' . $link . '.' . $transactionMsg;

    $mailData['recipient'] = $booking->email;

    $mailData['sessionMessage'] = 'Payment status updated & mail has been sent successfully!';

    BasicMailer::sendMail($mailData);

    return redirect()->back();
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

  public function updateShippingStatus(Request $request, $id)
  {
    $booking = EquipmentBooking::find($id);

    if ($request['shipping_status'] == 'pending') {
      $booking->update([
        'shipping_status' => 'pending'
      ]);

      $statusMsg = 'The shipping status of your booked equipment is pending.';
    } else if ($request['shipping_status'] == 'taken') {
      $booking->update([
        'shipping_status' => 'taken'
      ]);

      $statusMsg = 'We want to inform you that you have taken your booked equipment.<br/><br/>Thank you.';
    } else if ($request['shipping_status'] == 'delivered') {
      $booking->update([
        'shipping_status' => 'delivered'
      ]);

      $statusMsg = 'The equipment you have booked has been successfully delivered to your location.';
    } else {
      $booking->update([
        'shipping_status' => 'returned'
      ]);

      $statusMsg = 'You have returned your booked equipment.<br/><br/>Thank you.';
    }

    $mailData['subject'] = 'Notification of shipping status';

    $mailData['body'] = 'Hi ' . $booking->name . ',<br/><br/>This email is to notify the shipping status of your booked equipment. ' . $statusMsg;

    $mailData['recipient'] = $booking->email;

    $mailData['sessionMessage'] = 'Shipping status updated & mail has been sent successfully!';

    BasicMailer::sendMail($mailData);

    return redirect()->back();
  }
  //updateReturnStatus
  public function updateReturnStatus(Request $request)
  {
    $booking = EquipmentBooking::where('id', $request->booking_id)->first();


    $amount = intval($booking->security_deposit_amount);
    $rules = [
      'booking_id' => 'required',
      'refund_type' => 'required',
    ];

    if ($request->refund_type == 'partial') {
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
    if ($request->refund_type == 'full') {
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


    if ($request->refund_type != 'full' && !is_null($booking->vendor_id)) {
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

  //updateReturnStatus2 
  public function updateReturnStatus2(Request $request)
  {
    $booking = EquipmentBooking::where('id', $request->id)->first();
    $booking->return_status = $request->status;
    $booking->save();
    Session::flash('success', 'Change Return Status Successfully..!');
    return back();
  }

  public function show($id, Request $request)
  {
    $information['details'] = EquipmentBooking::find($id);

    $information['language'] = Language::where('code', $request->language)->first();

    $information['tax'] = Basic::select('equipment_tax_amount')->first();

    return view('backend.instrument.booking.details', $information);
  }

  public function destroy($id)
  {
    $booking = EquipmentBooking::find($id);

    // delete the attachment
    @unlink(public_path('assets/file/attachments/equipment/') . $booking->attachment);

    // delete the invoice
    @unlink(public_path('assets/file/invoices/equipment/') . $booking->invoice);

    $deposits = $booking->security_deposit()->get();
    foreach ($deposits as $deposit) {
      $deposit->delete();
    }

    $booking->delete();

    return redirect()->back()->with('success', 'Booking deleted successfully!');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $booking = EquipmentBooking::find($id);

      // delete the attachment
      @unlink(public_path('assets/file/attachments/equipment/') . $booking->attachment);

      // delete the invoice
      @unlink(public_path('assets/file/invoices/equipment/') . $booking->invoice);

      $deposits = $booking->security_deposit()->get();
      foreach ($deposits as $deposit) {
        $deposit->delete();
      }

      $booking->delete();
    }

    Session::flash('success', 'Bookings deleted successfully!');

    return response()->json(['status' => 'success'], 200);
  }


  public function report(Request $request)
  {
    $queryResult['onlineGateways'] = OnlineGateway::query()->where('status', '=', 1)->get();
    $queryResult['offlineGateways'] = OfflineGateway::query()->where('status', '=', 1)->orderBy('serial_number', 'asc')->get();

    $from = $to = $vendor = $paymentGateway = $paymentStatus = $shippingStatus = null;

    if ($request->filled('payment_gateway')) {
      $paymentGateway = $request->payment_gateway;
    }
    if ($request->filled('vendor')) {
      $vendor = $request->vendor;
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
        ->whereDate('created_at', '>=', $from)
        ->whereDate('created_at', '<=', $to)
        ->when($paymentGateway, function (Builder $query, $paymentGateway) {
          return $query->where('payment_method', '=', $paymentGateway);
        })
        ->when($vendor, function ($query, $vendor) {
          if ($vendor == 'admin') {
            return $query->where('vendor_id', '=', null);
          } else {
            return $query->where('vendor_id', $vendor);
          }
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

    $queryResult['vendors'] = Vendor::where('status', 1)->get();

    return view('backend.instrument.booking.report', $queryResult);
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
}
