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
use App\Models\UserCard;

use App\Http\Helpers\BasicMailer;
use App\Models\AdditionalInvoice; // code by AG

class StaxController extends Controller
{
    //
    private $key;
    private $server_api_key;
    
    public function __construct()
    {
        $data = OnlineGateway::whereKeyword('stax')->first();
        $staxData = json_decode($data->information, true);
        
        $this->key = $staxData['key'];
        $this->server_api_key = $staxData['serverapikey'];
    }
    
    public function create_payment($payment_method_id, $payment_data){
        // CURLOPT_POSTFIELDS =>'{
        //  "payment_method_id": "'.$payment_method_id.'",
        //  "meta": {
        //     "tax":4,
        //     "poNumber": "1234",
        //     "shippingAmount": 2,
        //     "subtotal":20,
        //     "lineItems": [
        //         {
        //             "id": "optional-fm-catalog-item-id",
        //             "item":"Demo Item",
        //             "details":"this is a regular demo item",
        //             "quantity":20,
        //             "price": 1
        //         }
        //     ]
        //  },
        //  "total": 26.00,
        //  "pre_auth": 0
        // }',
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://apiprod.fattlabs.com/charge',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>json_encode($payment_data),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->server_api_key,
            'Cookie: __cf_bm=eWGFSwM2qbz0x_gQdN_PCd7dNPgSOmBwtCLiy_YmxvI-1697191992-0-ASNsYYOUcfWbqFN3md/yg8pEL5cepgTsT28Yufr9gXOm1c3tkqJvk945ev0o6G/eob6sJXZSy1QKEGwuDv8Ka40='
          ),
        ));
        
        $response = curl_exec($curl);
        
        Log::info('Stax Payment Log: {id}', ['id' => $response]);
        
        curl_close($curl);
        $response = json_decode($response, true);
        
        if(isset($response['success']) && $response['success'] == true){
            return $response['id'];
        }
        else{
            return false;
        }
    }
    
    public function get_customer( $email ){
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://apiprod.fattlabs.com/customer?email='.$email,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->server_api_key
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        
        $response = json_decode($response, true);
        if(empty($response['data'])){
           return false; 
        }
        else{
            return $response['data'][0]['id'];
        }

    }
    
    public function create_customer($customer_data){
        
        // CURLOPT_POSTFIELDS =>'{
        //   "firstname": "John",
        //   "lastname": "Smith",
        //   "company": "ABC INC",
        //   "email": "contact@example.com",
        //   "cc_emails": ["demo@abc.com"],
        //   "phone": "1234567898",
        //   "address_1": "123 Rite Way",
        //   "address_2": "Unit 12",
        //   "address_city": "Orlando",
        //   "address_state": "FL",
        //   "address_zip": "32801",
        //   "address_country": "USA",
        //   "reference": "BARTLE"
        // } ',
        
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://apiprod.fattlabs.com/customer',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => json_encode($customer_data),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->server_api_key,
            'Cookie: __cf_bm=eWGFSwM2qbz0x_gQdN_PCd7dNPgSOmBwtCLiy_YmxvI-1697191992-0-ASNsYYOUcfWbqFN3md/yg8pEL5cepgTsT28Yufr9gXOm1c3tkqJvk945ev0o6G/eob6sJXZSy1QKEGwuDv8Ka40='
          ),
        ));
        
        $response = curl_exec($curl);
        
        
        
        curl_close($curl);
        $response = json_decode($response, true);
        if(isset($response['id'])){
            return $response['id'];
        }
        else{
            return false;
        }
    }
    
    public function update_customer($id, $customer_data){
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://apiprod.fattlabs.com/customer/'.$id,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'PUT',
          CURLOPT_POSTFIELDS =>'{
          "firstname": "John",
          "lastname": "Smith",
          "company": "ABC INC",
          "email": "contact123@example.com",
          "cc_emails": ["demo@abc.com"],
          "phone": "1234567898",
          "address_1": "123 Rite Way",
          "address_2": "Unit 12",
          "address_city": "Orlando",
          "address_state": "FL",
          "address_zip": "32801",
          "address_country": "USA",
          "reference": "BARTLE"
        } ',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->server_api_key
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        echo $response;

    }
    
    public function get_customer_payment_methods($customer_id){
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://apiprod.fattlabs.com/customer/'.$customer_id.'/payment-method',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->server_api_key
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        $response = json_decode($response, true);
        
        if(!empty($response) && isset($response[0]['id'])){
            return $response;
        }
        else{
            return array();
        }

    }
    
    public function create_payment_method($customer_id, $method_data){
        
        // CURLOPT_POSTFIELDS =>'{
        //     "method": "card",
        //     "person_name": "Steven Smith",
        //     "card_number": "4111111111111111",
        //     "card_cvv": "123",
        //     "card_exp": "0427",
        //     "customer_id": "'.$customer_id.'"
        // }',
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://apiprod.fattlabs.com/payment-method/',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => json_encode($method_data),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->server_api_key
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        $response = json_decode($response, true);
        if(isset($response['id'])){
            return $response['id'];
        }
        else{
            return false;
        }
    }
    
    public function update_payment_method($id, $method_data){
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://apiprod.fattlabs.com/payment-method/'.$id,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'PUT',
          CURLOPT_POSTFIELDS =>'{
          "is_default": 1,
          "person_name": "Carl Junior Sr.",
          "card_type": "visa",
          "card_last_four": "1111",
          "card_exp": "032020",
          "bank_name": null,
          "bank_type": null,
          "bank_holder_type": null,
          "address_1": null,
          "address_2": null,
          "address_city": null,
          "address_state": null,
          "address_zip": "32944",
          "address_country": "USA"
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->server_api_key
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        echo $response;

    }
    
    public function delete_payment_method($id){
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://apiprod.fattlabs.com/payment-method/'.$id,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'DELETE',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->server_api_key
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
       // echo $response;
       
       return true;

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
            'paymentMethod' => 'Helly Pay',
            'gatewayType' => 'online',
            'paymentStatus' => 'completed',
            'branch_id' => $request['branch_id'] ?? null,
            'company_id' => $request['company_id'] ?? null,
            'orderStatus' => 'pending'
          );
    
          $title = 'Purchase Product';
          $notifyURL = route('shop.purchase_product.stax.notify');
          
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
            'paymentMethod' => 'Helly Pay',
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
          $notifyURL = route('equipment.make_booking.stax.notify');
          if($request['card_id'])
          {
              $card_detail = UserCard::find($request['card_id']);
          }else{
              $card_detail = "";
          }
          
          
        }
    
        // dd($card_detail->toArray());

        // create checkout data
        
        // put some data in session before redirect to razorpay url
        $request->session()->put('paymentFor', $paymentFor);
        $request->session()->put('arrData', $arrData);
        
        
        $public_key = $this->key;
        return view('frontend.payment.stax', compact('public_key', 'arrData', 'notifyURL','card_detail'));
      }
      
      
      public function notify(Request $request)
      {
        // get the information from session
        $paymentPurpose = $request->session()->get('paymentFor');
    
        if($paymentPurpose == 'product purchase') {
          $productList = $request->session()->get('productCart');
        }
    
        $arrData = $request->session()->get('arrData');
    
        $urlInfo = $request->all();
    
        // assume that the transaction was successful
        $success = true;
    
    
        /**
         * either razorpay_order_id or razorpay_subscription_id must be present.
         * the keys of $attributes array must be follow razorpay convention.
         */
    
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
    
            return redirect()->route('vendor.equipment_booking.bookings');
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
      
      
      // swap charge process start  // same method is used for relocation charge
      public function swap_charge_payment($request, $swap_charges, $booking, $additional_invoice_id)
      {
         $currencyInfo = $this->getCurrencyInfo();
    
        // changing the currency before redirect to Stripe
        if ($currencyInfo->base_currency_text !== 'USD') {
          $rate = floatval($currencyInfo->base_currency_rate);
          $convertedTotal = round(($swap_charges / $rate), 2);
        }
    
        $arrData = array(
            'grandTotal' => $swap_charges,
            'contactNumber' => $booking->contact_number,
            'email'=> $booking->email,
            'additional_invoice_id' => $additional_invoice_id,
            'vendor_id' => $booking->vendor_id,
            'booking_id' => $booking->id
          );
    
          $title = 'Equipment Swap';
          $notifyURL = route('equipment.swap_charge.stax.notify');
        
        $request->session()->put('arrData', $arrData);
    
        $public_key = $this->key;
        return view('frontend.payment.stax', compact('public_key', 'arrData', 'notifyURL'));
      }
      
      // swap charge process // same method is used for relocation charge
      public function swap_charge_process(Request $request)
      {
        // get the information from session
    
        $arrData = $request->session()->get('arrData');
    
        $urlInfo = $request->all();
    
        // assume that the transaction was successful
        $success = true;
    
    
        if ($success === true) {
          // remove this session datas
          
          $request->session()->forget('arrData');
          
          
          
          //calculate commission start
    
            if (isset($arrData['vendor_id']) && $arrData['vendor_id'] != '') {
              
                $vendor_id = $arrData['vendor_id'];
              
            } else {
                $vendor_id = NULL;
            }
    
         
            //calculate commission
            $percent = Commission::select('equipment_commission')->first();
    
    
            $commission = ag_calculate_commission($arrData['grandTotal']); //(($arrData['grandTotal']) * $percent->equipment_commission) / 100;
    
            //get vendor
            $vendor = Vendor::where('id', $vendor_id)->first();
    
    
            //add blance to admin revinue
            $earning = Earning::first();
    
            $earning->total_revenue = $earning->total_revenue + $arrData['grandTotal'];
            if ($vendor) {
              $earning->total_earning = $earning->total_earning + $commission;
            } else {
              $earning->total_earning = $earning->total_earning;
            }
            $earning->save();
    
    
            //store Balance  to vendor
            if ($vendor) {
              $pre_balance = $vendor->amount;
              $vendor->amount = $vendor->amount + ($arrData['grandTotal'] - ($commission));
              $vendor->save();
              $after_balance = $vendor->amount;
    
              $received_amount = ($arrData['grandTotal'] - ($commission));
    
            } else {
              
              $received_amount = $arrData['grandTotal'];
              $after_balance = NULL;
              $pre_balance = NULL;
            }
            //calculate commission end
    
            if (!is_null($vendor_id)) {
              $comission = $commission;
            } else {
              $comission = $arrData['grandTotal'];
            }
            
            
    
            //store data to transcation table
            $transactionStoreArr = [
              'transcation_id' => time(),
              'booking_id' => $arrData['booking_id'],
              'transcation_type' => 1,
              'user_id' => Auth::guard('web')->check() == true ? Auth::guard('web')->user()->id : null,
              'vendor_id' => $vendor_id??'',
              'payment_status' => 1,
              'payment_method' => "Helly Pay",
              'shipping_charge' => 0,
              'commission' => $comission,
              'security_deposit' => 0,
              'tax' => 0,
              'grand_total' => $received_amount,
              'pre_balance' => $pre_balance,
              'after_balance' => $after_balance,
              'gateway_type' => 'online',
              'currency_symbol' => '$',
              'currency_symbol_position' => 'left',
            ];
    
    
            storeTranscation($transactionStoreArr);
            
            
            $add_invoice = AdditionalInvoice::find($arrData['additional_invoice_id']);
            if($add_invoice){
                $add_invoice->status = 'paid';
                $add_invoice->save();
            }
    
            // send a mail to the customer with the invoice
            //$bookingProcess->prepareMail($bookingInfo, $transactionStoreArr['transcation_id']);
    
    
            // remove all session data
             $request->session()->forget('totalPrice');
             $request->session()->forget('equipmentDiscount');
    
            return redirect()->route('user.equipment_bookings')->with('success','Payment Completed.');
          
        } else {
          
          $request->session()->forget('arrData');
          $request->session()->forget('totalPrice');
            $request->session()->forget('equipmentDiscount');
            
            return redirect()->route('user.equipment_bookings')->with('success','Payment Pending.');
            
        }
      }
      // swap charge process end
}
