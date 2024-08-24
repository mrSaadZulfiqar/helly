<?php

namespace App\Http\Controllers\FrontEnd\Instrument;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\BasicSettings\Basic;
use App\Models\Instrument\Coupon;
use App\Models\Instrument\Equipment;
use App\Models\Instrument\EquipmentBooking;
use App\Models\Instrument\EquipmentCategory;
use App\Models\Instrument\EquipmentContent;
use App\Models\Instrument\EquipmentLocation;
use App\Models\Instrument\EquipmentReview;
use App\Models\Instrument\Location;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Vendor;
use App\Models\VendorSetting; // code by AG
use App\Models\EquipmentQuote; // code by AG
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Http\Helpers\BasicMailer;
use App\Models\BranchUser;

use App\Models\EquipmentFieldsValue; // code by AG
use App\Models\Admin; // code by AG
use App\Notifications\BasicNotify; // code by AG
use Illuminate\Support\Collection;
use App\Models\UserCard;


class EquipmentController extends Controller
{
  public function index(Request $request)
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_equipment', 'meta_description_equipment')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    $keyword = $sort = $category = $min = $max = $dates = $pricingType = $location = null;
    $bookedEquipmentIds = [];

    // code by AG start
    $lat = $long_ = $radius = $unit = $in_service_radius_vendors = null;
    $unit = 'km';
    if ($request->filled('lat')) {
        $lat = $request['lat'];
    }
    if ($request->filled('long')) {
        $long_ = $request['long'];
    }
    if ($request->filled('radius')) {
        $radius = $request['radius'];
    }
    if ($request->filled('unit')) {
        $unit = $request['unit'];
    }

    
    if($lat != '' && $long_ != '' && $radius != ''){
        $in_service_radius_vendors = array();
        // $in_service_radius_vendors = $this->get_in_service_radius_equipments_ids($lat, $long_, $radius, $unit);
        
        if ($request->filled('category')) {
          $category = $request['category'];
        }
        $in_service_radius_vendors_response = $this->get_in_service_radius_equipments_ids_by_vendor_locations($lat, $long_, $radius, $unit, $category);
        $in_service_radius_vendors = $in_service_radius_vendors_response['equipments_ids'];

        if(empty($in_service_radius_vendors)){
            $in_service_radius_vendors = array('no_vendor');
        }
    }

    // code by AG end

    if ($request->filled('keyword')) {
      $keyword = $request['keyword'];
    }
    if ($request->filled('sort')) {
      $sort = $request['sort'];
    }
    if ($request->filled('category')) {
      $category = $request['category'];
    }
    if ($request->filled('min') && $request->filled('max')) {
      $min = $request['min'];
      $max = $request['max'];
    }
    if ($request->filled('dates')) {
      $dates = $request['dates'];
    }
    if ($request->filled('pricing')) {
      $pricingType = $request['pricing'];
    }
    $eq_ids = [];
    if ($request->filled('location')) {
      $location = $request['location'];

      $locations = Location::where('name', 'like', '%' . $location . '%')->where('language_id', $language->id)->get();
      $locations_ids = [];
      foreach ($locations as $lk) {
        if (!in_array($lk->id, $locations_ids)) {
          array_push($locations_ids, $lk->id);
        }
      }

      $equipment_locations = EquipmentLocation::whereIn('location_id', $locations_ids)->get();
      foreach ($equipment_locations as $equipment_location) {
        if (!in_array($equipment_location->equipment_id, $eq_ids)) {
          array_push($eq_ids, $equipment_location->equipment_id);
        }
      }
    }

    $allEquipment = Equipment::query()->join('equipment_contents', 'equipments.id', '=', 'equipment_contents.equipment_id')
      ->where('equipment_contents.language_id', '=', $language->id)
      ->when($location, function ($query) use ($eq_ids) {
        return $query->whereIn('equipments.id', $eq_ids);
      })
      ->when($keyword, function ($query, $keyword) {
        return $query->where('equipment_contents.title', 'like', '%' . $keyword . '%');
      })
      ->when($category, function ($query, $category) {
        $categoryId = EquipmentCategory::query()->where('slug', '=', $category)->pluck('id')->first();

        return $query->where('equipment_contents.equipment_category_id', '=', $categoryId);
      })
      ->when(($min && $max), function ($query) use ($min, $max) {
        return $query->where('equipments.lowest_price', '>=', $min)->where('equipments.lowest_price', '<=', $max);
      })
      ->when($dates, function ($query, $dates) use ($bookedEquipmentIds) {
        // get start & end date from the string
        $arrOfDate = explode(' ', $dates);
        $date_1 = $arrOfDate[0];
        $date_2 = $arrOfDate[2];

        // get all the dates between the start & end date
        $allDates = $this->getAllDates($date_1, $date_2, 'Y-m-d');

        $equipments = Equipment::all();

        // loop through all equipment
        foreach ($equipments as $equipment) {
          $equipId = $equipment->id;
          $equipQuantity = $equipment->quantity;

          // loop through the list of dates, which we have found from the start & end date
          foreach ($allDates as $date) {
            $currentDate = Carbon::parse($date);

            // count number of booking of a specific date
            $bookingCount = DB::table('equipment_bookings')->where('equipment_id', '=', $equipId)
              ->whereDate('start_date', '<=', $currentDate)
              ->whereDate('end_date', '>=', $currentDate)
              ->where('payment_status', '=', 'completed')
              ->count();

            // if the number of booking of a specific date is same as the equipment quantity, then mark that equipment as unavailable
            if (($bookingCount >= $equipQuantity) && !in_array($equipId, $bookedEquipmentIds)) {
              array_push($bookedEquipmentIds, $equipId);
            }
          }
        }

        return $query->whereNotIn('equipments.id', $bookedEquipmentIds);
      })
      ->when($pricingType, function ($query, $pricingType) {
        if ($pricingType == 'fixed price') {
          return $query->whereNotNull('equipments.lowest_price');
        } else {
          return $query->whereNull('equipments.lowest_price');
        }
      })
      ->when($in_service_radius_vendors, function ($query, $in_service_radius_vendors) {
        if($in_service_radius_vendors){

        }
         return $query->whereIn('equipments.vendor_id', $in_service_radius_vendors);
      })
      ->select('equipments.id', 'equipments.thumbnail_image', 'equipments.lowest_price', 'equipment_contents.title', 'equipment_contents.slug', 'equipments.per_day_price', 'equipments.per_week_price', 'equipments.per_month_price', 'equipment_contents.features', 'equipments.offer', 'equipments.vendor_id', 'equipment_contents.equipment_category_id')
      ->when($sort, function ($query, $sort) {
        if ($sort == 'new') {
          return $query->orderBy('equipments.created_at', 'desc');
        } else if ($sort == 'old') {
          return $query->orderBy('equipments.created_at', 'asc');
        } else if ($sort == 'ascending') {
          return $query->orderBy('equipments.lowest_price', 'asc');
        } else if ($sort == 'descending') {
          return $query->orderBy('equipments.lowest_price', 'desc');
        }
      }, function ($query) {
        return $query->orderByDesc('equipments.id');
      })
      ->paginate(4);

    $allEquipment->map(function ($equipment) {
      $avgRating = $equipment->review()->avg('rating');
      $ratingCount = $equipment->review()->count();

      $equipment['avgRating'] = floatval($avgRating);
      $equipment['ratingCount'] = $ratingCount;
      
      // code by AG start
        $equipment_fields = EquipmentFieldsValue::where('equipment_id', $equipment->id)->first();
    
        if($equipment_fields){
            $information['equipment_fields'] = json_decode($equipment_fields->fields_value, true);
            $information['multiple_charges_settings'] = json_decode($equipment_fields->multiple_charges_settings, true);
        }
        else{
            $information['equipment_fields'] = array();
            $information['multiple_charges_settings'] = array();
        }
        
        $equipment['multiple_charges_settings'] = $information['multiple_charges_settings'];
        
        
    
        $fields_html = '<div class="equipment-fields-values"><ul style="display: inline-flex;">';
        if( !empty($information['equipment_fields']) ){
          foreach( $information['equipment_fields'] as $key => $equipment_field_ ){
    
            
            if($equipment_field_['type'] == 'Text'){
                $fields_html .= '<li> <span class="span-btn"> <b>'.$equipment_field_['name'].' : </b>'.$equipment_field_['value'].'</span></li>';
            }
    
            if($equipment_field_['type'] == 'Dropdown'){
                $fields_html .= '<li> <span class="span-btn"><b>'.$equipment_field_['name'].' : </b>'.$equipment_field_['value'].'</span></li>';
            }
    
            if($equipment_field_['type'] == 'Price'){
              $currencyInfo = $this->getCurrencyInfo();
              $currencyText = $currencyInfo->base_currency_text;
              $fields_html .= '<li><span class="span-btn"> <b>'.$equipment_field_['name'].' : </b>'.$currencyText = $currencyInfo->base_currency_symbol.' '.$equipment_field_['value'].'</span></li>';
            }
          }
        }
    
        $fields_html .= '</ul></div>';
        
        
        $equipment['fields_html'] = $fields_html;
        // code by AG end
    });
    
    //echo '<pre>'; print_r($allEquipment); die;

    $queryResult['allEquipment'] = $allEquipment;

    $queryResult['currencyInfo'] = $this->getCurrencyInfo();

    $queryResult['categories'] = $language->equipmentCategory()->where('status', 1)->orderBy('serial_number', 'asc')->get();

    $queryResult['minPrice'] = Equipment::query()->min('lowest_price');
    $queryResult['maxPrice'] = Equipment::query()->max('lowest_price');

    return view('frontend.equipment.index', $queryResult);
  }
  
  // code by AG start
  public function get_equipments_by_equipment_fields( $request_data ){
      $equipment_ids_ = array();
      if(isset($request_data['category']) && $request_data['category'] == 'dumpster'){
          $dumpster_type = $request_data['dumpster_type']??'';
          $dumpster_ton = $request_data['dumpster_ton']??'';
          $dumpster_rentaldays = $request_data['dumpster_rentaldays']??'';
          
          
              $equipment_s = EquipmentFieldsValue::when(($dumpster_type != ''), function ($query) use ($dumpster_type) {
                return $query->where('fields_value->[0]->value',$dumpster_type);
              })
              ->when(($dumpster_ton != ''), function ($query) use ($dumpster_ton) {
                return $query->where('multiple_charges_settings->allowed_ton',$dumpster_ton);
              })
              ->when(($dumpster_rentaldays != ''), function ($query) use ($dumpster_rentaldays) {
                return $query->where('multiple_charges_settings->rental_days',$dumpster_rentaldays);
              })
              ->get()->toArray();
          
            if( !empty($equipment_s)){
                $equipment_ids_ = array_column($equipment_s,'equipment_id');
            }
            else{
                if($dumpster_type != '' || $dumpster_ton != '' || $dumpster_rentaldays != ''){
                    $equipment_ids_ = array('0000');
                }
            }
      }
      
      if(isset($request_data['category']) && $request_data['category'] == 'portable-storage-containers'){
          $container_size = $request_data['container_size']??'';
          $container_rentaldays = $request_data['container_rentaldays']??'';
          
          $equipment_s = EquipmentFieldsValue::when(($container_size != ''), function ($query) use ($container_size) {
                return $query->where('fields_value->[1]->value',$container_size);
              })
              ->when(($container_rentaldays != ''), function ($query) use ($container_rentaldays) {
                return $query->where('multiple_charges_settings->rental_days',$container_rentaldays);
              })
              ->get()->toArray();
          
            if( !empty($equipment_s)){
                $equipment_ids_ = array_column($equipment_s,'equipment_id');
            }
             else{
                if($container_size != '' || $container_rentaldays != ''){
                    $equipment_ids_ = array('0000');
                }
            }
      }
      
      if(isset($request_data['category']) && $request_data['category'] == 'temporary-toilets'){
          $toilets_type = $request_data['toilets_type']??'';
          $toilets_services = $request_data['toilets_services']??'';
          $toilets_rental = $request_data['toilets_rental']??'';
          
          
              $equipment_s = EquipmentFieldsValue::when(($toilets_type != ''), function ($query) use ($toilets_type) {
                return $query->where('fields_value->[2]->value',$toilets_type);
              })
              ->when(($toilets_services != ''), function ($query) use ($toilets_services) {
                return $query->where('multiple_charges_settings->services_per_week',$toilets_services);
              })
              ->when(($toilets_rental != ''), function ($query) use ($toilets_rental) {
                return $query->where('multiple_charges_settings->rental_days_included',$toilets_rental);
              })
              ->get()->toArray();
              
              //echo '<pre>'; print_r($equipment_ids_); die;
          
            if( !empty($equipment_s)){
                //echo '<pre>'; print_r($equipment_ids_); die;
                $equipment_ids_ = array_column($equipment_s,'equipment_id');
            }
             else{
                if($toilets_type != '' || $toilets_services != '' || $toilets_rental != ''){
                    $equipment_ids_ = array('0000');
                }
            }
      }
      
      return $equipment_ids_;
  }
  // code by AG end
  
//   search function start 
  public function index_with_map(Request $request)
  {

    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_equipment', 'meta_description_equipment')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    $keyword = $sort = $category = $min = $max = $dates = $pricingType = $location = null;
    $bookedEquipmentIds = [];

    // code by AG start
    
    $request->session()->put('eqp_search_data', $request->all());
    
    
    // code to get equipment ids by filtering custom fields
    $cf_filtered_equipments = $this->get_equipments_by_equipment_fields($request->all());
    
    
    $lat = $long_ = $radius = $unit = $in_service_radius_vendors = null;
    $unit = 'km';
    if ($request->filled('lat')) {
        $lat = $request['lat'];
    }
    if ($request->filled('long')) {
        $long_ = $request['long'];
    }
    if ($request->filled('radius')) {
        $radius = $request['radius'];
    }
    if ($request->filled('unit')) {
        $unit = $request['unit'];
    }

    
    if($lat != '' && $long_ != '' && $radius != ''){
        $in_service_radius_vendors = array();
        // $in_service_radius_vendors = $this->get_in_service_radius_equipments_ids($lat, $long_, $radius, $unit);
        
        if ($request->filled('category')) {
          $category = $request['category'];
        }
        $in_service_radius_vendors_response = $this->get_in_service_radius_equipments_ids_by_vendor_locations($lat, $long_, $radius, $unit, $category);
        $in_service_radius_vendors = $in_service_radius_vendors_response['equipments_ids'];
        
        $queryResult['additional_addresses'] = $in_service_radius_vendors_response['locations_array'];
        $queryResult['addresses'] = array();
        if(empty($in_service_radius_vendors)){
            $in_service_radius_vendors = array('no_vendor');
        }
    }

    // code by AG end

    if ($request->filled('keyword')) {
      $keyword = $request['keyword'];
    }
    if ($request->filled('sort')) {
      $sort = $request['sort'];
    }
    if ($request->filled('category')) {
      $category = $request['category'];
    }
    if ($request->filled('min') && $request->filled('max')) {
      $min = $request['min'];
      $max = $request['max'];
    }
    if ($request->filled('dates')) {
      $dates = $request['dates'];
    }
    if ($request->filled('pricing')) {
      $pricingType = $request['pricing'];
    }
    $eq_ids = [];
    if ($request->filled('location')) {
      $location = $request['location'];

      $locations = Location::where('name', 'like', '%' . $location . '%')->where('language_id', $language->id)->get();
      $locations_ids = [];
      foreach ($locations as $lk) {
        if (!in_array($lk->id, $locations_ids)) {
          array_push($locations_ids, $lk->id);
        }
      }

      $equipment_locations = EquipmentLocation::whereIn('location_id', $locations_ids)->get();
      foreach ($equipment_locations as $equipment_location) {
        if (!in_array($equipment_location->equipment_id, $eq_ids)) {
          array_push($eq_ids, $equipment_location->equipment_id);
        }
      }
    }
   
    
    
    $allEquipmentData = Equipment::query()->join('equipment_contents', 'equipments.id', '=', 'equipment_contents.equipment_id')
        ->where('equipment_contents.language_id', '=', $language->id)
        ->when($location, function ($query) use ($eq_ids) {
            return $query->whereIn('equipments.id', $eq_ids);
        })
        ->when($keyword, function ($query, $keyword) {
            return $query->where('equipment_contents.title', 'like', '%' . $keyword . '%');
        })
        ->when($category, function ($query, $category) {
            $categoryId = EquipmentCategory::query()->where('slug', '=', $category)->pluck('id')->first();
            return $query->where('equipment_contents.equipment_category_id', '=', $categoryId);
        })
        //     ->when(($min && $max), function ($query) use ($min, $max) {
        //     return $query->where('equipments.lowest_price', '>=', $min)->where('equipments.lowest_price', '<=', $max);
        // })
        ->when($dates, function ($query, $dates) use ($bookedEquipmentIds) {
            // get start & end date from the string
            $arrOfDate = explode(' ', $dates);
            $date_1 = $arrOfDate[0];
            $date_2 = $arrOfDate[2];

        // get all the dates between the start & end date
        $allDates = $this->getAllDates($date_1, $date_2, 'Y-m-d');

        $equipments = Equipment::all();

        // loop through all equipment
        foreach ($equipments as $equipment) {
          $equipId = $equipment->id;
          $equipQuantity = $equipment->quantity;

          // loop through the list of dates, which we have found from the start & end date
          foreach ($allDates as $date) {
            $currentDate = Carbon::parse($date);

            // count number of booking of a specific date
            $bookingCount = DB::table('equipment_bookings')->where('equipment_id', '=', $equipId)
              ->whereDate('start_date', '<=', $currentDate)
              ->whereDate('end_date', '>=', $currentDate)
              ->where('payment_status', '=', 'completed')
              ->count();

            // if the number of booking of a specific date is same as the equipment quantity, then mark that equipment as unavailable
            if (($bookingCount >= $equipQuantity) && !in_array($equipId, $bookedEquipmentIds)) {
              array_push($bookedEquipmentIds, $equipId);
            }
          }
        }

        return $query->whereNotIn('equipments.id', $bookedEquipmentIds);
      })
      ->when($pricingType, function ($query, $pricingType) {
        if ($pricingType == 'fixed price') {
          return $query->whereNotNull('equipments.lowest_price');
        } else {
          return $query->whereNull('equipments.lowest_price');
        }
      })
      ->when($in_service_radius_vendors, function ($query, $in_service_radius_vendors) {
        if($in_service_radius_vendors){

        }
         return $query->whereIn('equipments.location_id', $in_service_radius_vendors);
      })
      ->when((!empty($cf_filtered_equipments)), function ($query) use ($cf_filtered_equipments) {
            return $query->whereIn('equipments.id', $cf_filtered_equipments);
      })
      ->select('equipments.id', 'equipments.thumbnail_image', 'equipments.lowest_price', 'equipment_contents.title', 'equipment_contents.slug', 'equipments.per_day_price', 'equipments.per_week_price', 'equipments.per_month_price', 'equipment_contents.features', 'equipments.offer', 'equipments.vendor_id', 'equipment_contents.equipment_category_id','equipments.quantity','equipments.min_booking_days','equipments.max_booking_days')
      ->when($sort, function ($query, $sort) {
        if ($sort == 'new') {
          return $query->orderBy('equipments.created_at', 'desc');
        } else if ($sort == 'old') {
          return $query->orderBy('equipments.created_at', 'asc');
        } else if ($sort == 'ascending') {
            return $query->orderByRaw('CAST(equipments.base_price AS DECIMAL(10, 2)) ASC');
        } else if ($sort == 'descending') {
            return $query->orderByRaw('CAST(equipments.base_price AS DECIMAL(10, 2)) DESC');
        }
      }, function ($query) {
        return $query->orderByDesc('equipments.id');
      })
      ->get();
      
      

      
 

    $allEquipmentData->map(function ($equipment) {
      $avgRating = $equipment->review()->avg('rating');
      $ratingCount = $equipment->review()->count();

      $equipment['avgRating'] = floatval($avgRating);
      $equipment['ratingCount'] = $ratingCount;
      
      // code by AG start
        $equipment_fields = EquipmentFieldsValue::where('equipment_id', $equipment->id)->first();
    
        if($equipment_fields){
            $information['equipment_fields'] = json_decode($equipment_fields->fields_value, true);
            $information['multiple_charges_settings'] = json_decode($equipment_fields->multiple_charges_settings, true);
        }
        else{
            $information['equipment_fields'] = array();
            $information['multiple_charges_settings'] = array();
        }
        
        $equipment['multiple_charges_settings'] = $information['multiple_charges_settings'];
        
        
    
        $fields_html = '<div class="equipment-fields-values"><ul style="display: inline-flex;">';
        if( !empty($information['equipment_fields']) ){
          foreach( $information['equipment_fields'] as $key => $equipment_field_ ){
    
            
            if($equipment_field_['type'] == 'Text'){
                $fields_html .= '<li> <span class="span-btn"> <b>'.$equipment_field_['name'].' : </b>'.$equipment_field_['value'].'</span></li>';
            }
    
            if($equipment_field_['type'] == 'Dropdown'){
                $fields_html .= '<li> <span class="span-btn"><b>'.$equipment_field_['name'].' : </b>'.$equipment_field_['value'].'</span></li>';
            }
    
            if($equipment_field_['type'] == 'Price'){
              $currencyInfo = $this->getCurrencyInfo();
              $currencyText = $currencyInfo->base_currency_text;
              $fields_html .= '<li><span class="span-btn"> <b>'.$equipment_field_['name'].' : </b>'.$currencyText = $currencyInfo->base_currency_symbol.' '.$equipment_field_['value'].'</span></li>';
            }
          }
        }
    
        $fields_html .= '</ul></div>';
        
        
        $equipment['fields_html'] = $fields_html;
        // code by AG end
    });
    
    
    
    
    
    
    
    $allDataHere = [];
    $min_ton = ((int)$request['min_ton']) ? (int)$request['min_ton'] : "";
    $max_ton = ((int)$request['max_ton']) ? (int)$request['max_ton'] : "";
    
    $min_price = ((int)$request['min']) ? (int)$request['min'] : "";
    $max_price = ((int)$request['max']) ? (int)$request['max'] : "";
    
    if(!is_null($queryResult) && !empty($queryResult['additional_addresses'])){
        foreach ($allEquipmentData as $equipment) {
            $vendorId = $equipment['vendor_id'];
            
            // Filter additional addresses based on vendor_id
            $filteredAddresses = array_values(collect($queryResult['additional_addresses'])->where('vendor_id', $vendorId)->all());
            
            $equipment_fields = EquipmentFieldsValue::where('equipment_id', $equipment->id)->first('multiple_charges_settings');
           
            if($min_ton != "" && $max_ton != ""){
                 
                if(!empty($equipment_fields->multiple_charges_settings)){
                    $equipment_fields_data = json_decode($equipment_fields->multiple_charges_settings);
                  
                    if(isset($equipment_fields_data->allowed_ton)){
                           $ton = (int) preg_replace('/[^0-9]/', '', $equipment_fields_data->allowed_ton); 
                           if($ton >= $min_ton && $ton <= $max_ton){
                                
                           }else{
                                continue;
                           }
                    }
                }
            }
            
            if($min_price != "" && $max_price != ""){
                 
                if(!empty($equipment_fields->multiple_charges_settings)){
                    $equipment_fields_data = json_decode($equipment_fields->multiple_charges_settings);
                  
                    if(isset($equipment_fields_data->base_price)){
                           $price = $equipment_fields_data->base_price; 
                           if($price >= $min_price && $price <= $max_price){
                                
                           }else{
                                continue;
                           }
                    }
                }
            }
            
            // Add equipment data and filtered additional addresses to the new array
            $allDataHere[] = [
                'equipment_data' => $equipment,
                'additional_addresses' => $filteredAddresses,
            ];
        }
    }
        
    $totalItems = count($allDataHere);
    $perPage = 10; // Aapki pasand ke mutabiq set karein
    $currentPage = request()->get('page') ?? 1; // Current page number ko determine karne ke liye
    $totalPages = ceil($totalItems / $perPage);
    $offset = ($currentPage - 1) * $perPage;
    
    $slicedData = array_slice($allDataHere, $offset, $perPage);

    
 
$paginationLinks = '<ul class="d-flex m-0 p-0 mt-3 flex-wrap justify-content-center">';
$current_page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number

// Previous button
$prevPage = ($current_page > 1) ? $current_page - 1 : 1;
$prevParams = $_GET;
$prevParams['page'] = $prevPage;
$prevQueryString = http_build_query($prevParams);
$prevDisabled = ($current_page == 1) ? ' disabled' : '';
$paginationLinks .= '<li><a class="pagination-item-prev' . $prevDisabled . '" href="?' . $prevQueryString . '">Previous</a></li>';

// Pagination links
for ($i = 1; $i <= $totalPages; $i++) {
    // Generate new query string with page number and existing query parameters
    $queryParams = $_GET;
    $queryParams['page'] = $i;
    $newQueryString = http_build_query($queryParams);
    
    // Determine if this link is for the current page
    $activeClass = ($i == $current_page) ? '-active' : '';
    
    // Create pagination link
    $paginationLinks .= '<li><a class="pagination-item'. $activeClass . '" href="?' . $newQueryString . '">' . $i . '</a></li>';
}

// Next button
$nextPage = ($current_page < $totalPages) ? $current_page + 1 : $totalPages;
$nextParams = $_GET;
$nextParams['page'] = $nextPage;
$nextQueryString = http_build_query($nextParams);
$nextDisabled = ($current_page == $totalPages) ? ' disabled' : '';
$paginationLinks .= '<li><a class="pagination-item-next' . $nextDisabled . '" href="?' . $nextQueryString . '">Next</a></li>';

$paginationLinks .= '</ul>';




    
    
    //echo '<pre>'; print_r($allEquipment); die;

    $queryResult['allDataHere'] = $slicedData;
    $queryResult['pagination_links'] = $paginationLinks;

    $queryResult['currencyInfo'] = $this->getCurrencyInfo();

    $queryResult['categories'] = $language->equipmentCategory()->where('status', 1)->orderBy('serial_number', 'asc')->get();

    // $queryResult['minPrice'] = Equipment::query()->min('lowest_price');
    // $queryResult['maxPrice'] = Equipment::query()->max('lowest_price');
    
    $equipment_fields_value = EquipmentFieldsValue::orderBy('id', 'desc')->get('multiple_charges_settings');
    $all_base_price_data = [];
    $all_tonnage_data = [];
    foreach($equipment_fields_value as $equipment_value) {
        $multi_charges_data = json_decode($equipment_value->multiple_charges_settings);
        if(isset($multi_charges_data->base_price)){
            $all_base_price_data[] = $multi_charges_data->base_price;
        }
        if(isset($multi_charges_data->allowed_ton)){
            $all_tonnage_data[] = $multi_charges_data->allowed_ton;
        }
    }
    
    
    $minPrice = (int)min($all_base_price_data);
    $maxPrice = (int)max($all_base_price_data);
    
    $minAllowedTon = (int)min($all_tonnage_data);
    $maxAllowedTon = (int)max($all_tonnage_data);

    $queryResult['minPrice'] = $minPrice;
    $queryResult['maxPrice'] = $maxPrice;
    
    $queryResult['minAllowedTon'] = $minAllowedTon;
    $queryResult['maxAllowedTon'] = $maxAllowedTon;
    
    
    // code by AG start
    $advance_search_fields = '';
    if($category == 'dumpster'){
        $types__ = '3yd,4yd,6yd,8yd,10yd,12yd,15yd,16yd,18yd,20yd,25yd,30yd,35yd,40yd';
        $types__ = explode(',', $types__);
        $type_options_ = '';
        if( !empty($types__) ){
            foreach($types__ as $type__){
                $type_options_ .= '<option '.((isset($request->dumpster_type) && $request->dumpster_type == $type__)?'selected':'').' value="'.$type__.'">'.$type__.'</option>';
            }
        }
        $advance_search_fields .= '<div class="col-lg-2 col-md-6 col-sm-12">
        <label>Type</label>
            <select class="form_control" name="dumpster_type" id="dumpster_type">
                <option value="">Type</option>
                '.$type_options_.'
            </select>
        </div>';
        
        
        $tonnages__ = '1ton,2ton,3ton,4ton,5ton,6ton,7ton,8ton,9ton';
        $tonnages__ = explode(',', $tonnages__);
        $tonnages_options_ = '';
        if( !empty($tonnages__) ){
            foreach($tonnages__ as $tonnage__){
                $tonnages_options_ .= '<option '.((isset($request->dumpster_ton) && $request->dumpster_ton == $tonnage__)?'selected':'').' value="'.$tonnage__.'">'.$tonnage__.'</option>';
            }
        }
        $advance_search_fields .= '<div class="col-lg-2 col-md-6 col-sm-12">
        <label>Tonnage</label>
            <select class="form_control" name="dumpster_ton" id="dumpster_ton">
                <option value="">Tonnage</option>
                '.$tonnages_options_.'
            </select>
        </div>';
        
        $advance_search_fields .= '<div class="col-lg-2 col-md-6 col-sm-12">
        <label>Rental Days</label>
            <input type="number" step="1" name="dumpster_rentaldays" id="dumpster_rentaldays" placeholder="Rental Days" class="form_control" value="'.($request->dumpster_rentaldays??'').'">
        </div>';
    }
    if($category == 'temporary-toilets'){
        $types__ = 'Flushable Toilet with Sink,ADA Handicap Toilet,Flushable Toilet,Portable Sink,Portable Toilet,Standard Toilet with sink';
        $types__ = explode(',', $types__);
        $type_options_ = '';
        if( !empty($types__) ){
            foreach($types__ as $type__){
                $type_options_ .= '<option '.((isset($request->toilets_type) && $request->toilets_type == $type__)?'selected':'').' value="'.$type__.'">'.$type__.'</option>';
            }
        }
        $advance_search_fields .= '<div class="col-lg-2 col-md-6 col-sm-12">
        <label>Type</label>
            <select class="form_control" name="toilets_type" id="toilets_type">
                <option value="">Type</option>
                '.$type_options_.'
            </select>
        </div>';
        
        
        $services__ = '1,2,3';
        $services__ = explode(',', $services__);
        $services_options_ = '';
        if( !empty($services__) ){
            foreach($services__ as $service__){
                $services_options_ .= '<option '.((isset($request->toilets_services) && $request->toilets_services == $service__)?'selected':'').' value="'.$service__.'">'.$service__.'</option>';
            }
        }
        $advance_search_fields .= '<div class="col-lg-2 col-md-6 col-sm-12">
        <label>Services per week</label>
            <select class="form_control" name="toilets_services" id="toilets_services">
                <option value="">Services per week</option>
                '.$services_options_.'
            </select>
        </div>';
        
        $rental_days__ = '28,Monthly';
        $rental_days__ = explode(',', $rental_days__);
        $rental_days_options_ = '';
        if( !empty($rental_days__) ){
            foreach($rental_days__ as $rental_day__){
                $rental_days_options_ .= '<option '.((isset($request->toilets_rental) && $request->toilets_rental == $rental_day__)?'selected':'').' value="'.$rental_day__.'">'.$rental_day__.'</option>';
            }
        }
        $advance_search_fields .= '<div class="col-lg-2 col-md-6 col-sm-12">
         <label>Rental</label>
            <select class="form_control" name="toilets_rental" id="toilets_rental">
                <option value="">Rental</option>
                '.$rental_days_options_.'
            </select>
        </div>';
    }
    if($category == 'portable-storage-containers'){
        $sizes__ = '12ft,16ft,20ft,40ft';
        $sizes__ = explode(',', $sizes__);
        $sizes_options_ = '';
        if( !empty($sizes__) ){
            foreach($sizes__ as $size__){
                $sizes_options_ .= '<option '.((isset($request->container_size) && $request->container_size == $size__)?'selected':'').' value="'.$size__.'">'.$size__.'</option>';
            }
        }
        $advance_search_fields .= '<div class="col-lg-2 col-md-6 col-sm-12">
        <label>Container Size</label>
            <select class="form_control" name="container_size" id="container_size">
                <option value="">Container Size</option>
                '.$sizes_options_.'
            </select>
        </div>';
        
        $advance_search_fields .= '<div class="col-lg-2 col-md-6 col-sm-12">
        <label>Rental Days</label>
            <input type="number" step="1" min="0" name="container_rentaldays" id="container_rentaldays" placeholder="Rental Days" class="form_control" value="'.($request->container_rentaldays??'').'">
        </div>';
    }
    
    $queryResult['advance_search_fields'] = $advance_search_fields;
    
    // code by AG end

    return view('frontend.equipment.index2', $queryResult);
  }
//   code by rz start 

public function index_2(Request $request)
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_equipment', 'meta_description_equipment')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    $keyword = $sort = $category = $min = $max = $dates = $pricingType = $location = null;
    $bookedEquipmentIds = [];

    // code by AG start
    $lat = $long_ = $radius = $unit = $in_service_radius_vendors = null;
    $unit = 'km';
    if ($request->filled('lat')) {
        $lat = $request['lat'];
    }
    if ($request->filled('long')) {
        $long_ = $request['long'];
    }
    if ($request->filled('radius')) {
        $radius = $request['radius'];
    }
    if ($request->filled('unit')) {
        $unit = $request['unit'];
    }

    
    if($lat != '' && $long_ != '' && $radius != ''){
        $in_service_radius_vendors = array();
        $in_service_radius_vendors = $this->get_in_service_radius_equipments_ids_2($lat, $long_, $radius, $unit);
        $additional_addresses = $in_service_radius_vendors[1];
        $addresses = $in_service_radius_vendors[2];
        $in_service_radius_vendors = $in_service_radius_vendors[0];
        if(empty($in_service_radius_vendors)){
            $in_service_radius_vendors = array('no_vendor');
        }
        if(isset($additional_addresses)){
            if(count($additional_addresses) > 0){
                $queryResult['additional_addresses'] = $additional_addresses;
            }  
        }
        if(isset($addresses)){
            if(count($addresses) > 0){
                $queryResult['addresses'] = $addresses;
            }
        }
        }else{
             $additional_addresses = DB::table('additional_addresses as additional_address')
            ->join('locations as location', 'location.additional_address', '=', 'additional_address.id')
            ->select('additional_address.*', 'location.*')
            ->get();
            $addresses = DB::table('vendor_settings as vendor_setting')
            ->join('equipments as equipment', 'equipment.vendor_id', '=', 'vendor_setting.vendor_id')
            ->select('vendor_setting.*')
            ->get();
            if(isset($additional_addresses)){
                if(count($additional_addresses) > 0){
                    $queryResult['additional_addresses'] = $additional_addresses;
                }  
            }
            if(isset($addresses)){
                if(count($addresses) > 0){
                    $queryResult['addresses'] = $addresses;
                }
            }
           
        }

    // code by AG end

    if ($request->filled('keyword')) {
      $keyword = $request['keyword'];
    }
    if ($request->filled('sort')) {
      $sort = $request['sort'];
    }
    if ($request->filled('category')) {
      $category = $request['category'];
    }
    if ($request->filled('min') && $request->filled('max')) {
      $min = $request['min'];
      $max = $request['max'];
    }
    if ($request->filled('dates')) {
      $dates = $request['dates'];
    }
    if ($request->filled('pricing')) {
      $pricingType = $request['pricing'];
    }
    $eq_ids = [];
    if ($request->filled('location')) {
      $location = $request['location'];

      $locations = Location::where('name', 'like', '%' . $location . '%')->where('language_id', $language->id)->get();
      $locations_ids = [];
      foreach ($locations as $lk) {
        if (!in_array($lk->id, $locations_ids)) {
          array_push($locations_ids, $lk->id);
        }
      }

      $equipment_locations = EquipmentLocation::whereIn('location_id', $locations_ids)->get();
      foreach ($equipment_locations as $equipment_location) {
        if (!in_array($equipment_location->equipment_id, $eq_ids)) {
          array_push($eq_ids, $equipment_location->equipment_id);
        }
      }
    }

    $allEquipment = Equipment::query()->join('equipment_contents', 'equipments.id', '=', 'equipment_contents.equipment_id')
      ->where('equipment_contents.language_id', '=', $language->id)
      ->when($location, function ($query) use ($eq_ids) {
        return $query->whereIn('equipments.id', $eq_ids);
      })
      ->when($keyword, function ($query, $keyword) {
        return $query->where('equipment_contents.title', 'like', '%' . $keyword . '%');
      })
      ->when($category, function ($query, $category) {
        $categoryId = EquipmentCategory::query()->where('slug', '=', $category)->pluck('id')->first();

        return $query->where('equipment_contents.equipment_category_id', '=', $categoryId);
      })
      ->when(($min && $max), function ($query) use ($min, $max) {
        return $query->where('equipments.lowest_price', '>=', $min)->where('equipments.lowest_price', '<=', $max);
      })
      ->when($dates, function ($query, $dates) use ($bookedEquipmentIds) {
        // get start & end date from the string
        $arrOfDate = explode(' ', $dates);
        $date_1 = $arrOfDate[0];
        $date_2 = $arrOfDate[2];

        // get all the dates between the start & end date
        $allDates = $this->getAllDates($date_1, $date_2, 'Y-m-d');

        $equipments = Equipment::all();

        // loop through all equipment
        foreach ($equipments as $equipment) {
          $equipId = $equipment->id;
          $equipQuantity = $equipment->quantity;

          // loop through the list of dates, which we have found from the start & end date
          foreach ($allDates as $date) {
            $currentDate = Carbon::parse($date);

            // count number of booking of a specific date
            $bookingCount = DB::table('equipment_bookings')->where('equipment_id', '=', $equipId)
              ->whereDate('start_date', '<=', $currentDate)
              ->whereDate('end_date', '>=', $currentDate)
              ->where('payment_status', '=', 'completed')
              ->count();

            // if the number of booking of a specific date is same as the equipment quantity, then mark that equipment as unavailable
            if (($bookingCount >= $equipQuantity) && !in_array($equipId, $bookedEquipmentIds)) {
              array_push($bookedEquipmentIds, $equipId);
            }
          }
        }

        return $query->whereNotIn('equipments.id', $bookedEquipmentIds);
      })
      ->when($pricingType, function ($query, $pricingType) {
        if ($pricingType == 'fixed price') {
          return $query->whereNotNull('equipments.lowest_price');
        } else {
          return $query->whereNull('equipments.lowest_price');
        }
      })
      ->when($in_service_radius_vendors, function ($query, $in_service_radius_vendors) {
        if($in_service_radius_vendors){

        }
        $equipments = $query->whereIn('equipments.vendor_id', $in_service_radius_vendors);
      })
      ->select('equipments.id', 'equipments.thumbnail_image', 'equipments.lowest_price', 'equipment_contents.title', 'equipment_contents.slug', 'equipments.per_day_price', 'equipments.per_week_price', 'equipments.per_month_price', 'equipment_contents.features', 'equipments.offer', 'equipments.vendor_id', 'equipment_contents.equipment_category_id')
      ->when($sort, function ($query, $sort) {
        if ($sort == 'new') {
          return $query->orderBy('equipments.created_at', 'desc');
        } else if ($sort == 'old') {
          return $query->orderBy('equipments.created_at', 'asc');
        } else if ($sort == 'ascending') {
          return $query->orderBy('equipments.lowest_price', 'asc');
        } else if ($sort == 'descending') {
          return $query->orderBy('equipments.lowest_price', 'desc');
        }
      }, function ($query) {
        return $query->orderByDesc('equipments.id');
      })
      ->paginate(4);

    $allEquipment->map(function ($equipment) {
      $avgRating = $equipment->review()->avg('rating');
      $ratingCount = $equipment->review()->count();

      $equipment['avgRating'] = floatval($avgRating);
      $equipment['ratingCount'] = $ratingCount;
    });
//     $vendorArray = [];

// foreach ($allEquipment as $all_id) {
//     $location_id = AdditionalAddress::where('vendor_id', $all_id->vendor_id)->get();
//     foreach ($location_id as $id) {
//     $arr = ['id' => $id->vendor_id, 'address' => empty($id->address) ? '' : $id->address];
//     }
//     $vendorArray[] = $arr;
// }

    // dd($vendorArray);
    $queryResult['allEquipment'] = $allEquipment;

    $queryResult['currencyInfo'] = $this->getCurrencyInfo();

    $queryResult['categories'] = $language->equipmentCategory()->where('status', 1)->orderBy('serial_number', 'asc')->get();

    $queryResult['minPrice'] = Equipment::query()->min('lowest_price');
    $queryResult['maxPrice'] = Equipment::query()->max('lowest_price');
    

   
    return view('frontend.equipment.index2', $queryResult);
  }
// code by rz end
  // code by AG start
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

    public function get_in_service_radius_equipments_ids_by_vendor_locations($lat = '', $lng = '', $distance = '', $unit = 'km', $category=''){
        $equipments_ids = array();
		
		
		if($lat != '' && $lng != '' && $distance != ''){
            $equipments = Location::select(
            '*');
            
            
            
            if ($unit == 'km') $radius = 6371.009; // in kilometers
            elseif ($unit == 'mile') $radius = 3958.761; // in miles
            
            $equipments = $equipments->selectRaw(
            '(? * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance',
            [$radius, $lat, $lng, $lat]
        );
                
            // latitude boundaries
            $maxLat = (float) $lat + rad2deg($distance / $radius);
            $minLat = (float) $lat - rad2deg($distance / $radius);

            // longitude boundaries (longitude gets smaller when latitude increases)
            $maxLng = (float) $lng + rad2deg($distance / $radius / cos(deg2rad((float) $lat)));
            $minLng = (float) $lng - rad2deg($distance / $radius / cos(deg2rad((float) $lat)));

            // $equipments = $equipments->where('latitude', '>', $minLat);
            // $equipments = $equipments->where('latitude', '<', $maxLat);
            
            // $equipments = $equipments->where('longitude', '>', $minLng);
            // $equipments = $equipments->where('longitude', '<', $maxLng);
            
            $equipments = $equipments->having('distance', '<', $distance);
            
            
            if($category != ''){
                $categoryId = EquipmentCategory::query()->where('slug', '=', $category)->pluck('id')->first();
                $equipments = $equipments->where('equipment_category_id', '=', $categoryId);
            }
            
            
            $equipments = $equipments->get();
            $equipments = $equipments->toArray();
            
            
            $locations_array = array();
            if( !empty( $equipments ) ){
                foreach($equipments as $equipment){
                    $distance__ = $this->getDistance($lat, $lng, $equipment['latitude'], $equipment['longitude'], $unit);
                    
                    //$vendor_unit = ($equipment['unit'] != '')?strtolower($equipment['unit']):'km';
                    $vendor_unit = 'mile';
                    if($unit == $vendor_unit){
                        $vendor_radius = $equipment['radius'];
                    }else{
                        if($unit == 'km'){
                            $vendor_radius = round(($equipment['radius'] * 1.609), 3);
                        }
                        if($unit == 'mile'){
                            $vendor_radius = round(($equipment['radius'] / 1.609), 3);
                        }
                    }
                    
                    if($distance__ <= $vendor_radius){
                        $equipments_ids[] = $equipment['id'];
                        $locations_array[] = $equipment;
                    }
                }
            }
        }
    
    
    
    return array('equipments_ids'=>$equipments_ids, 'locations_array'=>$locations_array);
}

  public function get_in_service_radius_equipments_ids($lat = '', $lng = '', $distance = '', $unit = 'km'){
        $equipments_ids = array();
		
		
		if($lat != '' && $lng != '' && $distance != ''){
            $equipments = VendorSetting::select(
            '*');
            
            if ($unit == 'km') $radius = 6371.009; // in kilometers
            elseif ($unit == 'mile') $radius = 3958.761; // in miles
                
            // latitude boundaries
            $maxLat = (float) $lat + rad2deg($distance / $radius);
            $minLat = (float) $lat - rad2deg($distance / $radius);

            // longitude boundaries (longitude gets smaller when latitude increases)
            $maxLng = (float) $lng + rad2deg($distance / $radius / cos(deg2rad((float) $lat)));
            $minLng = (float) $lng - rad2deg($distance / $radius / cos(deg2rad((float) $lat)));

            $equipments = $equipments->where('latitude', '>', $minLat);
            $equipments = $equipments->where('latitude', '<', $maxLat);
            
            $equipments = $equipments->where('longitude', '>', $minLng);
            $equipments = $equipments->where('longitude', '<', $maxLng);
            
            $equipments = $equipments->get();
            $equipments = $equipments->toArray();
            
            
            
            if( !empty( $equipments ) ){
                foreach($equipments as $equipment){
                    $distance__ = $this->getDistance($lat, $lng, $equipment['latitude'], $equipment['longitude'], $unit);
                    
                    $vendor_unit = ($equipment['unit'] != '')?strtolower($equipment['unit']):'km';
                    
                    if($unit == $vendor_unit){
                        $vendor_radius = $equipment['provide_service'];
                    }else{
                        if($unit == 'km'){
                            $vendor_radius = round(($equipment['provide_service'] * 1.609), 3);
                        }
                        if($unit == 'mile'){
                            $vendor_radius = round(($equipment['provide_service'] / 1.609), 3);
                        }
                    }
                    
                    if($distance__ <= $vendor_radius){
                        $equipments_ids[] = $equipment['vendor_id'];
                    }
                }
            }
        }
    
    
    
    return $equipments_ids;
  }
  // code by AG end

    public function get_in_service_radius_equipments_ids_2($lat = '', $lng = '', $distance = '', $unit = 'mile'){
        $equipments_ids = array();
		
		
		if($lat != '' && $lng != '' && $distance != ''){
            $equipments = VendorSetting::select(
            '*');
            
            if ($unit == 'km') $radius = 6371.009; // in kilometers
            elseif ($unit == 'mile') $radius = 3958.761; // in miles
                
            // latitude boundaries
            $maxLat = (float) $lat + rad2deg($distance / $radius);
            $minLat = (float) $lat - rad2deg($distance / $radius);
            // longitude boundaries (longitude gets smaller when latitude increases)
            $maxLng = (float) $lng + rad2deg($distance / $radius / cos(deg2rad((float) $lat)));
            $minLng = (float) $lng - rad2deg($distance / $radius / cos(deg2rad((float) $lat)));

            $equipments = $equipments->where('latitude', '>', $minLat);
            $equipments = $equipments->where('latitude', '<', $maxLat);
            
            $equipments = $equipments->where('longitude', '>', $minLng);
            $equipments = $equipments->where('longitude', '<', $maxLng);
            
            $equipments = $equipments->get();
            $equipments_2 = $equipments;
            $equipments = $equipments->toArray();
            
            
            
            if( !empty( $equipments ) ){
                foreach($equipments as $equipment){
                    $distance__ = $this->getDistance($lat, $lng, $equipment['latitude'], $equipment['longitude'], $unit);

                    $vendor_unit = ($equipment['unit'] != '')?strtolower($equipment['unit']):'mile';
                    
                    if($unit == $vendor_unit){
                        $vendor_radius = $equipment['provide_service'];
                    }else{
                        if($unit == 'km'){
                            $vendor_radius = round(($equipment['provide_service'] * 1.609), 3);
                        }
                        if($unit == 'mile'){
                            $vendor_radius = round(($equipment['provide_service'] / 1.609), 3);
                        }
                    }
                   
                    if($distance__ <= $vendor_radius){
                        $equipments_ids[] = $equipment['vendor_id'];
                        
                    }
                }
            }
            // code by rz start
            $equipments_additional = DB::table('additional_addresses as additional_address')
                ->join('locations as location', 'location.additional_address', '=', 'additional_address.id')
                ->select('additional_address.*', 'location.*')
                ->where('additional_address.latitude', '>', $minLat)
                ->where('additional_address.latitude', '<', $maxLat)
                ->where('additional_address.longitude', '>', $minLng)
                ->where('additional_address.longitude', '<', $maxLng)
                ->get();

            $equipments_additional = $equipments_additional->toArray();
            if( !empty( $equipments_additional ) ){
                foreach($equipments_additional as $equipment_additional){
                    $distance__additional = $this->getDistance($lat, $lng, $equipment_additional->latitude, $equipment_additional->longitude, $unit);
                    $vendor_check = VendorSetting::where('vendor_id', $equipment_additional->vendor_id)->first();
                    $vendor_unit_additional = ($vendor_check['unit'] != '')?strtolower($vendor_check['unit']):'mile';
                    
                    if($unit == $vendor_unit_additional){
                        $vendor_radius_additional = $equipment_additional->radius;
                    }else{
                        if($unit == 'km'){
                            $vendor_radius_additional = round(($equipment_additional->radius * 1.609), 3);
                        }
                        if($unit == 'mile'){
                            $vendor_radius_additional = round(($equipment_additional->radius / 1.609), 3);
                        }
                    }
                    
                    if($distance__additional <= $vendor_radius_additional){
                        $equipments_ids[] = $equipment_additional->vendor_id;
                    }
                }
            }
            // code by rz end
        }
    
    $equipment_address = $equipments_2;
    
    return [$equipments_ids,$equipments_additional,$equipment_address];    

  }
  // code by AG end
  public function show($slug, Request $request)
  {
      $eqp_search_data = $request->session()->get('eqp_search_data');
     
    $request->session()->put('redirectTo', url()->current());

    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    $details = Equipment::query()->join('equipment_contents', 'equipments.id', '=', 'equipment_contents.equipment_id')
      ->join('equipment_categories', 'equipment_categories.id', '=', 'equipment_contents.equipment_category_id')
      ->where('equipment_contents.language_id', '=', $language->id)
      ->where('equipment_contents.slug', '=', $slug)
      ->select('equipments.id', 'equipments.vendor_id', 'equipments.slider_images', 'equipment_contents.title', 'equipment_categories.name as categoryName', 'equipment_categories.slug as categorySlug', 'equipment_contents.description', 'equipment_contents.features', 'equipments.lowest_price', 'equipments.per_day_price', 'equipments.per_week_price', 'equipments.per_month_price', 'equipments.min_booking_days', 'equipments.max_booking_days', 'equipments.security_deposit_amount', 'equipment_contents.meta_keywords', 'equipment_contents.meta_description', 'equipment_contents.equipment_category_id')
      ->firstOrFail();

    $queryResult['details'] = $details;

    $queryResult['currencyInfo'] = $this->getCurrencyInfo();

    $equipmentId = EquipmentContent::query()->where('language_id', '=', $language->id)
      ->where('slug', '=', $slug)
      ->pluck('equipment_id')
      ->first();

    $reviews = EquipmentReview::query()->where('equipment_id', '=', $equipmentId)->orderByDesc('id')->get();

    $reviews->map(function ($review) {
      $review['user'] = $review->userInfo()->first();
    });

    $queryResult['reviews'] = $reviews;

    $basicData = Basic::select('self_pickup_status', 'two_way_delivery_status', 'equipment_tax_amount', 'guest_checkout_status')->first();

    if ($details->vendor_id != NULL) {
      $basicData2 = Vendor::where('id', $details->vendor_id)->select('self_pickup_status', 'two_way_delivery_status')->first();
      $c_data = collect($basicData2);

      $c_data->put('equipment_tax_amount', $basicData->equipment_tax_amount);
      $c_data->put('guest_checkout_status', $basicData->guest_checkout_status);

      $queryResult['basicData'] = $c_data;
    } else {
      $queryResult['basicData'] = $basicData;
    }



    $equipment = Equipment::query()->findOrFail($equipmentId);
    $quantity = $equipment->quantity;

    $bookings = EquipmentBooking::query()->where('equipment_id', '=', $equipmentId)
      ->where('payment_status', '=', 'completed')
      ->orWhere('payment_status', '=', 'pending')
      ->select('start_date', 'end_date')
      ->get();


    $bookedDates = [];

    foreach ($bookings as $booking) {
      // get all the dates between the booking start date & booking end date
      $date_1 = $booking->start_date;
      $date_2 = $booking->end_date;

      $allDates = $this->getAllDates($date_1, $date_2, 'Y-m-d');

      // loop through the list of dates, which we have found from the booking start date & booking end date
      foreach ($allDates as $date) {
        $bookingCount = 0;

        // loop through all the bookings
        foreach ($bookings as $currentBooking) {
          $bookingStartDate = Carbon::parse($currentBooking->start_date);
          $bookingEndDate = Carbon::parse($currentBooking->end_date);
          $currentDate = Carbon::parse($date);

          // check for each date, whether the date is present or not in any of the booking date range
          if ($currentDate->betweenIncluded($bookingStartDate, $bookingEndDate)) {
            $bookingCount++;
          }
        }

        // if the number of booking of a specific date is same as the equipment quantity, then mark that date as unavailable
        if ($bookingCount >= $quantity && !in_array($date, $bookedDates)) {
          array_push($bookedDates, $date);
        }
      }
    }


    $queryResult['bookedDates'] = $bookedDates;

    if (!session()->has('shippingMethod')) {
      if ($basicData->self_pickup_status == 1 && $basicData->two_way_delivery_status == 1) {
        session()->put('shippingMethod', 'self pickup');
      } else if ($basicData->self_pickup_status == 1 && $basicData->two_way_delivery_status == 0) {
        session()->put('shippingMethod', 'self pickup');
      } else if ($basicData->self_pickup_status == 0 && $basicData->two_way_delivery_status == 1) {
        session()->put('shippingMethod', 'two way delivery');
      } else {
        session()->put('shippingMethod', null);
      }
    }

    if ($equipment) {
      $location_ids = [];

      $eq_locations = EquipmentLocation::where([['equipment_id', $equipment->id], ['language_id', $language->id]])->get();

      foreach ($eq_locations as $location) {
        if (!in_array($location->location_id, $location_ids)) {
          array_push($location_ids, $location->location_id);
        }
      }

      $locations = Location::where('language_id', $language->id)->whereIn('id', $location_ids)->get();

      $queryResult['locations'] = $locations;
    }


    $queryResult['onlineGateways'] = OnlineGateway::where('status', 1)->get();

    $queryResult['offlineGateways'] = OfflineGateway::where('status', 1)->orderBy('serial_number', 'asc')->get();

    // code by AG start
    $equipment_fields = EquipmentFieldsValue::where('equipment_id', $equipmentId)->first();

    if($equipment_fields){
        $information['equipment_fields'] = json_decode($equipment_fields->fields_value, true);
        
        $information['multiple_charges_settings'] = json_decode($equipment_fields->multiple_charges_settings, true);
    }
    else{
        $information['equipment_fields'] = array();
        $information['multiple_charges_settings'] = array();
    }
    
    $queryResult['multiple_charges_settings'] = $information['multiple_charges_settings'];

    $fields_html = '<div class="equipment-fields-values"><ul>';
    if( !empty($information['equipment_fields']) ){
      foreach( $information['equipment_fields'] as $key => $equipment_field_ ){

        
        if($equipment_field_['type'] == 'Text'){
            $fields_html .= '<li><b>'.$equipment_field_['name'].' : </b>'.$equipment_field_['value'].'</li>';
        }

        if($equipment_field_['type'] == 'Dropdown'){
            $fields_html .= '<li><b>'.$equipment_field_['name'].' : </b>'.$equipment_field_['value'].'</li>';
        }

        if($equipment_field_['type'] == 'Price'){
          $currencyInfo = $this->getCurrencyInfo();
          $currencyText = $currencyInfo->base_currency_text;
          $fields_html .= '<li><b>'.$equipment_field_['name'].' : </b>'.$currencyText = $currencyInfo->base_currency_symbol.' '.$equipment_field_['value'].'</li>';
        }
      }
    }
    
    
     if( !empty($information['multiple_charges_settings']) ){
      foreach( $information['multiple_charges_settings'] as $key => $equipment_field_ ){
          if($key != 'base_price'){
              $key_name_ = str_replace("_"," ",$key);
                $fields_html .= '<li><b>'.ucwords($key_name_).' : </b>'.$equipment_field_.'</li>';
        
          }
        
      }
    }

    $fields_html .= '</ul></div>';
    $queryResult['fields_html'] = $fields_html;
    $queryResult['eqp_search_data'] = $eqp_search_data;
    
   
    if(Auth::guard('web')->check()){
        $authUser = Auth::guard('web')->user();
        $branches = BranchUser::where('user_id',$authUser->id)->get()->pluck('branch_id');
        $queryResult['user_cards'] = UserCard::where('user_id', $authUser->id)->orwhereIn('branch_id',$branches)->get();
    }else{
        $queryResult['user_cards'] = [];    
    }
    
    if(Auth::guard('web')->check()){
        $queryResult['default_user_card'] = UserCard::where('user_id',auth()->user()->id)->where('is_default',1)->first();    
    }else{
        $queryResult['default_user_card'] = "";
    }
    
    
    
    // code by AG end

    // code by AG start
    if(is_equipment_request_for_price($queryResult['details']->equipment_category_id)){
        return view('frontend.equipment.request-a-quote', $queryResult);
    }else{
        return view('frontend.equipment.details', $queryResult);
    }
    // code by AG end

    //return view('frontend.equipment.details', $queryResult); // commented by AG
  }

  /**
   * Get all the dates between the booking start date & booking end date.
   *
   * @param  string  $startDate
   * @param  string  $endDate
   * @param  string  $format
   * @return array
   */
  public function getAllDates($startDate, $endDate, $format)
  {
    $dates = [];

    // convert string to timestamps
    $currentTimestamps = strtotime($startDate);
    $endTimestamps = strtotime($endDate);

    // set an increment value
    $stepValue = '+1 day';

    // push all the timestamps to the 'dates' array by formatting those timestamps into date
    while ($currentTimestamps <= $endTimestamps) {
      $formattedDate = date($format, $currentTimestamps);
      array_push($dates, $formattedDate);
      $currentTimestamps = strtotime($stepValue, $currentTimestamps);
    }

    return $dates;
  }

  public function minPrice(Request $request, $id)
  {
    $dates = $request['dates'];
    $totalDays = $this->diffOfDates($dates);

    $equipment = Equipment::find($id);
    
    $currencyInfo = $this->getCurrencyInfo();
    $position = $currencyInfo->base_currency_symbol_position;
    $symbol = $currencyInfo->base_currency_symbol;
    

    if (!empty($equipment)) {
      $perDayPrice = is_null($equipment->per_day_price) ? 0.00 : amount_with_commission($equipment->per_day_price);
      $perWeekPrice = is_null($equipment->per_week_price) ? 0.00 : amount_with_commission($equipment->per_week_price);
      $perMonthPrice = is_null($equipment->per_month_price) ? 0.00 : amount_with_commission($equipment->per_month_price);
      $prices = [];

      // case: 1 -> calculate price according to month & day
      if ($perMonthPrice == 0.00 && $perDayPrice == 0.00) {
        array_push($prices, null);
      } else {
        $finalMonth = 1;
        $finalDay = 0;
        $month = $totalDays / 30;
        $finalMonth = floor($month);

        if ($this->isDecimal($month)) {
          $finalDay = $totalDays % 30;
        }

        $monthDayPrice = ($finalMonth * $perMonthPrice) + ($finalDay * $perDayPrice);
        array_push($prices, $monthDayPrice);
      }

      // case: 2 -> calculate price according to week & day
      if (!empty($perWeekPrice) && !empty($perDayPrice)) {
        $finalWeek = 1;
        $finalDay = 0;
        $week = $totalDays / 7;
        $finalWeek = floor($week);

        if ($this->isDecimal($week)) {
          $finalDay = $totalDays % 7;
        }

        $weekDayPrice = ($finalWeek * $perWeekPrice) + ($finalDay * $perDayPrice);
        array_push($prices, $weekDayPrice);
      }

      // case: 3 -> calculate price according to month, week & day
      if (!empty($perMonthPrice) && !empty($perWeekPrice) && !empty($perDayPrice)) {
        $finalMonth = 1;
        $finalWeek = 0;
        $finalDay = 0;

        if ($totalDays > 30) {
          $month = $totalDays / 30;
          $finalMonth = floor($month);

          if ($this->isDecimal($month)) {
            $day = $totalDays % 30;

            if ($day >= 7) {
              $week = $day / 7;
              $finalWeek = floor($week);

              if ($this->isDecimal($week)) {
                $finalDay = $day % 7;
              }
            } else {
              $finalDay = $day;
            }
          }
        }

        $monthWeekDayPrice = ($finalMonth * $perMonthPrice) + ($finalWeek * $perWeekPrice) + ($finalDay * $perDayPrice);
        array_push($prices, $monthWeekDayPrice);
      }

      // case: 4 -> calculate price according to month & week
      if (!empty($perMonthPrice) && !empty($perWeekPrice)) {
        $finalMonth = 1;
        $finalWeek = 0;

        if ($totalDays > 30) {
          $month = $totalDays / 30;
          $finalMonth = floor($month);

          if ($this->isDecimal($month)) {
            $day = $totalDays % 30;
            $finalWeek = 1;

            if ($day > 7) {
              $week = $day / 7;
              $finalWeek = floor($week);

              if ($this->isDecimal($week)) {
                $finalWeek = $finalWeek + 1;
              }
            }
          }
        }

        $monthWeekPrice = ($finalMonth * $perMonthPrice) + ($finalWeek * $perWeekPrice);
        array_push($prices, $monthWeekPrice);
      }

      // case: 5 -> calculate price according to only month
      if (!empty($perMonthPrice)) {
        $finalMonth = 1;

        if ($totalDays > 30) {
          $month = $totalDays / 30;
          $finalMonth = floor($month);

          if ($this->isDecimal($month)) {
            $finalMonth = $finalMonth + 1;
          }
        }

        $monthPrice = $finalMonth * $perMonthPrice;
        array_push($prices, $monthPrice);
      }


      // case: 6 -> calculate price according to only week
      if (!empty($perWeekPrice)) {
        $finalWeek = 1;

        if ($totalDays > 7) {
          $week = $totalDays / 7;
          $finalWeek = floor($week);

          if ($this->isDecimal($week)) {
            $finalWeek = $finalWeek + 1;
          }
        }

        $weekPrice = $finalWeek * $perWeekPrice;
        array_push($prices, $weekPrice);
      }


      // case: 7 -> calculate price according to only day
      if (!empty($perDayPrice)) {
        $dayPrice = $totalDays * $perDayPrice;
        array_push($prices, $dayPrice);
      }
      
      
      $priceArr = array_diff($prices, array(null, 0));
      $minimumPrice = min($priceArr);
      
      // case: custom by AG to calculate price for multiple charges category
      // code by AG start
      
        $additional_charges_item_html = '';
        $misc = new MiscellaneousController();
    
        $language = $misc->getLanguage();
        
        $details__ = Equipment::query()->join('equipment_contents', 'equipments.id', '=', 'equipment_contents.equipment_id')
          ->join('equipment_categories', 'equipment_categories.id', '=', 'equipment_contents.equipment_category_id')
          ->where('equipment_contents.language_id', '=', $language->id)
          ->where('equipments.id', $id)
          ->select('equipments.id', 'equipments.vendor_id', 'equipments.slider_images', 'equipment_contents.title', 'equipment_categories.name as categoryName', 'equipment_categories.slug as categorySlug', 'equipment_contents.description', 'equipment_contents.features', 'equipments.lowest_price', 'equipments.per_day_price', 'equipments.per_week_price', 'equipments.per_month_price', 'equipments.min_booking_days', 'equipments.max_booking_days', 'equipments.security_deposit_amount', 'equipment_contents.meta_keywords', 'equipment_contents.meta_description', 'equipment_contents.equipment_category_id')
          ->firstOrFail();
        
        if(is_equipment_multiple_charges($details__->equipment_category_id)){
            $equipment_fields = EquipmentFieldsValue::where('equipment_id', $id)->first();

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
                
                if($totalDays > $rental_days){
                    $extra_days = $totalDays - $rental_days;
                
                    $extra_days_cost = round(($additional_daily_cost * $extra_days), 2);
                    
                    $total_new_cost_ = round(($total_new_cost_ + $base_price), 2);
                    
                    $total_new_cost_ = round(($total_new_cost_ + $extra_days_cost), 2);
                    
                    $minimumPrice = $total_new_cost_;
                }
                else{
                    $extra_days = 0;
                
                    $extra_days_cost = round(($additional_daily_cost * $extra_days), 2);
                    
                    $total_new_cost_ = round(($total_new_cost_ + $base_price), 2);
                    
                    $total_new_cost_ = round(($total_new_cost_ + $extra_days_cost), 2);
                    
                    $minimumPrice = $total_new_cost_;
                }
                
            }
            
            if( isset($multiple_charges_settings['environmental_charges']) && $multiple_charges_settings['environmental_charges'] > 0){
                //$environmental_charges_ = amount_with_commission($multiple_charges_settings['environmental_charges']);
                
                $environmental_charges_ = $base_price * ($multiple_charges_settings['environmental_charges'] / 100);
                $total_new_cost_ = round(($total_new_cost_ +  $environmental_charges_), 2);
                $minimumPrice = $total_new_cost_;
                
                $additional_charges_item_html .= '<li class="single-price-option ag-eq-booking-addtional-lineitem">
                          <span class="title">Environmental Charges
                            
                            <span class="text-danger">(<i class="fas fa-plus"></i>)</span> <span class="amount" dir="ltr">
                            '.( $position == 'left' ? $symbol : '').''.( $position == 'right' ? $symbol : '' ).'
                            <span dir="ltr">'.( $environmental_charges_).'</span></span></span>
                        </li>';
            }
            
            if(isset($request['live_load']) && $request['live_load'] == 'Yes' && isset($multiple_charges_settings['live_load_cost']) && $multiple_charges_settings['live_load_cost'] > 0){
                $live_load_cost_ = amount_with_commission($multiple_charges_settings['live_load_cost']);
                $total_new_cost_ = round(($total_new_cost_ + $live_load_cost_ ), 2);
                $minimumPrice = $total_new_cost_;
                
                $additional_charges_item_html .= '<li class="single-price-option ag-eq-booking-addtional-lineitem">
                          <span class="title">Live Load Cost
                            
                            <span class="text-danger">(<i class="fas fa-plus"></i>)</span> <span class="amount" dir="ltr">
                            '.( $position == 'left' ? $symbol : '').''.( $position == 'right' ? $symbol : '' ).'
                            <span dir="ltr">'.( $live_load_cost_).'</span></span></span>
                        </li>';
            }
            
            if(isset($request['is_emergency']) && $request['is_emergency'] == 'Yes' && isset($multiple_charges_settings['emergency_cost']) && $multiple_charges_settings['emergency_cost'] > 0){
                $emergency_cost_ = amount_with_commission($multiple_charges_settings['emergency_cost']);
                $total_new_cost_ = round(($total_new_cost_ + $emergency_cost_ ), 2);
                $minimumPrice = $total_new_cost_;
                
                $additional_charges_item_html .= '<li class="single-price-option ag-eq-booking-addtional-lineitem">
                          <span class="title">Expedited Services
                            
                            <span class="text-danger">(<i class="fas fa-plus"></i>)</span> <span class="amount" dir="ltr">
                            '.( $position == 'left' ? $symbol : '').''.( $position == 'right' ? $symbol : '' ).'
                            <span dir="ltr">'.( $emergency_cost_).'</span></span></span>
                        </li>';
            }
            

        }
        else if(is_equipment_temporary_toilet_type($details__->equipment_category_id)){
            $equipment_fields = EquipmentFieldsValue::where('equipment_id', $id)->first();

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
                
                $additional_charges_item_html .= '<li class="single-price-option ag-eq-booking-addtional-lineitem">
                          <span class="title">Extra Services
                            
                            <span class="text-danger">(<i class="fas fa-plus"></i>)</span> <span class="amount" dir="ltr">
                            '.( $position == 'left' ? $symbol : '').''.( $position == 'right' ? $symbol : '' ).'
                            <span dir="ltr">'.( $extra_services_total ).'</span></span></span>
                        </li>';
            }
            
            if(isset($request['type_of_rental']) && $request['type_of_rental'] != ''){
                
                if($request['type_of_rental'] == 'Long Term'){
                    $total_new_cost_ = round(($total_new_cost_ + $long_term_price), 2);
                    
                    $additional_charges_item_html .= '<li class="single-price-option ag-eq-booking-addtional-lineitem">
                          <span class="title">Long Term
                            
                            <span class="text-danger">(<i class="fas fa-plus"></i>)</span> <span class="amount" dir="ltr">
                            '.( $position == 'left' ? $symbol : '').''.( $position == 'right' ? $symbol : '' ).'
                            <span dir="ltr">'.( $long_term_price ).'</span></span></span>
                        </li>';
                }
                
                if($request['type_of_rental'] == 'Short Term'){
                    $total_new_cost_ = round(($total_new_cost_ + $short_term_price), 2);
                    
                    $additional_charges_item_html .= '<li class="single-price-option ag-eq-booking-addtional-lineitem">
                          <span class="title">Short Term
                            
                            <span class="text-danger">(<i class="fas fa-plus"></i>)</span> <span class="amount" dir="ltr">
                            '.( $position == 'left' ? $symbol : '').''.( $position == 'right' ? $symbol : '' ).'
                            <span dir="ltr">'.( $short_term_price ).'</span></span></span>
                        </li>';
                }
                
                if($request['type_of_rental'] == 'Special Event'){
                    $total_new_cost_ = round(($total_new_cost_ + $special_event_price), 2);
                    
                    $additional_charges_item_html .= '<li class="single-price-option ag-eq-booking-addtional-lineitem">
                          <span class="title">Special Event
                            
                            <span class="text-danger">(<i class="fas fa-plus"></i>)</span> <span class="amount" dir="ltr">
                            '.( $position == 'left' ? $symbol : '').''.( $position == 'right' ? $symbol : '' ).'
                            <span dir="ltr">'.( $special_event_price ).'</span></span></span>
                        </li>';
                }
                
                if($request['type_of_rental'] == 'Construction Event'){
                    $total_new_cost_ = round(($total_new_cost_ + $construction_event_price), 2);
                    
                    $additional_charges_item_html .= '<li class="single-price-option ag-eq-booking-addtional-lineitem">
                          <span class="title">Construction Event
                            
                            <span class="text-danger">(<i class="fas fa-plus"></i>)</span> <span class="amount" dir="ltr">
                            '.( $position == 'left' ? $symbol : '').''.( $position == 'right' ? $symbol : '' ).'
                            <span dir="ltr">'.( $construction_event_price ).'</span></span></span>
                        </li>';
                }
                
            }
            
            $minimumPrice = $total_new_cost_;
        }
        
        else if(is_equipment_storage_container_type($details__->equipment_category_id)){
            $equipment_fields = EquipmentFieldsValue::where('equipment_id', $id)->first();

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
                
                
                $minimumPrice = $total_new_cost_;
            }
            

        }
        // code by AG end

      // calculating shipping cost
      $shipping_cost__ = 0;
      $distance__ = '';
      $vendor_location = Location::where('vendor_id',$equipment->vendor_id)->where('equipment_category_id', $details__->equipment_category_id)->first();
        if(!empty($vendor_location)){
            $distance__ = $this->getDistance($request['lat__'], $request['long__'], $vendor_location->latitude, $vendor_location->longitude, 'mile');
            $distance__ = round($distance__,2);
            if($vendor_location->rate_type == 'flat_rate'){
                $shipping_cost__ = $vendor_location->charge;
            }
            if($vendor_location->rate_type == 'rate_by_distance'){
                if($request['lat__'] != '' && $request['long__'] != ''
                && $vendor_location->latitude != '' && $vendor_location->longitude != ''){
                    
                    $shipping_cost__ = round(($distance__ * $vendor_location->distance_rate), 2);
                }
                
            }
        }
      $request->session()->put('totalPrice', $minimumPrice);

      return response()->json(['minimumPrice' => $minimumPrice, 'additional_charges_item_html'=>$additional_charges_item_html, 'shipping_cost'=> $shipping_cost__,'distance__'=>$distance__]);
    } else {
      return response()->json(['errorMessage' => 'Sorry, equipment not found!']);
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

  public function isDecimal($value)
  {
    return is_numeric($value) && floor($value) != $value;
  }

  public function changeShippingMethod(Request $request)
  {
    $request->session()->put('shippingMethod', $request['shippingMethod']);

    return response()->json(['success' => 'Shipping method changed'], 200);
  }

  public function applyCoupon(Request $request)
  {
    if (empty($request->dateRange)) {
      return response()->json(['error' => 'First, fillup the booking dates.']);
    } else {
      try {
        $coupon = Coupon::where('code', $request->coupon)->firstOrFail();

        $startDate = Carbon::parse($coupon->start_date);
        $endDate = Carbon::parse($coupon->end_date);
        $todayDate = Carbon::now();

        // check coupon is valid or not
        if ($todayDate->between($startDate, $endDate) == false) {
          return response()->json(['error' => 'Sorry, This coupon has been expired!']);
        }

        // check coupon is valid or not for this equipment
        $equipmentId = $request->equipmentId;
        $equipmentIds = empty($coupon->equipments) ? '' : json_decode($coupon->equipments);

        if (!empty($equipmentIds) && !in_array($equipmentId, $equipmentIds)) {
          return response()->json(['error' => 'You can not apply this coupon for this equipment!']);
        }

        // else proceed
        $total = $request->session()->get('totalPrice');

        if ($coupon->type == 'fixed') {
          $request->session()->put('equipmentDiscount', $coupon->value);

          return response()->json([
            'success' => 'Coupon applied successfully.',
            'amount' => $coupon->value
          ]);
        } else {
          $couponAmount = $total * ($coupon->value / 100);

          $request->session()->put('equipmentDiscount', $couponAmount);

          return response()->json([
            'success' => 'Coupon applied successfully.',
            'amount' => $couponAmount
          ]);
        }
      } catch (ModelNotFoundException $e) {
        return response()->json(['error' => 'Coupon is not valid!']);
      }
    }
  }

  public function storeReview(Request $request, $id)
  {
    $rule = ['rating' => 'required'];

    $validator = Validator::make($request->all(), $rule);

    if ($validator->fails()) {
      return redirect()->back()
        ->with('error', 'The rating field is required for equipment review.')
        ->withInput();
    }

    $equipmentBooked = false;

    // get the authenticate user
    $user = Auth::guard('web')->user();

    // then, get the bookings of that user
    $bookings = $user->equipmentBooking()->where('payment_status', 'completed')->orderBy('id', 'desc')->get();

    $vendor_id = NULL;

    if (count($bookings) > 0) {
      foreach ($bookings as $booking) {
        if ($booking->equipment_id == $id) {
          $equipmentBooked = true;
          if ($booking->equipment_id == $id && $booking->vendor_id != NULL) {
            $vendor_id = $booking->vendor_id;
          }
          break;
        }
      }

      if ($equipmentBooked == true) {
        EquipmentReview::updateOrCreate(
          ['user_id' => $user->id, 'equipment_id' => $id],
          ['comment' => $request->comment, 'rating' => $request->rating, 'vendor_id' => $vendor_id]
        );

        if ($vendor_id != NULL) {
          $rating = EquipmentReview::where('vendor_id', $vendor_id)->avg('rating');
          Vendor::where('id', $vendor_id)->update([
            'avg_rating' => $rating
          ]);
        }

        Session::flash('success', 'Your review submitted successfully.');
      } else {
        Session::flash('error', 'You have not booked this equipment yet!');
      }
    } else {
      Session::flash('error', 'You have not booked anything yet!');
    }

    return redirect()->back();
  }
  
  public function request_a_quote_page(){
      $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['pageHeading'] = 'Request A Quote';

    $queryResult['bgImg'] = $misc->getBreadcrumb();
      return view('frontend.request-a-quote', $queryResult);
  }

  // code by AG start
  public function submit_quote(Request $request){
    $rules = [
        'first_name' => "required",
        'last_name' => "required",
        'email' => 'required|email',
        'phone' => "required",
        'company_name' => "required",
        'project_country' => "required",
        'project_city' => "required",
        'project_state' => "required",
        'project_zipcode' => "required",
        // 'equipment_needed' => "required",
    ];


    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator->errors())->withInput();
    }
    
    
    $in = $request->all();
	
	if(isset($in['equipment_needed']) && !empty($in['equipment_needed'])){
	    $in['equipment_needed'] = implode(', ', $in['equipment_needed']);
	}
	
	
	$in['customer_id'] = Auth::guard('web')->check() == true ? Auth::guard('web')->user()->id : null;
    
    $quote = EquipmentQuote::create($in);
	
	if(isset($request->equipment_id) && $request->equipment_id != ''){
	    $equipment = Equipment::findOrFail($request->equipment_id);
	
    	$receipent_email = '';
    	$vendor_ = array();
        if (!empty($equipment)) {
          if ($equipment->vendor_id != NULL) {
            $vendor_id = $equipment->vendor_id;
    		
    		$vendor_ = Vendor::find($vendor_id);
    		$receipent_email = $vendor_->email;
          } else {
            $vendor_id = NULL;
          }
        } else {
          $vendor_id = NULL;
    	}
    	$in['vendor_id'] = $vendor_id;
    	
    	$mailData['body'] = 'Hi ' . $vendor_->username . ',<br/><br/> You have received new quotation from<br/> Name: ' . $in['first_name'] . ' '.$in['last_name'].'<br/>Email: '.$in['email'].'<p> Quotation Id : #' . $quote->id . '</p>';
        
        $vendor_->notify(new BasicNotify('<a href="/vendor/equipment-quotations/'.$quote->id.'/details">'.$mailData['body'].'</a>'));
	}
	else{
	    $admin_ = Admin::find(1);
	    $admin_email = $admin_->email;
	    $receipent_email = $admin_email;
	    $mailData['body'] = '<a href="/admin/equipment-quotations/'. $quote->id .'/details">Hi admin,<br/><br/> You have received new quotation from<br/> Name: ' . $in['first_name'] . ' '.$in['last_name'].'<br/>Email: '.$in['email'].'</a>';


        $admin_->notify(new BasicNotify($mailData['body']));
	}
    
    
	
	if($receipent_email != ''){
		$mailData['subject'] = 'New Quotation Received';
        $mailData['body'] .= '<p> Quotation Id : #' . $quote->id . '</p>';

		
		$mailData['recipient'] = $receipent_email;

		//$mailData['sessionMessage'] = 'Quotation Received & mail has been sent successfully!';

		BasicMailer::sendMail($mailData);

	}
	
	

    Session::flash('success', 'Your Quote Has Been Sumitted Successfully!');
    return redirect()->back()->with('success', 'Your Quote Has Been Sumitted Successfully!');
  }
  // code by Ag end
}
