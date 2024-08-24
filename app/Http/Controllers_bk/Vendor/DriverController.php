<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\Instrument\EquipmentStoreRequest;
use App\Http\Requests\Instrument\EquipmentUpdateRequest;
use App\Models\Driver;
use App\Models\Admin;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;
use App\Http\Helpers\BasicMailer;

class DriverController extends Controller
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

        $information['allDriver'] = Driver::query()->where('vendor_id', Auth::guard('vendor')->user()->id)
            ->where('drivers.language_id', '=', $language->id)
            ->orderByDesc('drivers.id')
            ->get();

        return view('vendors.driver.index', $information);
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

        $information['languages'] = $languages;

        return view('vendors.driver.create', $information);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        
        $in['vendor_id'] = Auth::guard('vendor')->user()->id;

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

        $mailData['subject'] = 'New Driver Added to Equipment Vendor Profile';

        $mailData['body'] = 'Dear '.Auth::guard('vendor')->user()->username.',<br><br>';
        $mailData['body'] .= "We are writing to inform you that a new driver has been added to our equipment vendor profile. This update is aimed at ensuring smoother coordination and communication between our teams. <br><br>";
        $mailData['body'] .= "Driver Details: <br><br>";
        $mailData['body'] .= "<ul><li>Name: ".$driver->username."</li><li>License Number: N/A</li><li>Contact Information: ".Auth::guard('vendor')->user()->email."</li></ul><br><br>";
        $mailData['body'] .= "Please update your records accordingly to ensure seamless collaboration and to facilitate any future communication or deliveries. Should you have any questions or require further information, feel free to reach out to us at ".Auth::guard('vendor')->user()->email.".<br><br>";
        $mailData['body'] .= "Thank you for your attention to this matter.<br><br>";
        $mailData['body'] .= "Best regards,<br>";
        $mailData['body'] .= "CAT Dump";
        
        $mailData['recipient'] = Auth::guard('vendor')->user()->email;


        BasicMailer::sendMail($mailData);

        Session::flash('success', 'Add Driver Successfully!');
        return Response::json(['status' => 'success'], 200);

    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $driver = Driver::find($id);
        if ($driver) {
            if ($driver->vendor_id != Auth::guard('vendor')->user()->id) {
                return redirect()->route('vendor.dashboard');
            }
        } else {
            return redirect()->route('vendor.dashboard');
        }


        $information['driver'] = $driver;

        // get the currency information from db
        $information['currencyInfo'] = $this->getCurrencyInfo();

        // get all the languages from db
        $languages = Language::all();

        $information['languages'] = $languages;

        return view('vendors.driver.edit', $information);
    }
    
    public function update(Request $request, $id){
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
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $driver = Driver::find($id);

        #====finally delete the driver=======
        @unlink(public_path('assets/admin/img/vendor-photo/') . $driver->image);

        $driver->delete();

        return redirect()->back()->with('success', 'Driver deleted successfully!');
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
    
    
    public function changePassword($id)
    {
        $userInfo = Driver::find($id);

        return view('vendors.driver.change-password', compact('userInfo'));
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
}
