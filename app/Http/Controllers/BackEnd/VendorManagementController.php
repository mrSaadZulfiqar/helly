<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Instrument\Equipment;
use App\Models\Language;
use App\Models\Transcation;
use App\Models\Vendor;
use App\Models\VendorInfo;
use App\Models\VendorSetting; // code by AG
use Exception;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;
use PHPMailer\PHPMailer\PHPMailer;

use App\Http\Controllers\Custom\VoximplantController;
use App\Http\Controllers\BackEnd\Custom\OptionController;
use App\Models\AdditionalContact;

use App\Models\Instrument\EquipmentCategory; // code by AG
use App\Models\Instrument\EquipmentContent; // code by AG
use App\Models\EquipmentField; // code by AG
use App\Models\EquipmentFieldsValue; // code by AG
use App\Models\PlanVendor; 
use App\Models\MembershipPlan; 
use Mews\Purifier\Facades\Purifier;

class VendorManagementController extends Controller
{
    public function settings()
    {
        $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('vendor_email_verification', 'vendor_admin_approval', 'admin_approval_notice')->first();
        return view('backend.end-user.vendor.settings', compact('setting'));
    }
    //update_setting
    public function update_setting(Request $request)
    {
        if ($request->vendor_email_verification) {
            $vendor_email_verification = 1;
        } else {
            $vendor_email_verification = 0;
        }
        if ($request->vendor_admin_approval) {
            $vendor_admin_approval = 1;
        } else {
            $vendor_admin_approval = 0;
        }
        // finally, store the favicon into db
        DB::table('basic_settings')->updateOrInsert(
            ['uniqid' => 12345],
            [
                'vendor_email_verification' => $vendor_email_verification,
                'vendor_admin_approval' => $vendor_admin_approval,
                'admin_approval_notice' => $request->admin_approval_notice,
            ]
        );

        Session::flash('success', 'Update Settings Successfully!');
        return back();
    }

    // code by AG start
    public function import_vendor_ag(){
        //echo 'hello'; die;
        // Read the JSON file 
        //$json = file_get_contents('https://catdump.com/public/assets/result_data.json');
        
        //$csv__ = file_get_contents('https://catdump.com/public/assets/dumpster-165k-4014.csv');
        
        
        if (($handle = fopen("https://catdump.com/public/assets/file1.csv", "r")) !== FALSE) {
            $r = 1;
          while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
              
            if($r < 2000){
                if($r > 1){
                    
                    $vendor_id_ = $this->ag_create_vendor( $data );
                }
                
            }else{
                break;
            }
            
            $r = $r+1;
            
          }
          fclose($handle);
        }
       
        
        // Decode the JSON file
        // $json_data = json_decode($json,true);
        // //echo '<pre>';
        // // Display data
        // //print_r($json_data); die;

        // if( !empty( $json_data ) ){
        //     foreach( $json_data as $vendor__ ){
        //         $vendor_id_ = $this->ag_create_vendor( $vendor__ );

        //         if(isset($vendor__['equipments_data']) && !empty($vendor__['equipments_data'])){
        //             foreach($vendor__['equipments_data'] as $equipments_data){
        //                 $this->ag_create_equipment($equipments_data, $vendor_id_ );
        //             }
        //         }
        //     }
        // }
    }
    
    public function ag_create_equipment($eq_data, $vendor_id ){
        // store thumbnail image in storage
        //$thumbnailImgName = UploadFile::store(public_path('assets/img/equipments/thumbnail-images/'), $request->file('thumbnail_image'));

        // get the lowest price
        // $prices = [];

        // array_push(
        // $prices,
        // $request->per_day_price
        // );
        // array_push($prices, $request->per_week_price);
        // array_push($prices, $request->per_month_price);

        // $priceArr = array_diff($prices, array(null));
        // $lowestPrice = min($priceArr);

        $in = array();
        $in['vendor_id'] = $vendor_id;
        $in['slider_images'] = '[]';

        // code by AG start  
        $equipment_fields__ = $eq_data['equipment_fields']??array();
        // code by AG end

        // store data in db
        $equipment = Equipment::create($in);

        $languages = Language::all();

        foreach ($languages as $language) {
            if($language->is_default == 1){
                $category_ = EquipmentCategory::where('language_id', $language->id)
                ->where('name', $eq_data['equipment_name'])->first();

                $equipment_fields = EquipmentField::where('category_id', $category_->id)->first();
                $equipment_fields_array = array();
                $equipment_fields_names = array();
                if($equipment_fields){
                    $equipment_fields_array = json_decode($equipment_fields->fields, true);
                    $equipment_fields_names = array_column($equipment_fields_array, 'name');
                }

                $real_equipment_fields_value = array();

                if( !empty( $equipment_fields__ ) ){
                    foreach($equipment_fields__ as $key => $field__){
                        
                        if(in_array($field__['name'], $equipment_fields_names)){
                            $search__ = array_search($field__['name'],$equipment_fields_names);
                            $real_equipment_fields_value[$key] = $field__;
                            $real_equipment_fields_value[$key]['type'] = $equipment_fields_array[$search__]['type'];
                            $real_equipment_fields_value[$key]['options'] = $equipment_fields_array[$search__]['options'];
                        }
                    }
                }
                // code by AG start
                $eq_fields = new EquipmentFieldsValue();
                $eq_fields->equipment_id = $equipment->id;
                $eq_fields->fields_value = json_encode($real_equipment_fields_value);
                $eq_fields->save();
                // code by AG end
                
                $equipmentContent = new EquipmentContent();
                $equipmentContent->language_id = $language->id;
                $equipmentContent->equipment_category_id = $category_->id;
                $equipmentContent->equipment_id = $equipment->id;
                $equipmentContent->title = $eq_data['equipment_name'].'-'.$eq_data['unique_id'];
                $equipmentContent->slug = createSlug($eq_data['equipment_name'].'-'.$eq_data['unique_id']);
                $equipmentContent->features = 'Equipment';
                $equipmentContent->description = Purifier::clean('Equipment');
                $equipmentContent->meta_keywords = '';
                $equipmentContent->meta_description = '';
                $equipmentContent->save();
            }

        // commented by AG start
        //   $location_ids = $request[$language->code . '_location_ids'];
        //   foreach ($location_ids as $location_id) {
        //     $eq_location = new EquipmentLocation();
        //     $eq_location->language_id = $language->id;
        //     $eq_location->equipment_id = $equipment->id;
        //     $eq_location->location_id = $location_id;
        //     $eq_location->save();
        //   }
        // commented by AG end
        }
    }
    
    public function ag_create_vendor_json( $vendor_data ){
        $languages = Language::get();
        
        $in = array();
        $in['password'] = Hash::make('vendor@'.rand(10,10000));
        $in['status'] = 1;

        $in['email'] = $vendor_data['vendor']['email'];
        $in['phone'] = $vendor_data['vendor']['phone'];
        $in['username'] = $vendor_data['vendor']['email'];
        

        // $file = $request->file('photo');
        // if ($file) {
        //     $extension = $file->getClientOriginalExtension();
        //     $directory = public_path('assets/admin/img/vendor-photo/');
        //     $fileName = uniqid() . '.' . $extension;
        //     @mkdir($directory, 0775, true);
        //     $file->move($directory, $fileName);
        //     $in['photo'] = $fileName;
        // }

        // code by AG start
        $additional_contacts_data = $vendor_data['additional_contacts']??array();

        //unset($in['additional_contacts']);

        $radius_ = $vendor_data['vendor']['provide_service'];
        $longitude_ = $vendor_data['vendor']['longitude'];
        $latitude_ = $vendor_data['vendor']['latitude'];
        $unit_ = $vendor_data['vendor']['unitType'];
        $location_ = $vendor_data['vendor']['address'];
        // unset($in['radius']);
        // unset($in['longitude']);
        // unset($in['latitude']);
        // unset($in['unit']);
        // unset($in['location']);

        // code by AG end

        $vendor = Vendor::create($in);

        $vendor_id = $vendor->id;
        foreach ($languages as $language) {
            if($language->is_default == 1){
                $vendorInfo = new VendorInfo();
                $vendorInfo->language_id = $language->id;
                $vendorInfo->vendor_id = $vendor_id;
                $vendorInfo->name = $vendor_data['vendor']['name'];
                $vendorInfo->shop_name = $vendor_data['vendor']['company'];
                $vendorInfo->country = '';
                $vendorInfo->city = $vendor_data['vendor']['city'];
                $vendorInfo->state = $vendor_data['vendor']['state'];
                $vendorInfo->zip_code = $vendor_data['vendor']['zip'];
                $vendorInfo->address = $vendor_data['vendor']['address'];
                $vendorInfo->details = '';
                $vendorInfo->save();
            }
        }
        
        // code by AG start
        if( !empty( $additional_contacts_data ) ){
            foreach($additional_contacts_data as $contact){
                $ad_contact = array();
                $ad_contact['vendor_id'] = $vendor_id;
                $ad_contact['email'] = $contact['ad_email'];
                $ad_contact['phone_full'] = $contact['ad_phone'];
                $ad_contact['fax_no'] = $contact['ad_fax_no'];
                
                $additional_contact = AdditionalContact::create($ad_contact);
            }
        }
        // code by AG end

        $vendor_settings_ = new VendorSetting();
        $vendor_settings_->vendor_id = $vendor_id;
        $vendor_settings_->location = $location_;
        $vendor_settings_->latitude = $latitude_;
        $vendor_settings_->longitude = $longitude_;
        $vendor_settings_->provide_service = $radius_;
        $vendor_settings_->unit = $unit_;
        $vendor_settings_->save();
        
        // code by AG end

        return $vendor_id;
    }
    
    public function ag_create_vendor( $vendor_data ){
        $languages = Language::get();
        
        $in = array();
        $in['password'] = Hash::make('vendor@'.rand(10,10000));
        $in['status'] = 1;

        $in['email'] = $vendor_data[8];
        $in['phone'] = str_replace( array( ' ', '-', ')', '('), '', $vendor_data[7]);
        $in['username'] = $vendor_data[8];
        
        if($in['email'] != ''){
        // $file = $request->file('photo');
        // if ($file) {
        //     $extension = $file->getClientOriginalExtension();
        //     $directory = public_path('assets/admin/img/vendor-photo/');
        //     $fileName = uniqid() . '.' . $extension;
        //     @mkdir($directory, 0775, true);
        //     $file->move($directory, $fileName);
        //     $in['photo'] = $fileName;
        // }

        // code by AG start
        // // $additional_contacts_data = $vendor_data['additional_contacts']??array();

        //unset($in['additional_contacts']);

        // $radius_ = $vendor_data['vendor']['provide_service'];
        // $longitude_ = $vendor_data['vendor']['longitude'];
        // $latitude_ = $vendor_data['vendor']['latitude'];
        // $unit_ = $vendor_data['vendor']['unitType'];
        // $location_ = $vendor_data['vendor']['address'];
        // unset($in['radius']);
        // unset($in['longitude']);
        // unset($in['latitude']);
        // unset($in['unit']);
        // unset($in['location']);

        // code by AG end
        
        $vendor_exist = Vendor::where('email', $in['email'])->first();
        
        if(empty($vendor_exist)){
            echo $vendor_data[8].'<br>';
            $vendor = Vendor::create($in);

            $vendor_id = $vendor->id;
            foreach ($languages as $language) {
                if($language->is_default == 1){
                    
                    $address__ = explode(', ', $vendor_data[6]);
                    
                    $address__2 = explode(' ', $address__[count($address__)-1]);
                    
                    $vendorInfo = new VendorInfo();
                    $vendorInfo->language_id = $language->id;
                    $vendorInfo->vendor_id = $vendor_id;
                    $vendorInfo->name = $vendor_data[3];
                    $vendorInfo->shop_name = $vendor_data[3];
                    $vendorInfo->country = 'United States';
                    $vendorInfo->city = '';
                    $vendorInfo->state = $address__2[0]??'';
                    $vendorInfo->zip_code = $address__2[1]??'';
                    $vendorInfo->address = $vendor_data[6];
                    $vendorInfo->details = '';
                    $vendorInfo->save();
                }
            }
            
            // code by AG start
            $ad_contact = array();
            $ad_contact['vendor_id'] = $vendor_id;
            $ad_contact['email'] = $vendor_data[8];
            $ad_contact['phone_full'] = str_replace( array( ' ', '-', ')', '('), '', $vendor_data[7]);
            $ad_contact['fax_no'] = '';
            
            $additional_contact = AdditionalContact::create($ad_contact);
            // code by AG end
    
            $vendor_settings_ = new VendorSetting();
            $vendor_settings_->vendor_id = $vendor_id;
            // $vendor_settings_->location = $location_;
            // $vendor_settings_->latitude = $latitude_;
            // $vendor_settings_->longitude = $longitude_;
            // $vendor_settings_->provide_service = $radius_;
            // $vendor_settings_->unit = $unit_;
            $vendor_settings_->save();
            
            // code by AG end
            
            
    
            return $vendor_id; 
        }
        
        
        }
        
        return false;
    }
    // code by AG end

    public function index(Request $request)
    {
        $searchKey = null;

        if ($request->filled('info')) {
            $searchKey = $request['info'];
        }

        $vendors = Vendor::when($searchKey, function ($query, $searchKey) {
            return $query->where('username', 'like', '%' . $searchKey . '%')
                ->orWhere('email', 'like', '%' . $searchKey . '%');
        })
            ->orderBy('id', 'desc')
            ->paginate(10);


        return view('backend.end-user.vendor.index', compact('vendors'));
    }

    //add
    public function add(Request $request)
    {
        // first, get the language info from db
        $language = Language::query()->where('code', '=', $request->language)->first();
        $information['language'] = $language;
        $information['languages'] = Language::get();
        return view('backend.end-user.vendor.create', $information);
    }
    public function create(Request $request)
    {
        $admin = Admin::select('username')->first();
        $admin_username = $admin->username;
        $rules = [
            'username' => "required|unique:vendors|not_in:$admin_username",
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];


        $languages = Language::get();
        foreach ($languages as $language) {
            $rules[$language->code . '_name'] = 'required';
            $rules[$language->code . '_shop_name'] = 'required';
        }



        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $in = $request->all();
        $in['password'] = Hash::make($request->password);
        $in['status'] = 1;

        $file = $request->file('photo');
        if ($file) {
            $extension = $file->getClientOriginalExtension();
            $directory = public_path('assets/admin/img/vendor-photo/');
            $fileName = uniqid() . '.' . $extension;
            @mkdir($directory, 0775, true);
            $file->move($directory, $fileName);
            $in['photo'] = $fileName;
        }

        // code by AG start
        $additional_contacts_data = $in['additional_contacts']??array();

        unset($in['additional_contacts']);

        $radius_ = $in['radius'];
        $longitude_ = $in['longitude'];
        $latitude_ = $in['latitude'];
        $unit_ = $in['unit'];
        $location_ = $in['location'];
        unset($in['radius']);
        unset($in['longitude']);
        unset($in['latitude']);
        unset($in['unit']);
        unset($in['location']);

        // code by AG end

        $vendor = Vendor::create($in);

        $vendor_id = $vendor->id;
        foreach ($languages as $language) {
            $vendorInfo = new VendorInfo();
            $vendorInfo->language_id = $language->id;
            $vendorInfo->vendor_id = $vendor_id;
            $vendorInfo->name = $request[$language->code . '_name'];
            $vendorInfo->shop_name = $request[$language->code . '_shop_name'];
            $vendorInfo->country = $request[$language->code . '_country'];
            $vendorInfo->city = $request[$language->code . '_city'];
            $vendorInfo->state = $request[$language->code . '_state'];
            $vendorInfo->zip_code = $request[$language->code . '_zip_code'];
            $vendorInfo->address = $request[$language->code . '_address'];
            $vendorInfo->details = $request[$language->code . '_details'];
            $vendorInfo->save();
        }
        
        // code by AG start
        if( !empty( $additional_contacts_data ) ){
            foreach($additional_contacts_data as $contact){
                $contact['vendor_id'] = $vendor_id;
                unset($contact['phone']);
                $additional_contact = AdditionalContact::create($contact);
            }
        }
        // code by AG end

        $vendor_settings_ = new VendorSetting();
        $vendor_settings_->vendor_id = $vendor_id;
        $vendor_settings_->location = $location_;
        $vendor_settings_->latitude = $latitude_;
        $vendor_settings_->longitude = $longitude_;
        $vendor_settings_->provide_service = $radius_;
        $vendor_settings_->unit = $unit_;
        $vendor_settings_->save();
        
        // code by AG end

        Session::flash('success', 'Add Vendor Successfully!');
        return Response::json(['status' => 'success'], 200);
    }

    public function show($id)
    {

        $information['langs'] = Language::all();

        $language = Language::where('code', request()->input('language'))->first();
        $information['language'] = $language;
        $vendor = Vendor::with([
            'vendor_info' => function ($query) use ($language) {
                return $query->where('language_id', $language->id);
            }
        ])->find($id);
        $information['vendor'] = $vendor;

        $information['langs'] = Language::all();
        $information['currencyInfo'] = $this->getCurrencyInfo();

        $information['allEquipment'] = Equipment::query()->where('vendor_id', $vendor->id)->join('equipment_contents', 'equipments.id', '=', 'equipment_contents.equipment_id')
            ->join('equipment_categories', 'equipment_categories.id', '=', 'equipment_contents.equipment_category_id')
            ->where('equipment_contents.language_id', '=', $language->id)
            ->select('equipments.id', 'equipments.thumbnail_image', 'equipments.quantity', 'equipment_contents.title', 'equipment_contents.slug', 'equipment_categories.name as categoryName', 'equipments.is_featured')
            ->orderByDesc('equipments.id')
            ->get();
            
        //code by AG start
        $signup_equipments = VendorSetting::where('vendor_id',$id)->first();
        
        if($signup_equipments && isset($signup_equipments->signup_equipments) && $signup_equipments->signup_equipments != ''){
            $information['signup_equipments'] = json_decode($signup_equipments->signup_equipments, true);
        }
        else{
            $information['signup_equipments'] = array();
        }
    
        
        //code by AG end

        return view('backend.end-user.vendor.details', $information);
    }
    public function updateAccountStatus(Request $request, $id)
    {

        $user = Vendor::find($id);
        if ($request->account_status == 1) {
            $user->update(['status' => 1]);
        } else {
            $user->update(['status' => 0]);
        }
        Session::flash('success', 'Account status updated successfully!');

        return redirect()->back();
    }

    public function updateEmailStatus(Request $request, $id)
    {
        $vendor = Vendor::find($id);
        if ($request->email_status == 1) {
            $vendor->update(['email_verified_at' => now()]);
        } else {
            $vendor->update(['email_verified_at' => NULL]);
        }
        Session::flash('success', 'Email status updated successfully!');

        return redirect()->back();
    }
    public function changePassword($id)
    {
        $userInfo = Vendor::find($id);

        return view('backend.end-user.vendor.change-password', compact('userInfo'));
    }
    public function updatePassword(Request $request, $id)
    {
        $rules = [
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ];

        $messages = [
            'new_password.confirmed' => 'Password confirmation does not match.',
            'new_password_confirmation.required' => 'The confirm new password field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $user = Vendor::find($id);

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        Session::flash('success', 'Password updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function edit($id)
    {
        $information['languages'] = Language::get();
        $vendor = Vendor::find($id);
        $information['vendor'] = $vendor;

        
        // code by AG start
        $additional_contacts = AdditionalContact::where('vendor_id', $vendor->id)->get()->toArray();
        
        $information['additional_contacts'] = $additional_contacts;

        $vendor_settings_ = VendorSetting::where('vendor_id',$id)->get()->toArray();
        $information['vendor_settings'] = (!empty($vendor_settings_))?$vendor_settings_[0]:array();
        // code by AG end

        $information['currencyInfo'] = $this->getCurrencyInfo();
        return view('backend.end-user.vendor.edit', $information);
    }

    //update
    public function update(Request $request, $id, Vendor $vendor)
    {
        $rules = [

            'username' => [
                'required',
                'not_in:admin',
                Rule::unique('vendors', 'username')->ignore($id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('vendors', 'email')->ignore($id)
            ]
        ];

        if ($request->hasFile('photo')) {
            $rules['photo'] = 'mimes:png,jpeg,jpg|dimensions:min_width=80,max_width=80,min_width=80,min_height=80';
        }

        $languages = Language::get();
        foreach ($languages as $language) {
            $rules[$language->code . '_name'] = 'required';
            $rules[$language->code . '_shop_name'] = 'required';
        }

        $messages = [];

        foreach ($languages as $language) {
            $messages[$language->code . '_name.required'] = 'The name field is required.';

            $messages[$language->code . '_shop_name.required'] = 'The shop name field is required.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }


        $in = $request->all();
        $vendor  = Vendor::where('id', $id)->first();
        $file = $request->file('photo');
        if ($file) {
            $extension = $file->getClientOriginalExtension();
            $directory = public_path('assets/admin/img/vendor-photo/');
            $fileName = uniqid() . '.' . $extension;
            @mkdir($directory, 0775, true);
            $file->move($directory, $fileName);

            @unlink(public_path('assets/admin/img/vendor-photo/') . $vendor->photo);
            $in['photo'] = $fileName;
        }


        if ($request->show_email_addresss) {
            $in['show_email_addresss'] = 1;
        } else {
            $in['show_email_addresss'] = 0;
        }
        if ($request->show_phone_number) {
            $in['show_phone_number'] = 1;
        } else {
            $in['show_phone_number'] = 0;
        }
        if ($request->show_contact_form) {
            $in['show_contact_form'] = 1;
        } else {
            $in['show_contact_form'] = 0;
        }

         // code by AG start
         $additional_contacts_data = $in['additional_contacts']??array();

         unset($in['additional_contacts']);

         $radius_ = $in['radius'];
         $longitude_ = $in['longitude'];
         $latitude_ = $in['latitude'];
         $unit_ = $in['unit'];
         $location_ = $in['location'];
         unset($in['radius']);
         unset($in['longitude']);
         unset($in['latitude']);
         unset($in['unit']);
         unset($in['location']);
         
         // code by AG end


        $vendor->update($in);

        // code by AG start
        $vendor_settings_ = VendorSetting::where('vendor_id',$id)->get()->toArray();

        if( !empty( $vendor_settings_ ) ){
            $vendor_settings_e = VendorSetting::find($vendor_settings_[0]['id']);
            
            $vendor_settings_e->location = $location_;
            $vendor_settings_e->latitude = $latitude_;
            $vendor_settings_e->longitude = $longitude_;
            $vendor_settings_e->provide_service = $radius_;
            $vendor_settings_e->unit = $unit_;
            $vendor_settings_e->save();
        }
        else{
            $vendor_settings_ = new VendorSetting();
            $vendor_settings_->vendor_id = $id;
            $vendor_settings_->location = $location_;
            $vendor_settings_->latitude = $latitude_;
            $vendor_settings_->longitude = $longitude_;
            $vendor_settings_->provide_service = $radius_;
            $vendor_settings_->unit = $unit_;
            $vendor_settings_->save();
        }
        
        // code by AG end

        $languages = Language::get();
        $vendor_id = $vendor->id;
        foreach ($languages as $language) {
            $vendorInfo = VendorInfo::where('vendor_id', $vendor_id)->where('language_id', $language->id)->first();
            if ($vendorInfo == NULL) {
                $vendorInfo = new VendorInfo();
            }
            $vendorInfo->language_id = $language->id;
            $vendorInfo->vendor_id = $vendor_id;
            $vendorInfo->name = $request[$language->code . '_name'];
            $vendorInfo->shop_name = $request[$language->code . '_shop_name'];
            $vendorInfo->country = $request[$language->code . '_country'];
            $vendorInfo->city = $request[$language->code . '_city'];
            $vendorInfo->state = $request[$language->code . '_state'];
            $vendorInfo->zip_code = $request[$language->code . '_zip_code'];
            $vendorInfo->address = $request[$language->code . '_address'];
            $vendorInfo->details = $request[$language->code . '_details'];
            $vendorInfo->save();
        }
        
        // code by AG start
        AdditionalContact::where('vendor_id', $vendor_id)->delete();

        if( !empty( $additional_contacts_data ) ){
            foreach($additional_contacts_data as $contact){
                $contact['vendor_id'] = $vendor_id;
                unset($contact['phone']);
                $additional_contact = AdditionalContact::create($contact);
            }
        }
        // code by AG end


        Session::flash('success', 'Vendor updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function update_vendor_balance(Request $request, $id, Vendor $vendor)
    {
        $vendor  = Vendor::where('id', $id)->first();
        $currency_info = Basic::select('base_currency_symbol_position', 'base_currency_symbol')
            ->first();
        //add or subtract vendor balance
        if ($request->amount_status && $request->amount_status == 1) {
            $amount = $vendor->amount + $request->amount;

            //store data to transcation table
            $transcation = Transcation::create([
                'transcation_id' => time(),
                'booking_id' => NULL,
                'transcation_type' => 3,
                'user_id' => NULL,
                'vendor_id' => $vendor->id,
                'payment_status' => 1,
                'payment_method' => NULL,
                'grand_total' => $request->amount,
                'pre_balance' => $vendor->amount,
                'after_balance' => $amount,
                'gateway_type' => NULL,
                'currency_symbol' => $currency_info->base_currency_symbol,
                'currency_symbol_position' => $currency_info->base_currency_symbol_position,
            ]);

            $vendor_new_amount = $amount;
        } else {
            $amount = $vendor->amount - $request->amount;
            //store data to transcation table
            $transcation = Transcation::create([
                'transcation_id' => time(),
                'booking_id' => NULL,
                'transcation_type' => 4,
                'user_id' => NULL,
                'vendor_id' => $vendor->id,
                'payment_status' => 1,
                'payment_method' => NULL,
                'grand_total' => $request->amount,
                'pre_balance' => $vendor->amount,
                'after_balance' => $amount,
                'gateway_type' => NULL,
                'currency_symbol' => $currency_info->base_currency_symbol,
                'currency_symbol_position' => $currency_info->base_currency_symbol_position,
            ]);

            $vendor_new_amount = $amount;
        }

        //send mail
        if ($request->amount_status == 1 || $request->amount_status == 0) {
            if ($request->amount_status == 1) {
                $template_type = 'balance_add';

                $vendor_alert_msg = "Balance added to vendor account succefully.!";
            } else {
                $template_type = 'balance_subtract';
                $vendor_alert_msg = "Balance Subtract from vendor account succefully.!";
            }
            //mail sending
            // get the website title & mail's smtp information from db
            $info = Basic::select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name', 'base_currency_symbol_position', 'base_currency_symbol')
                ->first();

            //preparing mail info
            // get the mail template info from db
            $mailTemplate = MailTemplate::query()->where('mail_type', '=', $template_type)->first();
            $mailData['subject'] = $mailTemplate->mail_subject;
            $mailBody = $mailTemplate->mail_body;

            // get the website title info from db
            $website_info = Basic::select('website_title')->first();

            // preparing dynamic data
            $vendorName = $vendor->username;
            $vendorEmail = $vendor->email;
            $vendor_amount = $amount;

            $websiteTitle = $website_info->website_title;

            // replacing with actual data
            $mailBody = str_replace('{transaction_id}', $transcation->transcation_id, $mailBody);
            $mailBody = str_replace('{username}', $vendorName, $mailBody);
            $mailBody = str_replace('{amount}', $info->base_currency_symbol . $request->amount, $mailBody);

            $mailBody = str_replace('{current_balance}', $info->base_currency_symbol . $vendor_amount, $mailBody);
            $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);

            $mailData['body'] = $mailBody;

            $mailData['recipient'] = $vendorEmail;
            //preparing mail info end

            // initialize a new mail
            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            // if smtp status == 1, then set some value for PHPMailer
            if ($info->smtp_status == 1) {
                $mail->isSMTP();
                $mail->Host       = $info->smtp_host;
                $mail->SMTPAuth   = true;
                $mail->Username   = $info->smtp_username;
                $mail->Password   = $info->smtp_password;

                if ($info->encryption == 'TLS') {
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                }

                $mail->Port       = $info->smtp_port;
            }

            // add other informations and send the mail
            try {
                $mail->setFrom($info->from_mail, $info->from_name);
                $mail->addAddress($mailData['recipient']);

                $mail->isHTML(true);
                $mail->Subject = $mailData['subject'];
                $mail->Body = $mailData['body'];

                $mail->send();
                Session::flash('success', $vendor_alert_msg);
            } catch (Exception $e) {
                Session::flash('warning', 'Mail could not be sent. Mailer Error: ' . $mail->ErrorInfo);
            }
            //mail sending end
        }
        $vendor->amount = $vendor_new_amount;
        $vendor->save();
        return Response::json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $vendor = Vendor::find($id);

        /***********==Reveiew==*********** */
        $reviews = $vendor->reviews()->get();
        foreach ($reviews as $review) {
            $review->delete();
        }
        /*********************************************/
        #============delete vendor equipment==========

        $equipments = $vendor->equipment()->get();

        foreach ($equipments as $equipment) {
            // delete the thumbnail image
            @unlink(public_path('assets/img/equipments/thumbnail-images/') . $equipment->thumbnail_image);

           // Delete slider images
$sliderImages = json_decode($equipment->slider_images);

// Check if sliderImages is not null before attempting to iterate
if (!is_null($sliderImages)) {
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

            //delete all support ticket
            $support_tickets = $vendor->support_ticket()->get();
            if (count($support_tickets) > 0) {
                foreach ($support_tickets as $support_ticket) {
                    //delete conversation 
                    $messages = $support_ticket->messages()->get();
                    foreach ($messages as $message) {
                        @unlink(public_path('assets/admin/img/support-ticket/') . $message->file);
                        $message->delete();
                    }
                    @unlink(public_path('assets/admin/img/support-ticket/attachment/') . $support_ticket->attachment);
                    $support_ticket->delete();
                }
            }

            $equipment->delete();
        }
        /*********************************************/
        #====finally delete the vendor=======
        @unlink(public_path('assets/admin/img/vendor-photo/') . $vendor->photo);

        $vendorInfos = $vendor->vendor_info()->get();
        foreach ($vendorInfos as $item) {
            $item->delete();
        }
        $vendor->delete();

        return redirect()->back()->with('success', 'Vendor info deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $vendor = Vendor::find($id);
            /***********==Reveiew==*********** */
            $reviews = $vendor->reviews()->get();
            foreach ($reviews as $review) {
                $review->delete();
            }
            /*********************************************/
            //============delete vendor equipment==========

            $equipments = $vendor->equipment()->get();

            foreach ($equipments as $equipment) {
                // delete the thumbnail image
                @unlink(public_path('assets/img/equipments/thumbnail-images/') . $equipment->thumbnail_image);

                // delete the slider images
                $sliderImages = json_decode($equipment->slider_images);

                foreach ($sliderImages as $sliderImage) {
                    @unlink(public_path('assets/img/equipments/slider-images/') . $sliderImage);
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
            /*********************************************/

            $vendorInfos = $vendor->vendor_info()->get();
            foreach ($vendorInfos as $item) {
                $item->delete();
            }

            #====finally delete the vendor=======
            @unlink(public_path('assets/admin/img/vendor-photo/') . $vendor->photo);
            $vendor->delete();
        }

        Session::flash('success', 'Vendors info deleted successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    //secrtet login
    public function secret_login($id)
    {
        Session::put('secret_login', 1);
        $vendor = Vendor::where('id', $id)->first();
        Auth::guard('vendor')->login($vendor);
        return redirect()->route('vendor.dashboard');
    }

    // lead calling method
    public function calling($id){
        $system_calling_service_provider = 'voximplant';
		

		if($system_calling_service_provider == 'voximplant'){
            $voximplant = new VoximplantController();
			return $voximplant->vendor_calling($id);
		}
		else if($system_calling_service_provider == 'messagebird'){
			//return $this->vendor_calling_messagebird($id);
		}
		else{
			return 'Calling Provider Not Selected';
		}
    }

    // lead sms_communication
    public function sms_communication($id){
        $vendor = Vendor::find($id);
		
        $options = new OptionController();
        $communication_settings = $options->get_options();
        $system_sms_service_provider = $communication_settings['system_sms_service_provider']??'voximplant';
        
        return view('backend.end-user.communication.smschat', compact('vendor', 'system_sms_service_provider'));
    }
    
    public function vendor_plans()
    {
        $vendor_plans = PlanVendor::orderby('id','desc')->paginate(10);
        return view('backend.end-user.vendor.vendor_plans',compact('vendor_plans'));
    }
    
    public function vendor_plan_detail($id)
    {
        $vendor_plan = PlanVendor::find($id);
        $plan = MembershipPlan::with('plan_features')->find($vendor_plan->plan_id);
        $vendor = Vendor::find($vendor_plan->vendor_id);
        return view('backend.end-user.vendor.vendor_plan_detail',compact('vendor_plan','plan','vendor'));
    }
    
    
    public function plan_assign()
    {
        $plans = MembershipPlan::where('status',1)->get();
        return view('backend.end-user.vendor.plan_assign',compact('plans'));
    }
    public function get_vendors_ajax(Request $request){
        $vendors = Vendor::where('username','LIKE','%'.$request->q.'%')->take($request->page_limit)->get();
        return response()->json($vendors);
    }
    public function assign_store(Request $request){
        
        $check_plan = PlanVendor::where('vendor_id', $request->vendor_id)->update(['status' => 0]);
        
        $plan_get = MembershipPlan::find($request->plan_id);
        $date = Carbon::now();
        $date->addDays($plan_get->validity);
        
        $plan = new PlanVendor();
        $plan->plan_id = $request->plan_id;
        $plan->vendor_id = $request->vendor_id;
        $plan->name = $request->name;
        $plan->email = $request->email;
        $plan->contact = $request->contact;
        $plan->expiration_date = $date;
        $plan->trial_days = $plan_get->trial_days;
        $plan->total = $plan_get->total;
        $plan->payment_status = $request->payment_status;
        $plan->payment_method = 'Helly Pay';
        $plan->status = 1;
        if($plan_get->trial_days > 0){
            $plan->is_trial_active = 1;
        }else{
            $plan->is_trial_active = 0;
        }
        $plan->save();
        return redirect()->back()->with('success','Plan Assign Successfully');
    }
}
