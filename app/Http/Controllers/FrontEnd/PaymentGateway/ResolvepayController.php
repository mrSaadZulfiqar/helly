<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Instrument\BookingProcessController;
use App\Http\Controllers\FrontEnd\Shop\PurchaseProcessController;
use App\Models\Commission;
use App\Models\Earning;
use App\Models\Instrument\Equipment;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Shop\Product;
use App\Models\Transcation;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use App\Http\Helpers\BasicMailer;

use App\Models\AdditionalInvoice; // code by AG

class ResolvepayController extends Controller
{
    //
    private $key;
    private $server_api_key;
    private $is_sandbox;
    
    public function __construct()
    {
        $data = OnlineGateway::whereKeyword('resolve')->first();
        $resolveData = json_decode($data->information, true);
        
        $this->key = $resolveData['key'];
        $this->server_api_key = $resolveData['serverapikey'];
        $this->is_sandbox = $resolveData['sandbox_status'];
    }
    
    public function index(Request $request, $paymentFor)
    {
        // card validation start
    
        if(auth()->user()->owner_id || auth()->user()->account_type == 'corperate_account'){
            $rules = [
                'branch_id' => 'required',
              'job_number' => 'required',
              'po_number' => 'required'
            ];
            
             $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
              return redirect()->back()->withErrors($validator)->withInput();
            }
        }
     
        // card validation end
        
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
        
        // changing the currency before redirect to Stripe
        if ($currencyInfo->base_currency_text !== 'USD') {
          $rate = floatval($currencyInfo->base_currency_rate);
          $convertedTotal = round(($calculatedData['grandTotal'] / $rate), 2);
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
            'paymentMethod' => 'Resolve Pay',
            'gatewayType' => 'online',
            'paymentStatus' => 'completed',
            'branch_id' => $request['branch_id'] ?? null,
            'company_id' => $request['company_id'] ?? null,
            'orderStatus' => 'pending'
          );
        
          $title = 'Purchase Product';
          $notifyURL = route('shop.purchase_product.resolve.notify');
          
        } else if ($paymentFor == 'equipment booking') {
          // get start & end date
          $dates = $bookingProcess->getDates($request['dates']);
        
          // get location name
          $location_name = ''; //$bookingProcess->getLocation($request['location']);
        
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
            'paymentMethod' => 'Resolve Pay',
            'gatewayType' => 'online',
            'paymentStatus' => 'completed',
            'shippingStatus' => !$request->filled('shipping_method') ? null : 'pending',
            
            // code by AG start
            'delivery_location' => $request['delivery_location'],
            'lat' => $request['lat'],
            'lng' => $request['long'],
            'branch_id' => $request['branch_id'] ?? null,
            'company_id' => $request['company_id'] ?? null,
            'user_id' => $request['user_id'] ?? null,
            'job_number' => $request['job_number'] ?? null,
            'po_number' => $request['po_number'] ?? null,
            'additional_charges_items_json' => $calculatedData['additional_charges_items_json'],
            'additional_booking_parameters_json' => $calculatedData['additional_booking_parameters_json']
            // code by AG end
          );
        
          $title = 'Equipment Booking';
          $notifyURL = route('equipment.make_booking.resolve.notify');
          
          
        }
        
        // create checkout data
        
        // put some data in session before redirect to razorpay url
        $request->session()->put('paymentFor', $paymentFor);
        $request->session()->put('arrData', $arrData);
        
        
        $public_key = $this->key;
        $is_sandbox = $this->is_sandbox;
        
        return view('frontend.payment.resolve', compact('public_key', 'arrData', 'notifyURL', 'is_sandbox'));
    }
    
    
    public function notify(Request $request)
    {
        // get the information from session
        $paymentPurpose = $request->session()->get('paymentFor');
        
        if ($paymentPurpose == 'product purchase') {
          $productList = $request->session()->get('productCart');
        }
        
        $arrData = $request->session()->get('arrData');
        
        $urlInfo = $request->all();
        
        // assume that the transaction was successful
        if(isset($urlInfo['charge_id'])){
            $success = true;
        }
        else{
            $success = false;
        }
       
        if ($success === true) {
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
              'transcation_id' => $urlInfo['charge_id'],
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
            
            $capture_charge__ = $this->capture_charge( $urlInfo['charge_id'] );
            
            // remove all session data
            $request->session()->forget('productCart');
            $request->session()->forget('discount');
        //echo 'helo';
            return redirect()->route('shop.purchase_product.complete');
          } else if ($paymentPurpose == 'equipment booking') {
              
            $bookingProcess = new BookingProcessController();
        
            // store equipment booking information in database
            $bookingInfo = $bookingProcess->storeData($arrData);
            
            $vendor = Vendor::find($bookingInfo['vendor_id']);
            $mailData['subject'] = 'New Booking Received';
            $mailData['body'] = "New Booking Received";
            $mailData['recipient'] = $vendor->email;
            BasicMailer::sendMail($mailData);
            
            $admin_ = Admin::find(1);
            $mailData = array();
            $mailData['subject'] = 'New Booking Received';
            $mailData['body'] = "New Booking Received";
            $mailData['recipient'] = $admin_->email;
            BasicMailer::sendMail($mailData);
        
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
        
        
            $commission = ag_calculate_commission(($bookingInfo->total - $bookingInfo->discount)); //(($bookingInfo->total - $bookingInfo->discount) * $percent->equipment_commission) / 100;
        
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
              'transcation_id' => $urlInfo['charge_id'],
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
            
            $capture_charge__ = $this->capture_charge( $urlInfo['charge_id'] );
        
            // remove all session data
            $request->session()->forget('totalPrice');
            $request->session()->forget('equipmentDiscount');
        
            return redirect()->route('equipment.make_booking.complete');
          }
        } else {
          $request->session()->forget('paymentFor');
          $request->session()->forget('arrData');
          $request->session()->forget('razorpayOrderId');
        
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
    
    
    public function capture_charge( $charge_id ){
        if($this->is_sandbox == 1){
            $charge_url_ = 'https://'.$this->key.':'.$this->server_api_key.'@app-sandbox.resolvepay.com/api/charges/'.$charge_id.'/capture';
        }
        else{
            $charge_url_ = 'https://'.$this->key.':'.$this->server_api_key.'@app.resolvepay.com/api/charges/'.$charge_id.'/capture';
        }
        
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $charge_url_,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return $response;
    }
}
