<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\Instrument\EquipmentStoreRequest;
use App\Http\Requests\Instrument\EquipmentUpdateRequest;
use App\Models\Instrument\Equipment;
use App\Models\Instrument\EquipmentContent;
use App\Models\Instrument\EquipmentLocation;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

use App\Models\EquipmentFieldsValue; // code by AG 
use App\Models\Instrument\EquipmentCategory; // code by AG
use Illuminate\Support\Facades\Http; // code by AG
use App\Http\Helpers\BasicMailer;

class EquipmentController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $language = Language::where('code', $request->language)->first();

    $information['langs'] = Language::all();

    $information['allEquipment'] = Equipment::query()->where('vendor_id', Auth::guard('vendor')->user()->id)->join('equipment_contents', 'equipments.id', '=', 'equipment_contents.equipment_id')
      ->join('equipment_categories', 'equipment_categories.id', '=', 'equipment_contents.equipment_category_id')
      ->where('equipment_contents.language_id', '=', $language->id)
      ->select('equipments.id', 'equipments.thumbnail_image', 'equipments.quantity', 'equipment_contents.title', 'equipment_contents.slug', 'equipment_categories.name as categoryName', 'equipments.is_featured')
      ->orderByDesc('equipments.id')
      ->get();

    return view('vendors.equipment.index', $information);
  }

  function allEquipmentData()
  {
    $language = Language::where('code', 'en')->first();

    $information['langs'] = Language::all();

    $information['allEquipment'] = Equipment::query()->where('vendor_id', Auth::guard('vendor')->user()->id)->join('equipment_contents', 'equipments.id', '=', 'equipment_contents.equipment_id')
      ->join('equipment_categories', 'equipment_categories.id', '=', 'equipment_contents.equipment_category_id')
      ->where('equipment_contents.language_id', '=', $language->id)
      ->select('equipments.id', 'equipments.thumbnail_image', 'equipments.quantity', 'equipment_contents.title', 'equipment_contents.slug', 'equipment_categories.name as categoryName', 'equipments.is_featured')
      ->orderByDesc('equipments.id')
      ->get();

    return response()->json(['data' => $information]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $information['currencyInfo'] = $this->getCurrencyInfo();

    $languages = Language::all();

    $languages->map(function ($language) {
      $language['categories'] = $language->equipmentCategory()->where('status', 1)->orderByDesc('id')->get();
    });

    $information['languages'] = $languages;

    return view('vendors.equipment.create', $information);
  }

  /**
   * Store a new slider image in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function uploadImage(Request $request)
  {
    $rules = [
      'slider_image' => new ImageMimeTypeRule()
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'error' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $imageName = UploadFile::store(public_path('assets/img/equipments/slider-images/'), $request->file('slider_image'));

    return Response::json(['uniqueName' => $imageName], 200);
  }

  /**
   * Remove a slider image from storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function removeImage(Request $request)
  {
    if (empty($request['imageName'])) {
      return Response::json(['error' => 'The request has no file name.'], 400);
    } else {
      @unlink(public_path('assets/img/equipments/slider-images/') . $request['imageName']);

      return Response::json(['success' => 'The file has been deleted.'], 200);
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(EquipmentStoreRequest $request)
  {
    $thumbnailImgName = '';
    if ($request->hasFile('thumbnail_image')) {
      // store thumbnail image in storage
      $thumbnailImgName = UploadFile::store(public_path('assets/img/equipments/thumbnail-images/'), $request->file('thumbnail_image'));
    }

    // get the lowest price
    $priceBtnStatus = $request['price_btn_status'];

    if ($priceBtnStatus == 0) {
      $prices = [];

      array_push($prices, $request->per_day_price);
      array_push($prices, $request->per_week_price);
      array_push($prices, $request->per_month_price);

      $priceArr = array_diff($prices, array(null));
      $lowestPrice = min($priceArr);
    }

    // code by AG start  
    $equipment_fields__ = $request->equipment_fields_value ?? array();
    $multiple_charges_settings = $request->multiple_charges_settings ?? array();
    // code by AG end
    if ($request->multiple_charges_settings) {
      $base_price = $request->multiple_charges_settings['base_price'] ? $request->multiple_charges_settings['base_price'] : '';
    } else {
      $base_price = "";
    }

    // store data in db
    $equipment = Equipment::create($request->except('thumbnail_image', 'slider_images', 'lowest_price', 'equipment_fields_value', 'multiple_charges_settings') + [
      'thumbnail_image' => $thumbnailImgName,
      'vendor_id' => Auth::guard('vendor')->user()->id,
      'slider_images' => json_encode($request['slider_images']),
      'lowest_price' => $priceBtnStatus == 0 ? $lowestPrice : null,
      'base_price' => $base_price,
      'location_id' => $request->location_id
    ]);

    // code by AG start
    $eq_fields = new EquipmentFieldsValue();
    $eq_fields->equipment_id = $equipment->id;
    $eq_fields->fields_value = json_encode($equipment_fields__);
    $eq_fields->multiple_charges_settings = json_encode($multiple_charges_settings);
    $eq_fields->save();
    // code by AG end

    $languages = Language::all();

    foreach ($languages as $language) {
      $equipmentContent = new EquipmentContent();
      $equipmentContent->language_id = $language->id;
      $equipmentContent->equipment_category_id = $request[$language->code . '_category_id'];
      $equipmentContent->equipment_id = $equipment->id;
      $equipmentContent->title = $request[$language->code . '_title'];
      $equipmentContent->slug = createSlug($request[$language->code . '_title']);
      $equipmentContent->features = $request[$language->code . '_features'];
      $equipmentContent->description = Purifier::clean($request[$language->code . '_description']);
      $equipmentContent->meta_keywords = $request[$language->code . '_meta_keywords'];
      $equipmentContent->meta_description = $request[$language->code . '_meta_description'];
      $equipmentContent->save();

      // commented by AG start
      /*$location_ids = $request[$language->code . '_location_ids'];
            foreach ($location_ids as $location_id) {
                $eq_location = new EquipmentLocation();
                $eq_location->language_id = $language->id;
                $eq_location->equipment_id = $equipment->id;
                $eq_location->location_id = $location_id;
                $eq_location->save();
            }*/
      // commented by AG end
    }

    // code by AG start
    // pushing to invoice system 

    $category_id__ = $request['en_category_id'];
    $category__name = '';
    $category__ = EquipmentCategory::find($category_id__);
    if (!empty($category__)) {
      $category__name = $category__->name;
    }

    $response = Http::post(env('INVOICE_SYSTEM_URL') . 'api/products', [
      'name' => $request['en_title'],
      'category' => $category__name,
      //'code' => $in['email'],
      'unit_price' => 0,
      'vendor_email' => Auth::guard('vendor')->user()->email
    ]);

    $jsonData = $response->json();
    $msg = '';
    if ($response->successful()) {
      $msg = 'And Equipment Pushed to Invoice System Successfully!';
    }
    // code by AG end


    $mailData['subject'] = 'Notification: New Equipment Added to Vendor Profile';

    $mailData['body'] = 'Dear ' . Auth::guard('vendor')->user()->username . ',<br><br>';
    $mailData['body'] .= "We hope this email finds you well. <br><br>";
    $mailData['body'] .= "We would like to inform you that new equipment has been added to your vendor profile. Below are the details: <br><br>";
    $mailData['body'] .= "<ul><li>Equipment Type: " . $category__->name . "</li><li>Equipment Details: " . $equipmentContent->description . "</li></ul><br><br>";
    $mailData['body'] .= "If you have any questions or need further assistance, please don’t hesitate to contact us.<br><br>";
    $mailData['body'] .= "Best regards,<br>";
    $mailData['body'] .= "CAT Dump";

    $mailData['recipient'] = Auth::guard('vendor')->user()->email;


    BasicMailer::sendMail($mailData);
    Session::flash('success', 'New equipment added successfully!' . ' ' . $msg);

    return Response::json(['status' => 'success'], 200);
  }

  /**
   * Update featured status of a specified resource.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function updateFeatured(Request $request, $id)
  {
    $equipment = Equipment::find($id);

    if ($request['is_featured'] == 'yes') {
      $equipment->update(['is_featured' => 'yes']);

      Session::flash('success', 'Equipment featured successfully!');
    } else {
      $equipment->update(['is_featured' => 'no']);

      Session::flash('success', 'Equipment unfeatured successfully!');
    }

    return redirect()->back();
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $equipment = Equipment::find($id);
    if ($equipment) {
      if ($equipment->vendor_id != Auth::guard('vendor')->user()->id) {
        return redirect()->route('vendor.dashboard');
      }
    } else {
      return redirect()->route('vendor.dashboard');
    }


    $information['equipment'] = $equipment;

    // get the currency information from db
    $information['currencyInfo'] = $this->getCurrencyInfo();

    // get all the languages from db
    $languages = Language::all();

    $languages->map(function ($language) use ($equipment) {
      // get equipment information of each language from db
      $language['equipmentData'] = $language->equipmentContent()->where('equipment_id', $equipment->id)->first();

      // get all the categories of each language from db
      $language['categories'] = $language->equipmentCategory()->where('status', 1)->orderByDesc('id')->get();
    });

    $information['languages'] = $languages;


    // code by AG start
    $equipment_fields = EquipmentFieldsValue::where('equipment_id', $id)->first();

    if ($equipment_fields) {
      $information['equipment_fields'] = json_decode($equipment_fields->fields_value, true);

      $information['multiple_charges_settings'] = json_decode($equipment_fields->multiple_charges_settings, true);
    } else {
      $information['equipment_fields'] = array();
      $information['multiple_charges_settings'] = array();
    }

    $fields_html = '<div class="row equipment-fields-box">';
    if (!empty($information['equipment_fields'])) {
      foreach ($information['equipment_fields'] as $key => $equipment_field_) {
        if ($equipment_field_['type'] == 'Text') {
          $text_field = '<div class="col-md-12">
				<div class="form-group">
				  <label>' . $equipment_field_['name'] . '</label>
				  <input type="text" value="' . $equipment_field_['value'] . '" name="equipment_fields_value[' . $key . '][value]" class="form-control">
				  <input type="hidden" value="' . $equipment_field_['type'] . '" name="equipment_fields_value[' . $key . '][type]" class="form-control">
				  <input type="hidden" value="' . $equipment_field_['name'] . '" name="equipment_fields_value[' . $key . '][name]" class="form-control">
				  <input type="hidden" value="' . $equipment_field_['options'] . '" name="equipment_fields_value[' . $key . '][options]" class="form-control">
				</div>
			  </div>';
          $fields_html .= $text_field;
        }

        if ($equipment_field_['type'] == 'Dropdown') {
          $options__ = explode(',', $equipment_field_['options']);
          $options__html = '';
          if (!empty($options__)) {
            foreach ($options__ as $o) {
              $options__html .= '<option ' . (($equipment_field_['value'] == $o) ? 'selected' : '') . ' value="' . $o . '">' . $o . '</option>';
            }
          }
          $dropdown_field = '<div class="col-md-12">
				<div class="form-group">
				  <label>' . $equipment_field_['name'] . '</label>
				  <select name="equipment_fields_value[' . $key . '][value]" class="form-control">' . $options__html . '</select>
				  
				  <input type="hidden" value="' . $equipment_field_['type'] . '" name="equipment_fields_value[' . $key . '][type]" class="form-control">
				  <input type="hidden" value="' . $equipment_field_['name'] . '" name="equipment_fields_value[' . $key . '][name]" class="form-control">
				  <input type="hidden" value="' . $equipment_field_['options'] . '" name="equipment_fields_value[' . $key . '][options]" class="form-control">
				</div>
			  </div>';

          $fields_html .= $dropdown_field;
        }

        if ($equipment_field_['type'] == 'Price') {
          $currencyInfo = $this->getCurrencyInfo();
          $currencyText = $currencyInfo->base_currency_text;
          $price_field = '<div class="col-md-12">
				<div class="form-group">
				  <label>' . $equipment_field_['name'] . ' ( ' . $currencyText . ' )</label>
				  <input type="number" value="' . $equipment_field_['value'] . '" min="0" step=".01" name="equipment_fields_value[' . $key . '][value]" class="form-control">
				  <input type="hidden" value="' . $equipment_field_['type'] . '" name="equipment_fields_value[' . $key . '][type]" class="form-control">
				  <input type="hidden" value="' . $equipment_field_['name'] . '" name="equipment_fields_value[' . $key . '][name]" class="form-control">
				  <input type="hidden" value="' . $equipment_field_['options'] . '" name="equipment_fields_value[' . $key . '][options]" class="form-control">
				</div>
			  </div>';

          $fields_html .= $price_field;
        }
      }
    }

    $multiple_charges_settings_html = '<div class="col-md-12">
            <div class="form-group">
              <h4>Price Settings</h4>
            </div>
          </div><div class="col-md-12">
            <div class="form-group">
              <label>Rental Days</label>
              <input type="number" min="0" step="1" id="rental_days" name="multiple_charges_settings[rental_days]" value="' . ($information['multiple_charges_settings']['rental_days'] ?? 1) . '" class="form-control">
              
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label>Live Load</label>
              <select name="multiple_charges_settings[live_load]" id="live_load" class="form-control">
              <option value="Yes" ' . ((isset($information['multiple_charges_settings']['live_load']) && $information['multiple_charges_settings']['live_load'] == "Yes") ? 'selected' : '') . '>Yes</option>
              <option value="No" ' . ((isset($information['multiple_charges_settings']['live_load']) && $information['multiple_charges_settings']['live_load'] == "No") ? 'selected' : '') . '>No</option>
              </select>
              
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label>Ton</label>
              <select name="multiple_charges_settings[allowed_ton]" class="form-control">
              <option value="1ton" ' . ((isset($information['multiple_charges_settings']['allowed_ton']) && $information['multiple_charges_settings']['allowed_ton'] == "1ton") ? 'selected' : '') . '>1ton</option>
              <option value="2ton" ' . ((isset($information['multiple_charges_settings']['allowed_ton']) && $information['multiple_charges_settings']['allowed_ton'] == "2ton") ? 'selected' : '') . '>2ton</option>
              <option value="3ton" ' . ((isset($information['multiple_charges_settings']['allowed_ton']) && $information['multiple_charges_settings']['allowed_ton'] == "3ton") ? 'selected' : '') . '>3ton</option>
              <option value="4ton" ' . ((isset($information['multiple_charges_settings']['allowed_ton']) && $information['multiple_charges_settings']['allowed_ton'] == "4ton") ? 'selected' : '') . '>4ton</option>
              <option value="5ton" ' . ((isset($information['multiple_charges_settings']['allowed_ton']) && $information['multiple_charges_settings']['allowed_ton'] == "5ton") ? 'selected' : '') . '>5ton</option>
              <option value="6ton" ' . ((isset($information['multiple_charges_settings']['allowed_ton']) && $information['multiple_charges_settings']['allowed_ton'] == "6ton") ? 'selected' : '') . '>6ton</option>
              <option value="7ton" ' . ((isset($information['multiple_charges_settings']['allowed_ton']) && $information['multiple_charges_settings']['allowed_ton'] == "7ton") ? 'selected' : '') . '>7ton</option>
              <option value="8ton" ' . ((isset($information['multiple_charges_settings']['allowed_ton']) && $information['multiple_charges_settings']['allowed_ton'] == "8ton") ? 'selected' : '') . '>8ton</option>
              <option value="9ton" ' . ((isset($information['multiple_charges_settings']['allowed_ton']) && $information['multiple_charges_settings']['allowed_ton'] == "9ton") ? 'selected' : '') . '>9ton</option>
              </select>
            </div>
          </div><div class="col-md-12">
            <div class="form-group">
              <label>Base Price</label>
              <input type="number" min="0" step=".01" id="base_price" name="multiple_charges_settings[base_price]" value="' . ($information['multiple_charges_settings']['base_price'] ?? 0) . '" class="form-control">
              
            </div>
          </div><div class="col-md-12">
            <div class="form-group">
              <label>Additional Daily cost after Rental period ( USD )</label>
              <input type="number" id="additional_daily_cost" min="0" step=".01" name="multiple_charges_settings[additional_daily_cost]" value="' . ($information['multiple_charges_settings']['additional_daily_cost'] ?? 0) . '" class="form-control">
              
            </div>
          </div><div class="col-md-12">
            <div class="form-group">
              <label>Environmental Charges ( % )</label>
              <input type="number" id="environmental_charges" min="0" step=".01" name="multiple_charges_settings[environmental_charges]" value="' . ($information['multiple_charges_settings']['environmental_charges'] ?? 0) . '" class="form-control">
              
            </div>
          </div><div class="col-md-12">
            <div class="form-group">
              <label>Expedited Services ( USD )</label>
              <input type="number" id="emergency_cost" min="0" step=".01" name="multiple_charges_settings[emergency_cost]" value="' . ($information['multiple_charges_settings']['emergency_cost'] ?? 0) . '" class="form-control">
              
            </div>
          </div><div class="col-md-12">
            <div class="form-group">
              <label>Relocation Fee ( USD )</label>
              <input type="number" id="relocation_fee" min="0" step=".01" name="multiple_charges_settings[relocation_fee]" value="' . ($information['multiple_charges_settings']['relocation_fee'] ?? 0) . '" class="form-control">
              
            </div>
          </div><div class="col-md-12">
            <div class="form-group">
              <label>If yes Live Load Cost ( USD )</label>
              <input type="number" id="live_load_cost" min="0" step=".01" name="multiple_charges_settings[live_load_cost]" value="' . ($information['multiple_charges_settings']['live_load_cost'] ?? 0) . '" class="form-control">
              
            </div>
          </div><div class="col-md-12">
            <div class="form-group">
              <label>Additional Tonnage Cost Per Tonne ( USD )</label>
              <input type="number" id="additional_tonnage_cost" min="0" step=".01" name="multiple_charges_settings[additional_tonnage_cost]" value="' . ($information['multiple_charges_settings']['additional_tonnage_cost'] ?? 0) . '" class="form-control">
              
            </div>
          </div><div class="col-md-12">
            <div class="form-group">
              <label>Swap Charge ( USD )</label>
              <input type="number" id="swap_charge" min="0" step=".01" name="multiple_charges_settings[swap_charge]" value="' . ($information['multiple_charges_settings']['swap_charge'] ?? 0) . '" class="form-control">
              
            </div>
          </div>';

    $temporary_toilet_charges_settings_html = '<div class="col-md-12">
            <div class="form-group">
              <h4>Price Settings</h4>
            </div>
          </div>
          
          <div class="col-md-12">
            <div class="form-group">
              <label>Services per week</label>
              <select id="services_per_week" name="multiple_charges_settings[services_per_week]" class="form-control">
              <option ' . (isset($information['multiple_charges_settings']['services_per_week']) && $information['multiple_charges_settings']['services_per_week'] == "1" ? 'selected' : '') . ' value="1">1</option>
              <option ' . (isset($information['multiple_charges_settings']['services_per_week']) && $information['multiple_charges_settings']['services_per_week'] == "2" ? 'selected' : '') . ' value="2">2</option>
              <option ' . (isset($information['multiple_charges_settings']['services_per_week']) && $information['multiple_charges_settings']['services_per_week'] == "3" ? 'selected' : '') . ' value="3">3</option>
              </select>
            </div>
          </div>
          
          <div class="col-md-12">
            <div class="form-group">
              <label>Rental Days Included</label>
              <select id="rental_days_included" name="multiple_charges_settings[rental_days_included]" class="form-control">
              <option ' . (isset($information['multiple_charges_settings']['rental_days_included']) && $information['multiple_charges_settings']['rental_days_included'] == "28" ? 'selected' : '') . ' value="28">28</option>
              <option ' . (isset($information['multiple_charges_settings']['rental_days_included']) && $information['multiple_charges_settings']['rental_days_included'] == "Monthly" ? 'selected' : '') . ' value="Monthly">Monthly</option>
              </select>
            </div>
          </div>
          
          
          <div class="col-md-12">
            <div class="form-group">
              <label>Base Price</label>
              <input value="' . ($information['multiple_charges_settings']['base_price'] ?? 0) . '" type="number" min="0" step=".01" id="base_price-cd-temporary-toilet-cat" name="multiple_charges_settings[base_price]" class="form-control">
              
            </div>
          </div>
          
          <div class="col-md-12">
            <div class="form-group">
              <label>Additional Service Cost ( USD )</label>
              <input value="' . ($information['multiple_charges_settings']['additional_service_cost'] ?? 0) . '" type="number" id="additional_service_cost" min="0" step=".01" name="multiple_charges_settings[additional_service_cost]" class="form-control">
              
            </div>
          </div>
          
          <div class="col-md-12">
            <div class="form-group">
              <label>Relocation Fee ( USD )</label>
              <input type="number" id="relocation_fee" min="0" step=".01" name="multiple_charges_settings[relocation_fee]" value="' . ($information['multiple_charges_settings']['relocation_fee'] ?? 0) . '" class="form-control">
              
            </div>
          </div>
          
          <div class="col-md-12">
            <div class="form-group">
              <label>Special Event Price ( USD )</label>
              <input value="' . ($information['multiple_charges_settings']['special_event_price'] ?? 0) . '" type="number" id="special_event_price" min="0" step=".01" name="multiple_charges_settings[special_event_price]" class="form-control">
              
            </div>
          </div>
          
          <div class="col-md-12">
            <div class="form-group">
              <label>Short Term Price ( USD )</label>
              <input value="' . ($information['multiple_charges_settings']['short_term_price'] ?? 0) . '" type="number" id="short_term_price" min="0" step=".01" name="multiple_charges_settings[short_term_price]" class="form-control">
              
            </div>
          </div>
          
          <div class="col-md-12">
            <div class="form-group">
              <label>Long Term Price ( USD )</label>
              <input value="' . ($information['multiple_charges_settings']['long_term_price'] ?? 0) . '" type="number" id="long_term_price" min="0" step=".01" name="multiple_charges_settings[long_term_price]" class="form-control">
              
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label>Construction Event Price ( USD )</label>
              <input value="' . ($information['multiple_charges_settings']['construction_event_price'] ?? 0) . '" type="number" id="construction_event_price" min="0" step=".01" name="multiple_charges_settings[construction_event_price]" class="form-control">
              
            </div>
          </div>';

    $storage_container_charges_settings_html = '<div class="col-md-12">
            <div class="form-group">
              <h4>Price Settings</h4>
            </div>
          </div><div class="col-md-12">
            <div class="form-group">
              <label>Rental Days</label>
              <input type="number" min="0" step="1" id="rental_days" name="multiple_charges_settings[rental_days]" value="' . ($information['multiple_charges_settings']['rental_days'] ?? 1) . '" class="form-control">
              
            </div>
          </div><div class="col-md-12">
            <div class="form-group">
              <label>Base Price</label>
              <input type="number" min="0" step=".01" id="base_price" name="multiple_charges_settings[base_price]" value="' . ($information['multiple_charges_settings']['base_price'] ?? 0) . '" class="form-control">
              
            </div>
          </div>';

    $fields_html .= '</div>';
    $information['fields_html'] = $fields_html;
    $information['multiple_charges_settings_html'] = $multiple_charges_settings_html;

    $information['temporary_toilet_charges_settings_html'] = $temporary_toilet_charges_settings_html;

    $information['storage_container_charges_settings_html'] = $storage_container_charges_settings_html;
    // code by AG end

    return view('vendors.equipment.edit', $information);
  }

  /**
   * Remove 'stored' slider image form storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function detachImage(Request $request)
  {
    $id = $request['id'];
    $key = $request['key'];

    $equipment = Equipment::find($id);

    if (empty($equipment)) {
      return Response::json(['message' => 'Equipment not found!'], 400);
    } else {
      $sliderImages = json_decode($equipment->slider_images);

      if (count($sliderImages) == 1) {
        return Response::json(['message' => 'Sorry, the last image cannot be delete.'], 400);
      } else {
        $image = $sliderImages[$key];

        @unlink(public_path('assets/img/equipments/slider-images/') . $image);

        array_splice($sliderImages, $key, 1);

        $equipment->update([
          'slider_images' => json_encode($sliderImages)
        ]);

        return Response::json(['message' => 'Slider image removed successfully!'], 200);
      }
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(EquipmentUpdateRequest $request, $id)
  {
    $equipment = Equipment::find($id);

    // store thumbnail image in storage
    if ($request->hasFile('thumbnail_image')) {
      $newImage = $request->file('thumbnail_image');
      $oldImage = $equipment->thumbnail_image;
      $thumbnailImgName = UploadFile::update(public_path('assets/img/equipments/thumbnail-images/'), $newImage, $oldImage);
    }

    // merge slider images with existing images if request has new slider image
    if ($request->filled('slider_images')) {
      $prevImages = json_decode($equipment->slider_images);
      $newImages = $request['slider_images'];
      $imgArr = array_merge($prevImages, $newImages);
    }

    // get the lowest price
    $priceBtnStatus = $request['price_btn_status'];

    if ($priceBtnStatus == 0) {
      $prices = [];

      array_push($prices, $request->per_day_price);
      array_push($prices, $request->per_week_price);
      array_push($prices, $request->per_month_price);

      $priceArr = array_diff($prices, array(null));
      $lowestPrice = min($priceArr);
    }

    // code by AG start  
    $equipment_fields__ = $request->equipment_fields_value ?? array();

    $multiple_charges_settings = $request->multiple_charges_settings ?? array();

    // code by AG end

    // store data in db
    $equipment->update($request->except('thumbnail_image', 'slider_images', 'lowest_price', 'equipment_fields_value', 'multiple_charges_settings') + [
      'thumbnail_image' => $request->hasFile('thumbnail_image') ? $thumbnailImgName : $equipment->thumbnail_image,
      'vendor_id' => Auth::guard('vendor')->user()->id,
      'slider_images' => isset($imgArr) ? json_encode($imgArr) : $equipment->slider_images,
      'lowest_price' => $priceBtnStatus == 0 ? $lowestPrice : null
    ]);

    // code by AG start
    $eq_fields = EquipmentFieldsValue::where('equipment_id', $id)->first();
    if ($eq_fields) {
      $eq_fields = EquipmentFieldsValue::find($eq_fields->id);
      $eq_fields->fields_value = json_encode($equipment_fields__);
      $eq_fields->multiple_charges_settings = json_encode($multiple_charges_settings);
      $eq_fields->save();
    } else {
      $eq_fields = new EquipmentFieldsValue();
      $eq_fields->equipment_id = $id;
      $eq_fields->fields_value = json_encode($equipment_fields__);
      $eq_fields->multiple_charges_settings = json_encode($multiple_charges_settings);
      $eq_fields->save();
    }

    $equipment_old_title = '';

    // code by AG end

    $languages = Language::all();

    foreach ($languages as $language) {
      $equipmentContent = EquipmentContent::where('equipment_id', $id)->where('language_id', $language->id)->first();

      if ($language->is_default == 1) {
        $equipment_old_title = $equipmentContent->title;
      }

      if ($equipmentContent) {
        $equipmentContent->update([
          'equipment_category_id' => $request[$language->code . '_category_id'],
          'title' => $request[$language->code . '_title'],
          'slug' => createSlug($request[$language->code . '_title']),
          'features' => $request[$language->code . '_features'],
          'description' => Purifier::clean($request[$language->code . '_description']),
          'meta_keywords' => $request[$language->code . '_meta_keywords'],
          'meta_description' => $request[$language->code . '_meta_description']
        ]);
      } else {
        EquipmentContent::create([
          'language_id' => $language->id,
          'equipment_id' => $equipment->id,
          'equipment_category_id' => $request[$language->code . '_category_id'],
          'title' => $request[$language->code . '_title'],
          'slug' => createSlug($request[$language->code . '_title']),
          'features' => $request[$language->code . '_features'],
          'description' => Purifier::clean($request[$language->code . '_description']),
          'meta_keywords' => $request[$language->code . '_meta_keywords'],
          'meta_description' => $request[$language->code . '_meta_description']
        ]);
      }

      //first delete data
      $locations = EquipmentLocation::where([['language_id', $language->id], ['equipment_id', $equipment->id]])->get();
      foreach ($locations as $location) {
        $location->delete();
      }

      // commented by AG start
      //second store data
      /*$location_ids = $request[$language->code . '_location_ids'];
            foreach ($location_ids as $location_id) {
                $eq_location = new EquipmentLocation();
                $eq_location->language_id = $language->id;
                $eq_location->equipment_id = $equipment->id;
                $eq_location->location_id = $location_id;
                $eq_location->save();
            }*/
      // commented by AG start
    }

    // code by AG start
    // pushing to invoice system 

    $category_id__ = $request['en_category_id'];
    $category__name = '';
    $category__ = EquipmentCategory::find($category_id__);
    if (!empty($category__)) {
      $category__name = $category__->name;
    }



    $response = Http::put(env('INVOICE_SYSTEM_URL') . 'api/products/' . $equipment_old_title, [
      'name' => $request['en_title'],
      'category' => $category__name,
      //'code' => $in['email'],
      //'unit_price' => 0,
      'vendor_email' => Auth::guard('vendor')->user()->email
    ]);


    $jsonData = $response->json();

    $msg = '';
    if ($response->successful()) {
      $msg = 'And Equipment Update Pushed to Invoice System Successfully!';
    }
    // code by AG end

    Session::flash('success', 'Equipment updated successfully!' . ' ' . $msg);

    return Response::json(['status' => 'success'], 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $equipment = Equipment::find($id);

    // delete the thumbnail image
    @unlink(public_path('assets/img/equipments/thumbnail-images/') . $equipment->thumbnail_image);

    // delete the slider images
    $sliderImages = json_decode($equipment->slider_images);

    $sliderImages = (empty($sliderImages)) ? array() : $sliderImages;
    if (!empty($sliderImages)) {
      foreach ($sliderImages as $sliderImage) {
        @unlink(public_path('assets/img/equipments/slider-images/') . $sliderImage);
      }
    }


    // delete the equipment contents
    $equipmentContents = $equipment->content()->get();

    foreach ($equipmentContents as $equipmentContent) {
      $equipmentContent->delete();
    }

    // delete all the bookings of this equipment
    $bookings = $equipment->booking()->get();

    if (count($bookings) > 0) {
      foreach ($bookings as $booking) {
        @unlink(public_path('assets/file/attachments/equipment/') . $booking->attachment);

        @unlink(public_path('assets/file/invoices/equipment/') . $booking->invoice);

        $booking->delete();
      }
    }

    // delete all the reviews of this equipment
    $equipmentReviews = $equipment->review()->get();

    if (count($equipmentReviews) > 0) {
      foreach ($equipmentReviews as $equipmentReview) {
        $equipmentReview->delete();
      }
    }

    $equipment->delete();

    return redirect()->back()->with('success', 'Equipment deleted successfully!');
  }

  /**
   * Remove the selected or all resources from storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $equipment = Equipment::find($id);

      // delete the thumbnail image
      @unlink(public_path('assets/img/equipments/thumbnail-images/') . $equipment->thumbnail_image);

      // delete the slider images
      $sliderImages = json_decode($equipment->slider_images);

      $sliderImages = (empty($sliderImages)) ? array() : $sliderImages;
      if (!empty($sliderImages)) {
        foreach ($sliderImages as $sliderImage) {
          @unlink(public_path('assets/img/equipments/slider-images/') . $sliderImage);
        }
      }

      // delete the equipment contents
      $equipmentContents = $equipment->content()->get();

      foreach ($equipmentContents as $equipmentContent) {
        $equipmentContent->delete();
      }

      // delete all the bookings of this equipment
      $bookings = $equipment->booking()->get();

      if (count($bookings) > 0) {
        foreach ($bookings as $booking) {
          @unlink(public_path('assets/file/attachments/equipment/') . $booking->attachment);

          @unlink(public_path('assets/file/invoices/equipment/') . $booking->invoice);

          $booking->delete();
        }
      }

      // delete all the reviews of this equipment
      $equipmentReviews = $equipment->review()->get();

      if (count($equipmentReviews) > 0) {
        foreach ($equipmentReviews as $equipmentReview) {
          $equipmentReview->delete();
        }
      }

      $equipment->delete();
    }

    Session::flash('success', 'Equipments deleted successfully!');

    return Response::json(['status' => 'success'], 200);
  }
}
