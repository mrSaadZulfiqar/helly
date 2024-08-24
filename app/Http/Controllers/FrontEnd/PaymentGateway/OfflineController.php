<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Instrument\BookingProcessController;
use App\Http\Controllers\FrontEnd\Shop\PurchaseProcessController;
use App\Http\Helpers\UploadFile;
use App\Models\Instrument\Equipment;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\Shop\Product;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OfflineController extends Controller
{
  public function index(Request $request, $paymentFor)
  {
    $gatewayId = $request->gateway;
    // dd($paymentFor);

    $offlineGateway = null;

    if($gatewayId != "offline_payment")
    {
        $offlineGateway = OfflineGateway::query()->findOrFail($gatewayId);
    
        // validation start
        if ($offlineGateway->has_attachment == 1) {
          $rules = [
            'attachment' => [
              'required',
              new ImageMimeTypeRule()
            ]
          ];
    
          $message = [
            'attachment.required' => 'Please attach your payment receipt.'
          ];
    
          $validator = Validator::make($request->only('attachment'), $rules, $message);
    
          Session::flash('gatewayId', $offlineGateway->id);
    
          if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
          }
        }
    }
    // validation end

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

      $directory = public_path('assets/file/attachments/product/');
    } else if ($paymentFor == 'equipment booking') {
      // check whether the equipment lowest price exist or not in session
      if (!$request->session()->has('totalPrice')) {
        // dd("OK");
        Session::flash('error', 'Something went wrong!');
        return redirect()->route('all_equipment');
      }

      $bookingProcess = new BookingProcessController();

      // do calculation
      $calculatedData = $bookingProcess->calculation($request);

      $directory = public_path('assets/file/attachments/equipment/');
    }

    // store attachment in local storage
    if ($request->hasFile('attachment')) {
      $attachmentName = UploadFile::store($directory, $request->file('attachment'));
    }

    $currencyInfo = $this->getCurrencyInfo();

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
        'paymentMethod' => $offlineGateway->name,
        'gatewayType' => 'offline',
        'paymentStatus' => 'pending',
        'orderStatus' => 'pending',
        'attachment' => $request->exists('attachment') ? $attachmentName : null
      );

      // store product order information in database
      $purchaseProcess->storeData($productList, $arrData);

      // then subtract each product quantity from respective product stock
      foreach ($productList as $key => $item) {
        $product = Product::query()->find($key);

        if ($product->product_type == 'physical') {
          $stock = $product->stock - intval($item['quantity']);

          $product->update(['stock' => $stock]);
        }
      }

      // remove all session data
      $request->session()->forget('productCart');
      $request->session()->forget('discount');

      return redirect()->route('shop.purchase_product.complete', ['type' => 'offline_purchase']);
    } else if ($paymentFor == 'equipment booking') {
      // get start & end date
      $dates = $bookingProcess->getDates($request['dates']);

      // get location name
      //$location_name = $bookingProcess->getLocation($request['location']); // commented by AG

      $arrData = array(
        'name' => $request['name'],
        'contactNumber' => $request['contact_number'],
        'email' => $request['email'],
        'equipmentId' => $request['equipment_id'],
        'startDate' => $dates['startDate'],
        'endDate' => $dates['endDate'],
        'shippingMethod' => $request->filled('shipping_method') ? $request['shipping_method'] : null,
        'location' => $location_name??'',
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
        'paymentMethod' => $offlineGateway->name ?? "offline",
        'gatewayType' => 'offline',
        'paymentStatus' => 'pending',
        'shippingStatus' => !$request->filled('shipping_method') ? null : 'pending',
        'attachment' => $request->exists('attachment') ? $attachmentName : null
      );

      // store equipment booking information in database
      $bookingProcess->storeData($arrData);

      // remove all session data
      $request->session()->forget('totalPrice');
      $request->session()->forget('equipmentDiscount');

      return redirect()->route('vendor.equipment_booking.bookings', ['language' => 'en']);
    }
  }
}
