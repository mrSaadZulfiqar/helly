<?php

namespace App\Http\Controllers\FrontEnd\Instrument;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
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
use App\Http\Helpers\BasicMailer;
use App\Http\Requests\Instrument\BookingProcessRequest;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Commission;
use App\Models\Instrument\Equipment;
use App\Models\Instrument\EquipmentBooking;
use App\Models\Instrument\Location;
use App\Models\Transcation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Session;
use App\Models\EquipmentFieldsValue; // code by AG
use App\Notifications\BasicNotify; // code by AG
use App\Models\Admin;

class BookingProcessController extends Controller
{
  public function index(BookingProcessRequest $request)
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
    } else if ($request['gateway'] == 'paytm') {
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
  
  public function diffOfDates($dates)
  {
    $arrOfDate = explode(' ', $dates);
    $bookingStartDate = $arrOfDate[0];
    $bookingEndDate = $arrOfDate[2];

    $date1 = date_create($bookingStartDate);
    $date2 = date_create($bookingEndDate);
    $diff = date_diff($date1, $date2);
    $numOfDays = $diff->days + 1;

    return $numOfDays;
  }

  public function calculation(Request $request)
  {
    if ($request->session()->has('totalPrice')) {
      $total = $request->session()->get('totalPrice');
    }

    if ($request->session()->has('equipmentDiscount')) {
      $discountVal = $request->session()->get('equipmentDiscount');
    }

    $discount = isset($discountVal) ? floatval($discountVal) : 0.00;
    $subtotal = $total - $discount;

    $taxData = Basic::select('equipment_tax_amount')->first();
    $taxAmount = floatval($taxData->equipment_tax_amount);
    $calculatedTax = $subtotal * ($taxAmount / 100);

    $shippingCharge = 0.00;

    if ($request['shipping_method'] == 'two way delivery') {
    //   $locationId = $request['location']; // commented by AG

    //   $location = Location::query()->find($locationId); // commented by AG
    //   $shippingCharge = floatval($location->charge);  // commented by AG
    }

    //get security deposit amount 
    $equipment = Equipment::where('id', $request['equipment_id'])->first();

    $grandTotal = $subtotal + $calculatedTax + $shippingCharge + $equipment->security_deposit_amount;
    
    
     // case: custom by AG to calculate price for multiple charges category
      // code by AG start
      
        $dates__ = $request['dates'];
        $totalDays = $this->diffOfDates($dates__);
        
        $additional_charges_item_array = array(); // array item format: array("name":"additional charge name","amount":100)
        $additional_booking_parameters_array = array(); // array item format: array("name":"parameter title","value":"parameter value")
        $misc = new MiscellaneousController();
    
        $language = $misc->getLanguage();
        
        $details__ = Equipment::query()->join('equipment_contents', 'equipments.id', '=', 'equipment_contents.equipment_id')
          ->join('equipment_categories', 'equipment_categories.id', '=', 'equipment_contents.equipment_category_id')
          ->where('equipment_contents.language_id', '=', $language->id)
          ->where('equipments.id', $equipment->id)
          ->select('equipments.id', 'equipments.vendor_id', 'equipments.slider_images', 'equipment_contents.title', 'equipment_categories.name as categoryName', 'equipment_categories.slug as categorySlug', 'equipment_contents.description', 'equipment_contents.features', 'equipments.lowest_price', 'equipments.per_day_price', 'equipments.per_week_price', 'equipments.per_month_price', 'equipments.min_booking_days', 'equipments.max_booking_days', 'equipments.security_deposit_amount', 'equipment_contents.meta_keywords', 'equipment_contents.meta_description', 'equipment_contents.equipment_category_id')
          ->firstOrFail();
        
        if(is_equipment_multiple_charges($details__->equipment_category_id)){
            $equipment_fields = EquipmentFieldsValue::where('equipment_id', $equipment->id)->first();

            if($equipment_fields){
                
                $multiple_charges_settings = json_decode($equipment_fields->multiple_charges_settings, true);
            }
            else{
                $multiple_charges_settings = array();
            }
            $rental_days = $multiple_charges_settings['rental_days']??1;
            $base_price = $multiple_charges_settings['base_price']??0;
            $base_price = amount_with_commission($base_price);
            $additional_daily_cost = $multiple_charges_settings['additional_daily_cost']??0;
            $additional_daily_cost = amount_with_commission($additional_daily_cost);
            
            $total_new_cost_ = 0;
            if($rental_days > 0 && $base_price > 0){
                
                $extra_days = $totalDays - $rental_days;
                
                $extra_days_cost = round(($additional_daily_cost * $extra_days), 2);
                
                $total_new_cost_ = round(($total_new_cost_ + $base_price), 2);
                
                $total_new_cost_ = round(($total_new_cost_ + $extra_days_cost), 2);
                
                //$minimumPrice = $total_new_cost_;
            }
            
            if( isset($multiple_charges_settings['environmental_charges']) && $multiple_charges_settings['environmental_charges'] > 0){
                
                $environmental_charges_ = $base_price * ($multiple_charges_settings['environmental_charges'] / 100);
                
                $total_new_cost_ = round(($total_new_cost_ + $environmental_charges_ ), 2);
                
                $additional_charges_item_array[] = array(
                    "name" => "Environmental Charges",
                    "amount" => $environmental_charges_, //amount_with_commission($multiple_charges_settings['environmental_charges'])
                    );
            }
            
            if(isset($request['live_load']) && $request['live_load'] == 'Yes' && isset($multiple_charges_settings['live_load_cost']) && $multiple_charges_settings['live_load_cost'] > 0){
                $total_new_cost_ = round(($total_new_cost_ + $multiple_charges_settings['live_load_cost'] ), 2);
                
                $additional_booking_parameters_array[] = array(
                    "name" => "Live Load",
                    "value" => 'Yes'
                    );
                
                $additional_charges_item_array[] = array(
                    "name" => "Live Load Cost",
                    "amount" => amount_with_commission($multiple_charges_settings['live_load_cost'])
                    );
            }
            
            
            if(isset($request['placement_instructions'])){
                $additional_booking_parameters_array[] = array(
                    "name" => "Placement Instructions",
                    "value" => $request['placement_instructions']
                    );
            }
            
            if(isset($request['customer_punchoutlist'])){
                $additional_booking_parameters_array[] = array(
                    "name" => "Type Of waste",
                    "value" => $request['customer_punchoutlist']
                    );
            }
            
            if(isset($request['is_emergency']) && $request['is_emergency'] == 'Yes' && isset($multiple_charges_settings['emergency_cost']) && $multiple_charges_settings['emergency_cost'] > 0){
                $total_new_cost_ = round(($total_new_cost_ + $multiple_charges_settings['emergency_cost'] ), 2);
                
                $additional_booking_parameters_array[] = array(
                    "name" => "Emergency",
                    "value" => 'Yes'
                    );
                
                $additional_charges_item_array[] = array(
                    "name" => "Expedited Services",
                    "amount" => amount_with_commission($multiple_charges_settings['emergency_cost'])
                    );
            }
            

        }
        else if(is_equipment_temporary_toilet_type($details__->equipment_category_id)){
            $equipment_fields = EquipmentFieldsValue::where('equipment_id', $equipment->id)->first();

            if($equipment_fields){
                
                $multiple_charges_settings = json_decode($equipment_fields->multiple_charges_settings, true);
            }
            else{
                $multiple_charges_settings = array();
            }
            
            $rental_days = 28;
            
            if($multiple_charges_settings['rental_days_included'] == 'Monthly'){
                $rental_days = 30;
            }
            
            $base_price = $multiple_charges_settings['base_price']??0;
            $base_price = amount_with_commission($base_price);
            $additional_service_cost = $multiple_charges_settings['additional_service_cost']??0;
            $additional_service_cost = amount_with_commission($additional_service_cost);
            
            $special_event_price = $multiple_charges_settings['special_event_price']??0;
            $special_event_price = amount_with_commission($special_event_price);
            
            $short_term_price = $multiple_charges_settings['short_term_price']??0;
            $short_term_price = amount_with_commission($short_term_price);
            
            $long_term_price = $multiple_charges_settings['long_term_price']??0;
            $long_term_price = amount_with_commission($long_term_price);
            
            $construction_event_price = $multiple_charges_settings['construction_event_price']??0;
            $construction_event_price = amount_with_commission($construction_event_price);
            
            $price_repeate = ceil($totalDays/$rental_days);
            
            $total_new_cost_ = 0;
            
            $total_new_cost_ = round(($total_new_cost_ + ( $base_price * $price_repeate) ), 2);
            
            if(isset($request['extra_services']) && $request['extra_services'] > 0){
                $extra_services_total = ( $additional_service_cost * $request['extra_services']);
                $total_new_cost_ = round(($total_new_cost_ + $extra_services_total ), 2);
                
                $additional_booking_parameters_array[] = array(
                    "name" => "Extra Services",
                    "value" => $request['extra_services']
                    );
                 $additional_charges_item_array[] = array(
                    "name" => "Extra Services",
                    "amount" => $extra_services_total
                    );
                
            }
            if(isset($request['placement_instructions'])){
                $additional_booking_parameters_array[] = array(
                    "name" => "Placement Instructions",
                    "value" => $request['placement_instructions']
                    );
            }
            
            if(isset($request['type_of_rental']) && $request['type_of_rental'] != ''){
                
                if($request['type_of_rental'] == 'Long Term'){
                    $total_new_cost_ = round(($total_new_cost_ + $long_term_price), 2);
                    
                    $additional_booking_parameters_array[] = array(
                    "name" => "Type of rental",
                    "value" => $request['type_of_rental']
                    );
                    
                    $additional_charges_item_array[] = array(
                    "name" => "Long Term",
                    "amount" => $long_term_price
                    );
                    
                }
                
                if($request['type_of_rental'] == 'Short Term'){
                    $total_new_cost_ = round(($total_new_cost_ + $short_term_price), 2);
                    
                    $additional_booking_parameters_array[] = array(
                    "name" => "Type of rental",
                    "value" => $request['type_of_rental']
                    );
                    
                    $additional_charges_item_array[] = array(
                    "name" => "Short Term",
                    "amount" => $short_term_price
                    );
                    
                }
                
                if($request['type_of_rental'] == 'Special Event'){
                    $total_new_cost_ = round(($total_new_cost_ + $special_event_price), 2);
                    
                    $additional_booking_parameters_array[] = array(
                    "name" => "Type of rental",
                    "value" => $request['type_of_rental']
                    );
                    
                    $additional_charges_item_array[] = array(
                    "name" => "Special Event",
                    "amount" => $special_event_price
                    );
                    
                }
                
                if($request['type_of_rental'] == 'Construction Event'){
                    $total_new_cost_ = round(($total_new_cost_ + $construction_event_price), 2);
                    
                    $additional_booking_parameters_array[] = array(
                    "name" => "Type of rental",
                    "value" => $request['type_of_rental']
                    );
                    
                    $additional_charges_item_array[] = array(
                    "name" => "Construction Event",
                    "amount" => $construction_event_price
                    );
                    
                }
                
            }
            
           // $minimumPrice = $total_new_cost_;
        }
        
        else if(is_equipment_storage_container_type($details__->equipment_category_id)){
            $equipment_fields = EquipmentFieldsValue::where('equipment_id', $equipment->id)->first();

            if($equipment_fields){
                
                $multiple_charges_settings = json_decode($equipment_fields->multiple_charges_settings, true);
            }
            else{
                $multiple_charges_settings = array();
            }
            $rental_days = $multiple_charges_settings['rental_days']??1;
            $base_price = $multiple_charges_settings['base_price']??0;
            $base_price = amount_with_commission($base_price);
            
            if($rental_days > 0 && $base_price > 0){
                $total_new_cost_ = 0;
                
                $price_repeate = ceil($totalDays/$rental_days);
                
                $total_new_cost_ = round(($total_new_cost_ + ( $base_price * $price_repeate) ), 2);
                
                
               // $minimumPrice = $total_new_cost_;
            }
            

        }
        $shippingCharge = 0;
        $distance__ = '';
      $vendor_location = Location::where('vendor_id',$equipment->vendor_id)->where('equipment_category_id', $details__->equipment_category_id)->first();
        if(!empty($vendor_location)){
            $distance__ = $this->getDistance($request['lat'], $request['long'], $vendor_location->latitude, $vendor_location->longitude, 'mile');
            $distance__ = round($distance__,2);
            
            if($vendor_location->rate_type == 'flat_rate'){
                $shippingCharge = $vendor_location->charge;
            }
            if($vendor_location->rate_type == 'rate_by_distance'){
                if($request['lat'] != '' && $request['long'] != ''
                && $vendor_location->latitude != '' && $vendor_location->longitude != ''){
                    
                    $shippingCharge = round(($distance__ * $vendor_location->distance_rate), 2);
                }
                
            }
            
            $grandTotal = $grandTotal+ $shippingCharge;
        }
        // code by AG end
    

    $calculatedData = array(
      'total' => $total,
      'discount' => $discount,
      'subtotal' => $subtotal,
      'shippingCharge' => $request['shipping_method'] == 'two way delivery' ? $shippingCharge : null,
      'tax' => $calculatedTax,
      'grandTotal' => $grandTotal,
      'security_deposit_amount' => $equipment->security_deposit_amount,
      'additional_charges_items_json' => json_encode($additional_charges_item_array), // code by AG
      'additional_booking_parameters_json' => json_encode($additional_booking_parameters_array) // code by AG
    );
    

    return $calculatedData;
  }
  
  public function getDistance($lat1, $lng1, $lat2, $lng2, $unit = 'km')
    {
        // radius of earth; @note: the earth is not perfectly spherical, but this is considered the 'mean radius'
        if ($unit == 'km') $radius = 6371.009; // in kilometers
        elseif ($unit == 'mile') $radius = 3958.761; // in miles

        // convert degrees to radians
        $lat1 = deg2rad((float) $lat1);
        $lng1 = deg2rad((float) $lng1);
        $lat2 = deg2rad((float) $lat2);
        $lng2 = deg2rad((float) $lng2);

        // great circle distance formula
        return $radius * acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($lng1 - $lng2));
    }

  public function getDates($dateString)
  {
    $arrOfDate = explode(' ', $dateString);
    $date_1 = $arrOfDate[0];
    $date_2 = $arrOfDate[2];

    $dates = array(
      'startDate' => date_create($date_1),
      'endDate' => date_create($date_2)
    );

    return $dates;
  }

  public function getLocation($locationId)
  {
    $location = Location::query()->find($locationId);
    $locationName = $location->name;

    return $locationName;
  }

  public function storeData($arrData)
  {
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
    //generate 8 digit booking number
    $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    $booking_number = substr(str_shuffle(str_repeat($pool, 5)), 0, 8);

    $commission = Commission::first();
    $user_id= '';
    if(empty($arrData['user_id']) || $arrData['user_id'] == null)
    {
        if(Auth::guard('web')->check() == true)
        {
            $user_id = Auth::guard('web')->user()->id;
        }
        else{
            $user_id = null;
        }
    }else{
        $user_id = $arrData['user_id'];
    }
    
    // dd($arrData);
    
    $bookingInfo = EquipmentBooking::query()->create([
      'user_id' => $user_id,
      'booking_number' => $booking_number,
      'name' => $arrData['name'],
      'contact_number' => $arrData['contactNumber'] ?? "",
      'email' => $arrData['email'],
      'vendor_id' => $vendor_id,
      'equipment_id' => $arrData['equipmentId'],
      'start_date' => $arrData['startDate'],
      'end_date' => $arrData['endDate'],
      'shipping_method' => $arrData['shippingMethod'],
      'location' => !empty($arrData['delivery_location']) ? $arrData['delivery_location'] : $arrData['location'],
      'total' => array_key_exists('total', $arrData) ? $arrData['total'] : null,
      'discount' => array_key_exists('discount', $arrData) ? $arrData['discount'] : null,
      'shipping_cost' => array_key_exists('shippingCost', $arrData) ? $arrData['shippingCost'] : null,
      'tax' => array_key_exists('tax', $arrData) ? $arrData['tax'] : null,
      'grand_total' => array_key_exists('grandTotal', $arrData) ? $arrData['grandTotal'] : null,
      'security_deposit_amount' => array_key_exists('security_deposit_amount', $arrData) ? $arrData['security_deposit_amount'] : null,

      'currency_symbol' => array_key_exists('currencySymbol', $arrData) ? $arrData['currencySymbol'] : null,
      'currency_symbol_position' => array_key_exists('currencySymbolPosition', $arrData) ? $arrData['currencySymbolPosition'] : null,
      'currency_text' => array_key_exists('currencyText', $arrData) ? $arrData['currencyText'] : null,
      'currency_text_position' => array_key_exists('currencyTextPosition', $arrData) ? $arrData['currencyTextPosition'] : null,
      'booking_type' => array_key_exists('bookingType', $arrData) ? $arrData['bookingType'] : null,
      'price_message' => array_key_exists('priceMessage', $arrData) ? $arrData['priceMessage'] : null,
      'payment_method' => array_key_exists('paymentMethod', $arrData) ? $arrData['paymentMethod'] : null,
      'gateway_type' => array_key_exists('gatewayType', $arrData) ? $arrData['gatewayType'] : null,
      'payment_status' => $arrData['paymentStatus'],
      'shipping_status' => $arrData['shippingStatus'],
      'attachment' => array_key_exists('attachment', $arrData) ? $arrData['attachment'] : null,
      'commission_percentage' => $commission->equipment_commission,
      
      // code by AG start
      'delivery_location' => !empty($arrData['delivery_location']) ? $arrData['delivery_location'] : $arrData['location'],
      'lat' => !empty($arrData['lat']) ? $arrData['lat'] : "",
      'lng' => !empty($arrData['lng']) ? $arrData['lng'] : "",
      // code by AG end
      
      'branch_id' => $arrData['branch_id'] ?? null,
      'company_id' => $arrData['company_id'] ?? null,
      'po_number' => $arrData['po_number'] ?? null,
      'job_number' => $arrData['job_number'] ?? null,
      'additional_charges_line_items' => $arrData['additional_charges_items_json'] ?? null,
      'additional_booking_parameters' => $arrData['additional_booking_parameters_json'] ?? null,
    ]);
    
    

    return $bookingInfo;
  }

  public function generateInvoice($bookingInfo)
  {
    $fileName = $bookingInfo->booking_number . '.pdf';

    $data['bookingInfo'] = $bookingInfo;

    $directory = config('dompdf.public_path') . 'equipment/';
    @mkdir($directory, 0775, true);

    $fileLocated = $directory . $fileName;

    $data['taxData'] = Basic::select('equipment_tax_amount')->first();

    Pdf::loadView('frontend.equipment.invoice', $data)->save($fileLocated);

    return $fileName;
  }

  public function prepareMail($bookingInfo, $transaction_id)
  {
    // get the mail template info from db
    $mailTemplate = MailTemplate::query()->where('mail_type', '=', 'equipment_booking')->first();
    $mailData['subject'] = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    // get the website title info from db
    $info = Basic::select('website_title')->first();

    // preparing dynamic data
    $customerName = $bookingInfo->name;
    $bookingNumber = $bookingInfo->booking_id;
    $bookingDate = date_format($bookingInfo->created_at, 'M d, Y');

    $equipmentId = $bookingInfo->equipment_id;
    $equipment = Equipment::query()->find($equipmentId);

    $vendor = $equipment->vendor()->first();

    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();

    $equipmentInfo = $equipment->content()->where('language_id', $language->id)->first();
    $equipmentTitle = $equipmentInfo->title;

    $startDate = date_format($bookingInfo->start_date, 'M d, Y');
    $endDate = date_format($bookingInfo->end_date, 'M d, Y');
    $websiteTitle = $info->website_title;

    if (Auth::guard('web')->check() == true) {
      $bookingLink = '<p>Booking Details: <a href=' . url("user/equipment-booking/" . $bookingInfo->id . "/details") . '>Click Here</a></p>';
    } else {
      $bookingLink = '';
    }
    if ($vendor != NULL) {
      $vendor_details_link = '<p>Vendor Details: <a href=' . url("vendors/" . $vendor->username) . '>Click Here</a></p>';
      $admin_ = Admin::find(1);
      $vendor->notify(new BasicNotify('<a href="/vendor/equipment-booking/'. $bookingInfo->id .'/details?language=en">New Booking Received</a>'));
        $admin_->notify(new BasicNotify('<a href="/admin/equipment-booking/'. $bookingInfo->id .'/details?language=en">New Booking Received</a>'));
    } else {
      $vendor_details_link = '';
    }

    // replacing with actual data
    $mailBody = str_replace('{transaction_id}', $transaction_id, $mailBody);
    $mailBody = str_replace('{customer_name}', $customerName, $mailBody);
    $mailBody = str_replace('{booking_number}', $bookingNumber, $mailBody);
    $mailBody = str_replace('{booking_date}', $bookingDate, $mailBody);
    $mailBody = str_replace('{equipment_name}', $equipmentTitle, $mailBody);
    $mailBody = str_replace('{start_date}', $startDate, $mailBody);
    $mailBody = str_replace('{end_date}', $endDate, $mailBody);
    $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);
    $mailBody = str_replace('{booking_link}', $bookingLink, $mailBody);
    $mailBody = str_replace('{vendor_details_link}', $vendor_details_link, $mailBody);

    $mailData['body'] = $mailBody;

    $mailData['recipient'] = $bookingInfo->email;

    $mailData['invoice'] = public_path('assets/file/invoices/equipment/') . $bookingInfo->invoice;
    
    
    

    BasicMailer::sendMail($mailData);

    return;
  }

  public function complete($type = null)
  {
    $misc = new MiscellaneousController();

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    $queryResult['bookingType'] = $type;

    if (session()->has('shippingMethod')) {
      session()->forget('shippingMethod');
    }

    return view('frontend.payment.booking-success', $queryResult);
  }

  public function cancel(Request $request)
  {
    Session::flash('error', 'Sorry, an error has occured!');

    return redirect()->route('all_equipment');
  }
}
