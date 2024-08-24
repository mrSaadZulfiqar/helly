<?php

namespace App\Http\Controllers\BackEnd\Custom;

use App\Http\Controllers\Controller;

use App\Http\Controllers\Custom\VoximplantController;
use App\Http\Controllers\BackEnd\Custom\OptionController;
use App\Models\Admin;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Instrument\Equipment;
use App\Models\Language;
use App\Models\Transcation;
use App\Models\Vendor;
use App\Models\VendorInfo;
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
use PHPMailer\PHPMailer\PHPMailer;
use App\Models\Lead;
use App\Models\LeadRemark;
use App\Models\AdditionalContact;

class LeadController extends Controller
{
    public function index(Request $request){
		$searchKey = null;

        if ($request->filled('info')) {
            $searchKey = $request['info'];
        }

        $leads = Lead::when($searchKey, function ($query, $searchKey) {
            return $query->where('username', 'like', '%' . $searchKey . '%')
                ->orWhere('email', 'like', '%' . $searchKey . '%');
        })
            ->orderBy('id', 'desc')
            ->paginate(10);


        return view('backend.end-user.lead.index', compact('leads'));
	}

    //add
    public function add(Request $request)
    {
        // first, get the language info from db
        $language = Language::query()->where('code', '=', $request->language)->first();
        $information['language'] = $language;
        $information['languages'] = Language::get();
        return view('backend.end-user.lead.create', $information);
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
            if($language->is_default == 1){
                $rules[$language->code . '_name'] = 'required';
                $rules[$language->code . '_shop_name'] = 'required';
            }
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
        $in['additional_contact'] = json_encode($in['additional_contacts']);
        $file = $request->file('photo');
        if ($file) {
            $extension = $file->getClientOriginalExtension();
            $directory = public_path('assets/admin/img/vendor-photo/');
            $fileName = uniqid() . '.' . $extension;
            @mkdir($directory, 0775, true);
            $file->move($directory, $fileName);
            $in['photo'] = $fileName;
        }

        $lead = Lead::create($in);

        $lead_id = $lead->id;
        foreach ($languages as $language) {
            if($language->is_default == 1){
                
                $lead->language_id = $language->id;
                
                $lead->name = $request[$language->code . '_name'];
                $lead->shop_name = $request[$language->code . '_shop_name'];
                $lead->country = $request[$language->code . '_country'];
                $lead->city = $request[$language->code . '_city'];
                $lead->state = $request[$language->code . '_state'];
                $lead->zip_code = $request[$language->code . '_zip_code'];
                $lead->address = $request[$language->code . '_address'];
                $lead->details = $request[$language->code . '_details'];
                $lead->save();
            } 
        }


        Session::flash('success', 'Add Lead Successfully!');
        return Response::json(['status' => 'success'], 200);
    }

    public function add_remark(Request $request){
        
        $user_id = Auth::user()->id;
        $in = $request->all();
        $remark_data = array();
        $remark_data['lead_id'] = $in['id'];
        $remark_data['remark_by'] = $user_id;
        $remark_data['remark'] = $in['remark'];

        $remark = LeadRemark::create($remark_data);

        Session::flash('success', 'Lead Remark Added Successfully!');
        return Response::json(['status' => 'success'], 200);
    }

    public function edit($id)
    {
        $information['languages'] = Language::get();
        $lead = Lead::find($id);
        $information['lead'] = $lead;
        $information['currencyInfo'] = $this->getCurrencyInfo();
        return view('backend.end-user.lead.edit', $information);
    }

    public function convert_to_vendor($id){
        $lead_m = Lead::find($id);
        $lead = Lead::find($id)->toArray();

        // creating vendor
        $admin = Admin::select('username')->first();
        $admin_username = $admin->username;
        $rules = [
            'username' => "required|unique:vendors|not_in:$admin_username",
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];


        $languages = Language::get();
        foreach ($languages as $language) {
            if($language->is_default == 1){
                // $rules[$language->code . '_name'] = 'required';
                // $rules[$language->code . '_shop_name'] = 'required';
            }
        }



        $validator = Validator::make($lead, $rules);

        if ($validator->fails()) {
            // return Response::json([
            //     'errors' => $validator->getMessageBag()
            // ], 400);
            $errors_html = '';
            foreach($validator->errors()->all() as $msg){
                $errors_html .= $msg.', ';
            }
            return redirect()->back()->with('warning',$errors_html);
        }

        $in = $lead;
        $in['password'] = Hash::make($lead['password']);
        $in['status'] = 1;

        $in['photo'] = $lead['photo'];

        
        $additional_contacts_data = json_decode($lead['additional_contact'], true);

        unset($in['additional_contact']);
        

        $vendor = Vendor::create($in);

        $vendor_id = $vendor->id;
        foreach ($languages as $language) {
            if($language->is_default == 1){
                $vendorInfo = new VendorInfo();
                $vendorInfo->language_id = $language->id;
                $vendorInfo->vendor_id = $vendor_id;
                $vendorInfo->name = $lead['name'];
                $vendorInfo->shop_name = $lead['shop_name'];
                $vendorInfo->country = $lead['country'];
                $vendorInfo->city = $lead['city'];
                $vendorInfo->state = $lead['state'];
                $vendorInfo->zip_code = $lead['zip_code'];
                $vendorInfo->address = $lead['address'];
                $vendorInfo->details = $lead['details'];
                $vendorInfo->save();
            }
        }
        
        
        if( !empty( $additional_contacts_data ) ){
            foreach($additional_contacts_data as $contact){
                $contact['vendor_id'] = $vendor_id;
                unset($contact['phone']);
                $additional_contact = AdditionalContact::create($contact);
            }
        }
        
        $lead_m->converted_to_vendor = 1;
        $lead_m->save();
        return redirect()->back()->with('success', 'Lead converted to successfully!');
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
            if($language->is_default == 1){
                $rules[$language->code . '_name'] = 'required';
                $rules[$language->code . '_shop_name'] = 'required';
            }
        }

        $messages = [];

        foreach ($languages as $language) {
            if($language->is_default == 1){
                $messages[$language->code . '_name.required'] = 'The name field is required.';

                $messages[$language->code . '_shop_name.required'] = 'The shop name field is required.';
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }


        $in = $request->all();

        $in['additional_contact'] = json_encode($in['additional_contacts']);

        $lead  = Lead::where('id', $id)->first();
        $file = $request->file('photo');
        if ($file) {
            $extension = $file->getClientOriginalExtension();
            $directory = public_path('assets/admin/img/vendor-photo/');
            $fileName = uniqid() . '.' . $extension;
            @mkdir($directory, 0775, true);
            $file->move($directory, $fileName);

            @unlink(public_path('assets/admin/img/vendor-photo/') . $lead->photo);
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


        $lead->update($in);

        $languages = Language::get();
        $lead_id = $lead->id;
        foreach ($languages as $language) {
            if($language->is_default == 1){
                $lead->language_id = $language->id;
                
                $lead->name = $request[$language->code . '_name'];
                $lead->shop_name = $request[$language->code . '_shop_name'];
                $lead->country = $request[$language->code . '_country'];
                $lead->city = $request[$language->code . '_city'];
                $lead->state = $request[$language->code . '_state'];
                $lead->zip_code = $request[$language->code . '_zip_code'];
                $lead->address = $request[$language->code . '_address'];
                $lead->details = $request[$language->code . '_details'];
                $lead->save();
            }
            
        }



        Session::flash('success', 'Lead updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function show($id)
    {

        $information['langs'] = Language::all();

        $language = Language::where('code', request()->input('language'))->first();
        $information['language'] = $language;
        $lead = Lead::find($id);
        $information['lead'] = $lead;

        $information['langs'] = Language::all();
        $information['currencyInfo'] = $this->getCurrencyInfo();

        return view('backend.end-user.lead.details', $information);
    }

    public function destroy($id)
    {
        $lead = Lead::find($id);

        #====finally delete the lead=======
        @unlink(public_path('assets/admin/img/vendor-photo/') . $lead->photo);

        $lead->delete();

        return redirect()->back()->with('success', 'Lead deleted successfully!');
    }
	
	public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $lead = Lead::find($id);

            #====finally delete the lead=======
            @unlink(public_path('assets/admin/img/vendor-photo/') . $lead->photo);
            $lead->delete();
        }

        Session::flash('success', 'Leads info deleted successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    // lead calling method
    public function calling($id){
        $system_calling_service_provider = 'voximplant';
		

		if($system_calling_service_provider == 'voximplant'){
            $voximplant = new VoximplantController();
			return $voximplant->lead_calling($id);
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
        $vendor = Lead::find($id);
		
        $options = new OptionController();
        $communication_settings = $options->get_options();
        $system_sms_service_provider = $communication_settings['system_sms_service_provider']??'voximplant';
        
        return view('backend.end-user.communication.smschat', compact('vendor', 'system_sms_service_provider'));
    }
}
