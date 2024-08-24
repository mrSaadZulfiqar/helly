<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Instrument\BookingProcessController;
use App\Http\Controllers\FrontEnd\Shop\PurchaseProcessController;
use App\Models\Commission;
use App\Models\Earning;
use App\Models\Instrument\Equipment;
use App\Models\Shop\Product;
use App\Models\Transcation;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PaytmController extends Controller
{
  public function index(Request $request, $paymentFor)
  {
    if ($paymentFor == 'product purchase') {
      // get the products from session
      if ($request->session()->has('productCart')) {
        $productList = $request->session()->get('productCart');
      } else {
        Session::flash('error', 'Something went wrong!');

        return redirect()->route('shop.products');
      }

      $purchaseProcess = new PurchaseProcessController();

      // do calculation
      $calculatedData = $purchaseProcess->calculation($request, $productList);
    } else if ($paymentFor == 'equipment booking') {
      // check whether the equipment lowest price exist or not in session
      if (!$request->session()->has('totalPrice')) {
        Session::flash('error', 'Something went wrong!');

        return redirect()->route('all_equipment');
      }

      $bookingProcess = new BookingProcessController();

      // do calculation
      $calculatedData = $bookingProcess->calculation($request);
    }

    $currencyInfo = $this->getCurrencyInfo();

    // checking whether the currency is set to 'INR' or not
    if ($currencyInfo->base_currency_text !== 'INR') {
      return redirect()->back()->with('error', 'Invalid currency for paytm payment.')->withInput();
    }

    if ($paymentFor == 'product purchase') {
      $arrData = array(
        'billingFirstName' => $request['billing_first_name'],
        'billingLastName' => $request['billing_last_name'],
        'billingEmail' => $request['billing_email'],
        'billingContactNumber' => $request['billing_contact_number'],
        'billingAddress' => $request['billing_address'],
        'billingCity' => $request['billing_city'],
        'billingState' => $request['billing_state'],
        'billingCountry' => $request['billing_country'],
        'shippingFirstName' => $request['shipping_first_name'],
        'shippingLastName' => $request['shipping_last_name'],
        'shippingEmail' => $request['shipping_email'],
        'shippingContactNumber' => $request['shipping_contact_number'],
        'shippingAddress' => $request['shipping_address'],
        'shippingCity' => $request['shipping_city'],
        'shippingState' => $request['shipping_state'],
        'shippingCountry' => $request['shipping_country'],
        'total' => $calculatedData['total'],
        'discount' => $calculatedData['discount'],
        'productShippingChargeId' => $request->exists('charge_id') ? $request['charge_id'] : null,
        'shippingCharge' => $calculatedData['shippingCharge'],
        'tax' => $calculatedData['tax'],
        'grandTotal' => $calculatedData['grandTotal'],
        'currencyText' => $currencyInfo->base_currency_text,
        'currencyTextPosition' => $currencyInfo->base_currency_text_position,
        'currencySymbol' => $currencyInfo->base_currency_symbol,
        'currencySymbolPosition' => $currencyInfo->base_currency_symbol_position,
        'paymentMethod' => 'Paytm',
        'gatewayType' => 'online',
        'paymentStatus' => 'completed',
        'orderStatus' => 'pending'
      );

      $notifyURL = route('shop.purchase_product.paytm.notify');

      $customerEmail = $request['billing_email'];
      $customerPhone = $request['billing_contact_number'];
    } else if ($paymentFor == 'equipment booking') {
      // get start & end date
      $dates = $bookingProcess->getDates($request['dates']);

      // get location name
      $location_name = $bookingProcess->getLocation($request['location']);

      $arrData = array(
        'name' => $request['name'],
        'contactNumber' => $request['contact_number'],
        'email' => $request['email'],
        'equipmentId' => $request['equipment_id'],
        'startDate' => $dates['startDate'],
        'endDate' => $dates['endDate'],
        'shippingMethod' => $request->filled('shipping_method') ? $request['shipping_method'] : null,
        'location' => $location_name,
        'total' => $calculatedData['total'],
        'discount' => $calculatedData['discount'],
        'shippingCost' => $calculatedData['shippingCharge'],
        'tax' => $calculatedData['tax'],
        'grandTotal' => $calculatedData['grandTotal'],
        'security_deposit_amount' => $calculatedData['security_deposit_amount'],
        'currencySymbol' => $currencyInfo->base_currency_symbol,
        'currencySymbolPosition' => $currencyInfo->base_currency_symbol_position,
        'currencyText' => $currencyInfo->base_currency_text,
        'currencyTextPosition' => $currencyInfo->base_currency_text_position,
        'paymentMethod' => 'Paytm',
        'gatewayType' => 'online',
        'paymentStatus' => 'completed',
        'shippingStatus' => !$request->filled('shipping_method') ? null : 'pending'
      );

      $notifyURL = route('equipment.make_booking.paytm.notify');

      $customerEmail = $request['email'];
      $customerPhone = $request['contact_number'];
    }

    $payment = PaytmWallet::with('receive');

    $payment->prepare([
      'order' => time(),
      'user' => uniqid(),
      'mobile_number' => $customerPhone,
      'email' => $customerEmail,
      'amount' => round($calculatedData['grandTotal'], 2),
      'callback_url' => $notifyURL
    ]);

    // put some data in session before redirect to paytm url
    $request->session()->put('paymentFor', $paymentFor);
    $request->session()->put('arrData', $arrData);

    return $payment->receive();
  }

  public function notify(Request $request)
  {
    // get the information from session
    $paymentPurpose = $request->session()->get('paymentFor');

    if ($paymentPurpose == 'product purchase') {
      $productList = $request->session()->get('productCart');
    }

    $arrData = $request->session()->get('arrData');

    $transaction = PaytmWallet::with('receive');

    // this response is needed to check the transaction status
    $response = $transaction->response();

    if ($transaction->isSuccessful()) {
      // remove this session datas
      $request->session()->forget('paymentFor');
      $request->session()->forget('arrData');

      if ($paymentPurpose == 'product purchase') {
        $purchaseProcess = new PurchaseProcessController();

        // store product order information in database
        $orderInfo = $purchaseProcess->storeData($productList, $arrData);

        // then subtract each product quantity from respective product stock
        foreach ($productList as $key => $item) {
          $product = Product::query()->find($key);

          if ($product->product_type == 'physical') {
            $stock = $product->stock - intval($item['quantity']);

            $product->update(['stock' => $stock]);
          }
        }

        //add blance to admin revinue
        $earning = Earning::first();

        $earning->total_revenue = $earning->total_revenue + $orderInfo->grand_total;
        $earning->total_earning = $earning->total_earning + ($orderInfo->grand_total - $orderInfo->tax);
        $earning->save();

        $transactionStoreArr = [
          'transcation_id' => time(),
          'booking_id' => $orderInfo->id,
          'transcation_type' => 5,
          'user_id' => null,
          'vendor_id' => NULL,
          'payment_status' => 1,
          'payment_method' => $orderInfo->gateway_type,
          'grand_total' => $orderInfo->grand_total,
          'pre_balance' => NULL,
          'after_balance' => NULL,
          'gateway_type' => $orderInfo->gateway_type,
          'currency_symbol' => $orderInfo->currency_symbol,
          'currency_symbol_position' => $orderInfo->currency_symbol_position,
        ];
        storeTranscation($transactionStoreArr);

        // generate an invoice in pdf format
        $invoice = $purchaseProcess->generateInvoice($orderInfo, $productList);

        // then, update the invoice field info in database
        $orderInfo->update(['invoice' => $invoice]);

        // send a mail to the customer with the invoice
        $purchaseProcess->prepareMail($orderInfo, $transactionStoreArr['transcation_id']);

        // remove all session data
        $request->session()->forget('productCart');
        $request->session()->forget('discount');

        return redirect()->route('shop.purchase_product.complete');
      } else if ($paymentPurpose == 'equipment booking') {
        $bookingProcess = new BookingProcessController();

        // store equipment booking information in database
        $bookingInfo = $bookingProcess->storeData($arrData);

        // generate an invoice in pdf format
        $invoice = $bookingProcess->generateInvoice($bookingInfo);

        //calculate commission start

        $equipment = Equipment::findOrFail($arrData['equipmentId']);
        if (!empty($equipment)) {
          if ($equipment->vendor_id != NULL) {
            $vendor_id = $equipment->vendor_id;
          } else {
            $vendor_id = NULL;
          }
        } else {
          $vendor_id = NULL;
        }
        //calculate commission
        $percent = Commission::select('equipment_commission')->first();

        $commission = (($bookingInfo->total - $bookingInfo->discount) * $percent->equipment_commission) / 100;

        //get vendor
        $vendor = Vendor::where('id', $bookingInfo->vendor_id)->first();


        //add blance to admin revinue
        $earning = Earning::first();

        $earning->total_revenue = $earning->total_revenue + $bookingInfo->grand_total;
        if ($vendor) {
          $earning->total_earning = $earning->total_earning + $commission + $bookingInfo->tax;
        } else {
          $earning->total_earning = $earning->total_earning + ($bookingInfo->grand_total - $bookingInfo->security_deposit_amount);
        }
        $earning->save();


        //store Balance  to vendor
        if ($vendor) {
          $pre_balance = $vendor->amount;
          $vendor->amount = $vendor->amount + ($bookingInfo->grand_total - ($commission + $bookingInfo->tax + $bookingInfo->security_deposit_amount));
          $vendor->save();
          $after_balance = $vendor->amount;

          $received_amount = ($bookingInfo->grand_total - ($commission + $bookingInfo->tax + $bookingInfo->security_deposit_amount));

          // then, update the invoice field info in database
          $bookingInfo->update([
            'invoice' => $invoice,
            'comission' => $commission,
            'received_amount' => $received_amount,
          ]);
        } else {
          // then, update the invoice field info in database
          $bookingInfo->update([
            'invoice' => $invoice
          ]);
          $received_amount = $bookingInfo->grand_total - ($bookingInfo->security_deposit_amount + $bookingInfo->tax);
          $after_balance = NULL;
          $pre_balance = NULL;
        }
        //calculate commission end

        if (!is_null($vendor_id)) {
          $comission = $bookingInfo->comission;
        } else {
          $comission = $bookingInfo->grand_total - ($bookingInfo->security_deposit_amount + $bookingInfo->tax);
        }

        //store data to transcation table
        $transactionStoreArr = [
          'transcation_id' => time(),
          'booking_id' => $bookingInfo->id,
          'transcation_type' => 1,
          'user_id' => Auth::guard('web')->check() == true ? Auth::guard('web')->user()->id : null,
          'vendor_id' => $vendor_id,
          'payment_status' => 1,
          'payment_method' => $bookingInfo->payment_method,
          'shipping_charge' => $bookingInfo->shipping_cost,
          'commission' => $comission,
          'security_deposit' => $bookingInfo->security_deposit_amount,
          'tax' => $bookingInfo->tax,
          'grand_total' => $received_amount,
          'pre_balance' => $pre_balance,
          'after_balance' => $after_balance,
          'gateway_type' => $bookingInfo->gateway_type,
          'currency_symbol' => $bookingInfo->currency_symbol,
          'currency_symbol_position' => $bookingInfo->currency_symbol_position,
        ];

        storeTranscation($transactionStoreArr);

        // send a mail to the customer with the invoice
        $bookingProcess->prepareMail($bookingInfo, $transactionStoreArr['transcation_id']);

        // remove all session data
        $request->session()->forget('totalPrice');
        $request->session()->forget('equipmentDiscount');

        return redirect()->route('equipment.make_booking.complete');
      }
    } else {
      $request->session()->forget('paymentFor');
      $request->session()->forget('arrData');

      if ($paymentPurpose == 'product purchase') {
        // remove session data
        $request->session()->forget('productCart');
        $request->session()->forget('discount');

        return redirect()->route('shop.purchase_product.cancel');
      } else if ($paymentPurpose == 'equipment booking') {
        // remove session data
        $request->session()->forget('totalPrice');
        $request->session()->forget('equipmentDiscount');

        return redirect()->route('equipment.make_booking.cancel');
      }
    }
  }
}
