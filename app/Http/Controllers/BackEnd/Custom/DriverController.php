<?php

namespace App\Http\Controllers\BackEnd\Custom;

use App\Http\Controllers\Controller;

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
use App\Models\Driver;

class DriverController extends Controller
{
    //
    public function index(Request $request){
        $searchKey = null;

        if ($request->filled('info')) {
            $searchKey = $request['info'];
        }

        $drivers = Driver::when($searchKey, function ($query, $searchKey) {
            return $query->where('username', 'like', '%' . $searchKey . '%')
                ->orWhere('email', 'like', '%' . $searchKey . '%');
        })
            ->orderBy('id', 'desc')
            ->paginate(10);


        return view('backend.end-user.driver.index', compact('drivers'));
    }
    
    public function add(Request $request){
        // first, get the language info from db
        $language = Language::query()->where('code', '=', $request->language)->first();
        $information['language'] = $language;
        $information['languages'] = Language::get();
        
        $vendors = Vendor::get()->toArray();
        $information['vendors'] = $vendors;
        return view('backend.end-user.driver.create', $information);
    }
    
    public function create(Request $request){
        $admin = Admin::select('username')->first();
        $admin_username = $admin->username;
        $rules = [
            'username' => "required|unique:vendors|not_in:$admin_username",
            'email' => 'required|email',
            'password' => 'required|min:6',
            'first_name' => 'required',
            'last_name' => 'required',
        ];


        $languages = Language::get();
        foreach ($languages as $language) {
            if($language->is_default == 1){
                // $rules[$language->code . '_name'] = 'required';
                // $rules[$language->code . '_shop_name'] = 'required';
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
        
        $file = $request->file('photo');
        if ($file) {
            $extension = $file->getClientOriginalExtension();
            $directory = public_path('assets/admin/img/vendor-photo/');
            $fileName = uniqid() . '.' . $extension;
            @mkdir($directory, 0775, true);
            $file->move($directory, $fileName);
            $in['image'] = $fileName;
        }

        $driver = Driver::create($in);

        $driver_id = $driver->id;
        foreach ($languages as $language) {
            if($language->is_default == 1){
                
                $driver->language_id = $language->id;
                
                $driver->country = $request[$language->code . '_country'];
                $driver->city = $request[$language->code . '_city'];
                $driver->state = $request[$language->code . '_state'];
                $driver->zipcode = $request[$language->code . '_zipcode'];
                $driver->address = $request[$language->code . '_address'];
                
                $driver->save();
            } 
        }


        Session::flash('success', 'Add Driver Successfully!');
        return Response::json(['status' => 'success'], 200);
    }
    
    public function bulkDestroy(Request $request){
        $ids = $request->ids;

        foreach ($ids as $id) {
            $driver = Driver::find($id);

            #====finally delete the driver=======
            @unlink(public_path('assets/admin/img/vendor-photo/') . $driver->photo);
            $driver->delete();
        }

        Session::flash('success', 'Drivers info deleted successfully!');

        return Response::json(['status' => 'success'], 200);
    }
    
    public function show(){
        
    }
    
    public function edit($id){
        $information['languages'] = Language::get();
        $driver = Driver::find($id);
        $information['driver'] = $driver;
        $information['currencyInfo'] = $this->getCurrencyInfo();
        $vendors = Vendor::get()->toArray();
        $information['vendors'] = $vendors;
        return view('backend.end-user.driver.edit', $information);
    }
    
    public function update(Request $request, $id, Driver $driver){
        $rules = [

            'username' => [
                'required',
                'not_in:admin',
                Rule::unique('drivers', 'username')->ignore($id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('drivers', 'email')->ignore($id)
            ]
        ];

        if ($request->hasFile('photo')) {
            //$rules['photo'] = 'mimes:png,jpeg,jpg|dimensions:min_width=80,max_width=80,min_width=80,min_height=80';
        }

        $languages = Language::get();
        foreach ($languages as $language) {
            if($language->is_default == 1){
                // $rules[$language->code . '_name'] = 'required';
                // $rules[$language->code . '_shop_name'] = 'required';
            }
        }

        $messages = [];

        foreach ($languages as $language) {
            if($language->is_default == 1){
                // $messages[$language->code . '_name.required'] = 'The name field is required.';

                // $messages[$language->code . '_shop_name.required'] = 'The shop name field is required.';
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }


        $in = $request->all();


        $driver  = Driver::where('id', $id)->first();
        $file = $request->file('photo');
        if ($file) {
            $extension = $file->getClientOriginalExtension();
            $directory = public_path('assets/admin/img/vendor-photo/');
            $fileName = uniqid() . '.' . $extension;
            @mkdir($directory, 0775, true);
            $file->move($directory, $fileName);

            @unlink(public_path('assets/admin/img/vendor-photo/') . $driver->image);
            $in['image'] = $fileName;
        }


        $driver->update($in);

        $languages = Language::get();
        $driver_id = $driver->id;
        foreach ($languages as $language) {
            if($language->is_default == 1){
                $driver->language_id = $language->id;
                
                
                $driver->country = $request[$language->code . '_country'];
                $driver->city = $request[$language->code . '_city'];
                $driver->state = $request[$language->code . '_state'];
                $driver->zipcode = $request[$language->code . '_zipcode'];
                $driver->address = $request[$language->code . '_address'];
                
                $driver->save();
            }
            
        }



        Session::flash('success', 'Driver updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }
    
    public function changePassword($id)
    {
        $userInfo = Driver::find($id);

        return view('backend.end-user.driver.change-password', compact('userInfo'));
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

        $user = Driver::find($id);

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        Session::flash('success', 'Password updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }
    
    public function destroy($id){
        $driver = Driver::find($id);

        #====finally delete the driver=======
        @unlink(public_path('assets/admin/img/vendor-photo/') . $driver->image);

        $driver->delete();

        return redirect()->back()->with('success', 'Driver deleted successfully!');
    }
}
