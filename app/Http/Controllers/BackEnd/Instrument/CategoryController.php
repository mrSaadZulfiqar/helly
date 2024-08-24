<?php

namespace App\Http\Controllers\BackEnd\Instrument;

use App\Http\Controllers\Controller;
use App\Models\Instrument\EquipmentCategory;
use App\Models\Language;

use App\Models\EquipmentField; // code by AG
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\VendorSetting; // code by AG
use Auth;

class CategoryController extends Controller
{
  public function index(Request $request)
  {
    // first, get the language info from db
    $language = Language::where('code', $request->language)->first();
    $information['language'] = $language;

    // then, get the equipment categories of that language from db
    $information['categories'] = $language->equipmentCategory()->orderByDesc('id')->get();

    // also, get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.instrument.category.index', $information);
  }

  public function store(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'name' => 'required|unique:equipment_categories|max:255',
      'status' => 'required|numeric',
      'serial_number' => 'required|numeric'
    ];

    $message = [
      'language_id.required' => 'The language field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    // code by AG start
    $in = array();
    $file = $request->file('placeholder_img');
    if ($file) {
        $extension = $file->getClientOriginalExtension();
        $directory = public_path('assets/admin/img/category-images/');
        $fileName = uniqid() . '.' . $extension;
        @mkdir($directory, 0775, true);
        $file->move($directory, $fileName);
        $in['placeholder_img'] = $fileName;
    }

    EquipmentCategory::create($request->except(['slug','placeholder_img']) + $in + [
      'slug' => createSlug($request->name)
    ]);

    // code by AG end

    // EquipmentCategory::create($request->except('slug') + [
    //   'slug' => createSlug($request->name)
    // ]); // commented by AG

    Session::flash('success', 'New equipment category added successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function update(Request $request)
  {
    $rules = [
      'name' => [
        'required',
        'max:255',
        Rule::unique('equipment_categories', 'name')->ignore($request->id, 'id')
      ],
      'status' => 'required|numeric',
      'serial_number' => 'required|numeric'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    // code by AG start
    $in = array();
    $file = $request->file('placeholder_img');
    if ($file) {
        $extension = $file->getClientOriginalExtension();
        $directory = public_path('assets/admin/img/category-images/');
        $fileName = uniqid() . '.' . $extension;
        @mkdir($directory, 0775, true);
        $file->move($directory, $fileName);
        $in['placeholder_img'] = $fileName;
    }

    if(isset($request->request_for_price)){
      $in['request_for_price'] = $request->request_for_price;
    }else{
      $in['request_for_price'] = 0;
    }
    
    if(isset($request->multiple_charges)){
      $in['multiple_charges'] = $request->multiple_charges;
    }else{
      $in['multiple_charges'] = 0;
    }

    $category = EquipmentCategory::find($request->id);

    $category->update($request->except(['slug','placeholder_img','request_for_price', 'multiple_charges']) + $in + [
      'slug' => createSlug($request->name)
    ]);

    // code by AG end

    // $category = EquipmentCategory::find($request->id);

    // $category->update($request->except('slug') + [
    //   'slug' => createSlug($request->name)
    // ]); // commented by AG

    Session::flash('success', 'Equipment category updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $category = EquipmentCategory::find($id);
    $equipmentContents = $category->equipmentContent()->get();

    if (count($equipmentContents) > 0) {
      return redirect()->back()->with('warning', 'First delete all the equipments of this category!');
    } else {
      $category->delete();

      return redirect()->back()->with('success', 'Category deleted successfully!');
    }
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    $errorOccured = false;

    foreach ($ids as $id) {
      $category = EquipmentCategory::find($id);
      $equipmentContents = $category->equipmentContent()->get();

      if (count($equipmentContents) > 0) {
        $errorOccured = true;
        break;
      } else {
        $category->delete();
      }
    }

    if ($errorOccured == true) {
      Session::flash('warning', 'First delete all the equipment of these categories!');
    } else {
      Session::flash('success', 'Equipment categories deleted successfully!');
    }

    return Response::json(['status' => 'success'], 200);
  }

  // code by AG start
  public function edit_equipment_fields( $id ){
    //edit_equipment_fields.blade.php
    $category = EquipmentCategory::find($id);
    $equipment_fields = EquipmentField::where('category_id', $id)->first();
    $equipment_fields_array = array();
    if($equipment_fields){
      $equipment_fields_array = json_decode($equipment_fields->fields, true);
    }
    $information = array();
    $information['category'] = $category;
    $information['equipment_fields'] = $equipment_fields_array;
    
    return view('backend.instrument.category.edit_equipment_fields', $information);
  }

  public function update_equipment_fields(Request $request, $id ){
    //edit_equipment_fields.blade.php
    $fields_ = $request->equipment_fields;
    
    $equipment_fields = EquipmentField::where('category_id', $id)->first();

    if($equipment_fields){
        $equipment_fields = EquipmentField::find($equipment_fields->id);
        $equipment_fields->fields = json_encode($fields_);
        $equipment_fields->save();
    }else{
        $equipment_fields = new EquipmentField();
        $equipment_fields->category_id = $id;
        $equipment_fields->fields = json_encode($fields_);
        $equipment_fields->save();
    }

    Session::flash('success', 'Equipment fields updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function get_equipment_fields(Request $request){
      
    $category = EquipmentCategory::find($request->category_id);

    $equipment_fields = EquipmentField::where('category_id', $request->category_id)->first();
    $equipment_fields_array = array();
    if($equipment_fields){
      $equipment_fields_array = json_decode($equipment_fields->fields, true);
    }

    $fields_html = '<div class="row equipment-fields-box">';
    if( !empty($equipment_fields_array) ){
      foreach( $equipment_fields_array as $key => $equipment_field_ ){
        if($equipment_field_['type'] == 'Text'){
          $text_field = '<div class="col-md-12">
            <div class="form-group">
              <label>'.$equipment_field_['name'].'</label>
              <input type="text" name="equipment_fields_value['.$key.'][value]" class="form-control">
              <input type="hidden" value="'.$equipment_field_['type'].'" name="equipment_fields_value['.$key.'][type]" class="form-control">
              <input type="hidden" value="'.$equipment_field_['name'].'" name="equipment_fields_value['.$key.'][name]" class="form-control">
              <input type="hidden" value="'.$equipment_field_['options'].'" name="equipment_fields_value['.$key.'][options]" class="form-control">
            </div>
          </div>';
          $fields_html .= $text_field;
        }

        if($equipment_field_['type'] == 'Dropdown'){
          $options__ = explode(',', $equipment_field_['options']);
          $options__html = '';
          if( !empty($options__) ){
            foreach($options__ as $o){
              $options__html .= '<option value="'.$o.'">'.$o.'</option>';
            }
          }
          $dropdown_field = '<div class="col-md-12">
            <div class="form-group">
              <label>'.$equipment_field_['name'].'</label>
              <select name="equipment_fields_value['.$key.'][value]" class="form-control">'.$options__html.'</select>
              
              <input type="hidden" value="'.$equipment_field_['type'].'" name="equipment_fields_value['.$key.'][type]" class="form-control">
              <input type="hidden" value="'.$equipment_field_['name'].'" name="equipment_fields_value['.$key.'][name]" class="form-control">
              <input type="hidden" value="'.$equipment_field_['options'].'" name="equipment_fields_value['.$key.'][options]" class="form-control">
            </div>
          </div>';

          $fields_html .= $dropdown_field;
        }

        if($equipment_field_['type'] == 'Price'){
          $currencyInfo = $this->getCurrencyInfo();
          $currencyText = $currencyInfo->base_currency_text;
          $price_field = '<div class="col-md-12">
            <div class="form-group">
              <label>'.$equipment_field_['name'].' ( '.$currencyText.' )</label>
              <input type="number" min="0" step=".01" name="equipment_fields_value['.$key.'][value]" class="form-control">
              <input type="hidden" value="'.$equipment_field_['type'].'" name="equipment_fields_value['.$key.'][type]" class="form-control">
              <input type="hidden" value="'.$equipment_field_['name'].'" name="equipment_fields_value['.$key.'][name]" class="form-control">
              <input type="hidden" value="'.$equipment_field_['options'].'" name="equipment_fields_value['.$key.'][options]" class="form-control">
            </div>
          </div>';

          $fields_html .= $price_field;
        }
      }
    }
    
    
    if($category->multiple_charges){
        $fields_html .= '<div class="col-md-12">
            <div class="form-group">
              <h4>Price Settings</h4>
            </div>
          </div><div class="col-md-12">
            <div class="form-group">
              <label>Rental Days</label>
              <input type="number" id="rental_days" min="1" value="1" step="1" name="multiple_charges_settings[rental_days]" class="form-control">
              
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label>Live Load</label>
              <select name="multiple_charges_settings[live_load]" id="live_load" class="form-control">
              <option value="Yes">Yes</option>
              <option value="No">No</option>
              </select>
              
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label>Ton</label>
              <select name="multiple_charges_settings[allowed_ton]" class="form-control">
              <option value="1ton">1ton</option>
              <option value="2ton">2ton</option>
              <option value="3ton">3ton</option>
              <option value="4ton">4ton</option>
              <option value="5ton">5ton</option>
              <option value="6ton">6ton</option>
              <option value="7ton">7ton</option>
              <option value="8ton">8ton</option>
              <option value="9ton">9ton</option>
              </select>
            </div>
          </div><div class="col-md-12">
            <div class="form-group">
              <label>Base Price</label>
              <input type="number" min="0" step=".01" id="base_price" name="multiple_charges_settings[base_price]" class="form-control">
              
            </div>
          </div><div class="col-md-12">
            <div class="form-group">
              <label>Additional Daily cost after Rental period ( USD )</label>
              <input type="number" id="additional_daily_cost" min="0" step=".01" name="multiple_charges_settings[additional_daily_cost]" class="form-control">
              
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label>Environmental Charges ( % )</label>
              <input type="number" id="environmental_charges" min="0" step=".01" name="multiple_charges_settings[environmental_charges]" class="form-control">
              
            </div>
          </div><div class="col-md-12">
            <div class="form-group">
              <label>Expedited Services ( USD )</label>
              <input type="number" id="emergency_cost" min="0" step=".01" name="multiple_charges_settings[emergency_cost]" class="form-control">
              
            </div>
          </div><div class="col-md-12">
            <div class="form-group">
              <label>Relocation Fee ( USD )</label>
              <input type="number" id="relocation_fee" min="0" step=".01" name="multiple_charges_settings[relocation_fee]" class="form-control">
              
            </div>
          </div><div class="col-md-12">
            <div class="form-group">
              <label>If yes Live Load Cost ( USD )</label>
              <input type="number" id="live_load_cost" min="0" step=".01" name="multiple_charges_settings[live_load_cost]" class="form-control">
              
            </div>
          </div><div class="col-md-12">
            <div class="form-group">
              <label>Additional Tonnage Cost Per Tonne ( USD )</label>
              <input type="number" id="additional_tonnage_cost" min="0" step=".01" name="multiple_charges_settings[additional_tonnage_cost]" class="form-control">
              
            </div>
          </div><div class="col-md-12">
            <div class="form-group">
              <label>Swap Charge ( USD )</label>
              <input type="number" id="swap_charge" min="0" step=".01" name="multiple_charges_settings[swap_charge]" class="form-control">
              
            </div>
          </div>';
          
          
    }
    
    else if($category->id == env('TEMPORARY_TOILET_CATID')){
        
        $fields_html .= '<div class="col-md-12">
            <div class="form-group">
              <h4>Price Settings</h4>
            </div>
          </div>
          
          <div class="col-md-12">
            <div class="form-group">
              <label>Services per week</label>
              <select id="services_per_week" name="multiple_charges_settings[services_per_week]" class="form-control"><option value="1">1</option><option value="2">2</option><option value="3">3</option></select>
            </div>
          </div>
          
          <div class="col-md-12">
            <div class="form-group">
              <label>Rental Days Included</label>
              <select id="rental_days_included" name="multiple_charges_settings[rental_days_included]" class="form-control"><option value="28">28</option><option value="Monthly">Monthly</option></select>
            </div>
          </div>
          
          
          <div class="col-md-12">
            <div class="form-group">
              <label>Base Price</label>
              <input type="number" min="0" step=".01" id="base_price-cd-temporary-toilet-cat" name="multiple_charges_settings[base_price]" class="form-control">
              
            </div>
          </div>
          
          <div class="col-md-12">
            <div class="form-group">
              <label>Additional Service Cost ( USD )</label>
              <input type="number" id="additional_service_cost" min="0" step=".01" name="multiple_charges_settings[additional_service_cost]" class="form-control">
              
            </div>
          </div>
          
          <div class="col-md-12">
            <div class="form-group">
              <label>Relocation Fee ( USD )</label>
              <input type="number" id="relocation_fee" min="0" step=".01" name="multiple_charges_settings[relocation_fee]" class="form-control">
              
            </div>
          </div>
          
          <div class="col-md-12">
            <div class="form-group">
              <label>Special Event Price ( USD )</label>
              <input type="number" id="special_event_price" min="0" step=".01" name="multiple_charges_settings[special_event_price]" class="form-control">
              
            </div>
          </div>
          
          <div class="col-md-12">
            <div class="form-group">
              <label>Short Term Price ( USD )</label>
              <input type="number" id="short_term_price" min="0" step=".01" name="multiple_charges_settings[short_term_price]" class="form-control">
              
            </div>
          </div>
          
          <div class="col-md-12">
            <div class="form-group">
              <label>Long Term Price ( USD )</label>
              <input type="number" id="long_term_price" min="0" step=".01" name="multiple_charges_settings[long_term_price]" class="form-control">
              
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label>Construction Event Price ( USD )</label>
              <input type="number" id="construction_event_price" min="0" step=".01" name="multiple_charges_settings[construction_event_price]" class="form-control">
              
            </div>
          </div>';
          
          
    }
    else if($category->id == env('STORAGE_CONTAINER_CATID')){
        
        $fields_html .= '<div class="col-md-12">
            <div class="form-group">
              <h4>Price Settings</h4>
            </div>
          </div>
          
          <div class="col-md-12">
            <div class="form-group">
              <label>Rental Days</label>
              <input type="number" id="rental_days" min="1" value="1" step="1" name="multiple_charges_settings[rental_days]" class="form-control">
              
            </div>
          </div>
          
          <div class="col-md-12">
            <div class="form-group">
              <label>Base Price</label>
              <input type="number" min="0" step=".01" id="base_price" name="multiple_charges_settings[base_price]" class="form-control">
              
            </div>
          </div>';
          
          
    }
    

    $fields_html .= '</div>';
    
    
    $vendor_interest_equipment_form = '';
    if(Auth::guard('vendor')->user()){
        $vendor_id__ = Auth::guard('vendor')->user()->id;
        $vendor_settings_ = VendorSetting::where('vendor_id', $vendor_id__)->first();
        if(empty($vendor_settings_) || $vendor_settings_->signup_equipments == ''){
            
            if($category->id == env('DUMPSTER_CATID')){
                $vendor_interest_equipment_form = '<div class="vendor-interest-form-main">
                    
                <form method="post" action="'.route('vendor.save_vendor_interest').'">
                <a href="'.url('/vendor/equipment-management/create-equipment').'" class="vendor-interest-close"><i class="fa fa-times-circle" aria-hidden="true"></i>
</a>
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                    <div class="equipments_handel_fields">
					<label>
						<input type="checkbox" style="display:none" class="form_control equipments_handel" checked name="equipments[]" value="Dumpster"> Dumpster
					</label>
					<div class="equipments_handel_fields_box">
						<div class="form_group lab mb-4">
							<label>'. __('How many trucks does your company have ?') .'</label>
							<select name="equipments_fields[Dumpster][Company Trucks]" class="form_control">
								<option value="1-5">1-5</option>
								<option value="6-10">6-10</option>
								<option value="11-15">11-15</option>
								<option value="15+">15+</option>
							</select>		
						</div>
						
						<div class="form_group lab mb-4">
							<label>'. __('How many dumpsters does your company have ?') .'</label>
							<select name="equipments_fields[Dumpster][Company Dumpsters]" class="form_control">
								<option value="1-50">1-50</option>
								<option value="51-100">51-100</option>
								<option value="101-250">101-250</option>
								<option value="250+">250+</option>
							</select>	
						</div>
						
						<div class="form_group lab mb-4">
							<label>'. __('Current monthly sales volume') .'</label>
							<input type="number" min="0" name="equipments_fields[Dumpster][monthly sales volume]" class="form_control">	
						</div>
						
						<div class="form_group lab mb-4">
							<label>'. __('Current processing rate') .'</label>
							<input type="number" min="0" name="equipments_fields[Dumpster][processing rate]" class="form_control">	
						</div>
						
						<div class="form_group lab mb-4">
							<label>'. __('Current software program that you are using monthly cost?') .'</label>
							<input type="text" min="0" name="equipments_fields[Dumpster][software program that you are using monthly cost]" class="form_control">	
						</div>
						
						<div class="form_group lab mb-4">
							<label>'. __('How many employees do you have?') .'</label>
							<input type="number" min="0" name="equipments_fields[Dumpster][employees]" class="form_control">	
						</div>
					</div>
				</div>
				<div class="row">
                    <div class="col-12 text-center">
                      <button type="submit" class="btn btn-success">
                        Save
                      </button>
                    </div>
                  </div>
				</form></div>';
                
            }
            
            if($category->id == env('TEMPORARY_TOILET_CATID')){
                $vendor_interest_equipment_form = '<div class="vendor-interest-form-main">
                
<form method="post" action="'.route('vendor.save_vendor_interest').'">
<a href="'.url('/vendor/equipment-management/create-equipment').'" class="vendor-interest-close"><i class="fa fa-times-circle" aria-hidden="true"></i>
</a>
                    <input type="hidden" name="_token" value="'.csrf_token().'"><div class="equipments_handel_fields">
					<label>
						<input type="checkbox" style="display:none" class="form_control equipments_handel" checked name="equipments[]" value="Temp Toilet"> Temp Toilet
					</label>
					<div class="equipments_handel_fields_box">
						<div class="form_group lab mb-4">
							<label>'. __('How many people expected on site?') .'</label>
							<input type="number" min="0" name="equipments_fields[Temp Toilet][people expected on site]" class="form_control">	
								
						</div>
						
						<div class="form_group lab mb-4">
							<label>'. __('Is there water access on Site?') .'</label>
							<select name="equipments_fields[Temp Toilet][water access on Site]" class="form_control">
								<option value="Yes">Yes</option>
								<option value="No">No</option>
							</select>	
						</div>
						
						<div class="form_group lab mb-4">
							<label>'. __('Is the electrical hook up onsite?') .'</label>
							<select name="equipments_fields[Temp Toilet][electrical hook up onsite]" class="form_control">
								<option value="Yes">Yes</option>
								<option value="No">No</option>
							</select>	
						</div>
						
						<div class="form_group lab mb-4">
							<label>'. __('How many hours will it be in use?') .'</label>
							<input type="number" min="0" name="equipments_fields[Temp Toilet][hours will it be in use]" class="form_control">	
						</div>
						
					</div>
				</div>
				<div class="row">
                    <div class="col-12 text-center">
                      <button type="submit" class="btn btn-success">
                        Save
                      </button>
                    </div>
                  </div>
                  </form></div>';
                
            }
            
            if($category->id == env('STORAGE_CONTAINER_CATID')){
                $vendor_interest_equipment_form = '<div class="vendor-interest-form-main">
                <form method="post" action="'.route('vendor.save_vendor_interest').'">
                <a href="'.url('/vendor/equipment-management/create-equipment').'" class="vendor-interest-close"><i class="fa fa-times-circle" aria-hidden="true"></i>
</a>
                    <input type="hidden" name="_token" value="'.csrf_token().'"><div class="equipments_handel_fields">
					<label>
						<input type="checkbox" class="form_control equipments_handel" style="display:none" name="equipments[]" checked value="Storage Containers"> Storage Containers
					</label>
					<div class="equipments_handel_fields_box">
						<div class="form_group lab mb-4">
							<label>'.__('How many trucks does your company have ?').'</label>
							<select name="equipments_fields[Storage Containers][Company Trucks]" class="form_control">
								<option value="1-5">1-5</option>
								<option value="6-10">6-10</option>
								<option value="11-15">11-15</option>
								<option value="15+">15+</option>
							</select>		
						</div>
						
						<div class="form_group lab mb-4">
							<label>'. __('How many storage containers does your company have ?') .'</label>
							<select name="equipments_fields[Storage Containers][Company Storage Containers]" class="form_control">
								<option value="1-50">1-50</option>
								<option value="51-100">51-100</option>
								<option value="101-250">101-250</option>
								<option value="250+">250+</option>
							</select>	
						</div>
						
						<div class="form_group lab mb-4">
							<label>'. __('Current monthly sales volume') .'</label>
							<input type="number" min="0" name="equipments_fields[Storage Containers][monthly sales volume]" class="form_control">	
						</div>
						
						<div class="form_group lab mb-4">
							<label>'. __('Current processing rate') .'</label>
							<input type="number" min="0" name="equipments_fields[Storage Containers][processing rate]" class="form_control">	
						</div>
						
						<div class="form_group lab mb-4">
							<label>'. __('Current software program that you are using monthly cost?') .'</label>
							<input type="text" min="0" name="equipments_fields[Storage Containers][software program that you are using monthly cost]" class="form_control">	
						</div>
						
						<div class="form_group lab mb-4">
							<label>'. __('How many employees do you have?') .'</label>
							<input type="number" min="0" name="equipments_fields[Storage Containers][employees]" class="form_control">	
						</div>
					</div>
				</div>
				<div class="row">
                    <div class="col-12 text-center">
                      <button type="submit" class="btn btn-success">
                        Save
                      </button>
                    </div>
                  </div>
				</form></div>';
            }
        }
    }
    
    
    Session::flash('success', 'Category Equipment Fields Rendered!');

    return Response::json(['status' => 'success', 'vendor_interest_equipment_form'=> $vendor_interest_equipment_form, 'fields_html'=>$fields_html, 'is_multiple_charges' => $category->multiple_charges, 'is_temporary_toilet' => ($category->id == env('TEMPORARY_TOILET_CATID'))?1:0,
    'is_storage_container' => ($category->id == env('STORAGE_CONTAINER_CATID'))?1:0], 200);
  }
  // code by AG end
}
