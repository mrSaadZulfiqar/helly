<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Http\Helpers\BasicMailer;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\UserProfileRequest;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Instrument\EquipmentBooking;
use App\Models\Shop\Product;
use App\Models\Shop\ProductOrder;
use App\Models\User;
use App\Models\Company;
use App\Models\CompanyBranch;
use App\Rules\MatchEmailRule;
use App\Rules\MatchOldPasswordRule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Illuminate\Support\Facades\Response;
use App\Models\UserCard; // code by AG
use App\Models\AdditionalInvoice; // code by AG
use App\Models\BookingUpdate; // code by AG
use App\Models\BookingDriver; // code by AG
use App\Models\EquipmentFieldsValue; // code by AG 
use App\Models\Instrument\EquipmentCategory; // code by AG
use App\Models\Vendor;
use App\Http\Controllers\FrontEnd\PaymentGateway\StaxController; // code by AG
use App\Notifications\BasicNotify; // code by AG
use App\Models\Admin;
use Http;
use Illuminate\Validation\Rule;

class ManagerController extends Controller
{
        
public function manager()
  {
      $misc = new MiscellaneousController();

      $queryResult['bgImg'] = $misc->getBreadcrumb();
      $queryResult['managers'] = User::where('owner_id',Auth::guard('web')->user()->id)->get();
      return view('frontend.user.manager.index',$queryResult);
  }
  public function manager_create()
  {
      $misc = new MiscellaneousController();
      $queryResult['bgImg'] = $misc->getBreadcrumb();
      return view('frontend.user.manager.create',$queryResult);
  }
  public function manager_store(Request $request)
  {
        // validation start
        request()->validate([
          'username' => 'required|unique:users|max:255',
          'email' => 'required|email:rfc,dns|unique:users|max:255',
          'password' => 'required|confirmed',
          'password_confirmation' => 'required',
          'first_name' => 'required',
            'last_name' => 'required',
        ]);

       

        $in = $request->all();
        
        $in['password'] = Hash::make($request->password);
        $in['status'] = 1;
        
        $file = $request->file('photo');
        if ($file) {
            $extension = $file->getClientOriginalExtension();
            $directory = public_path('assets/img/users/');
            $fileName = uniqid() . '.' . $extension;
            @mkdir($directory, 0775, true);
            $file->move($directory, $fileName);
            $in['image'] = $fileName;
        }
        
        $in['owner_id'] = auth()->user()->id;
        
        $user = new User();
        $user->first_name = $in['first_name'];
        $user->last_name = $in['last_name'];
        $user->username = $in['username'];
        $user->email = $in['email'];
        $user->password = $in['password'];
        $user->status = $in['status'];
        $user->contact_number = $in['contact_number'];
        $user->image = $in['image']??'';
        $user->city = $in['city'];
        $user->state = $in['state'];
        $user->country = $in['country'];
        $user->address = $in['address'];
        $user->owner_id = $in['owner_id'];
        $user->temporary_password = $request->password;
        $user->email_verified_at = date('Y-m-d H:i:s');
        $user->save();
        //$user = User::create($in);

        // pushing to invoice system 
        
        $response = Http::post(env('INVOICE_SYSTEM_URL').'api/users', [
            'first_name' => $in['first_name'],
            'last_name' => $in['last_name'],
            'email' => $in['email'],
            'password' => $request->password,
            'contact' => $in['contact_number'],
            'postal_code' => '-',
            'address' => $in['address'],
            'website' => '',
            'vendor_email' => auth()->user()->email
        ]);
  
        $jsonData = $response->json();
        $msg = '';
        if($response->successful()){
            $msg = 'And Customer Pushed to Invoice System Successfully!';
        }
        Session::flash('success', 'Add Manager Successfully!'.' '.$msg);
        
        // $company = Company::where('customer_id',$in['owner_id'])->first();
        // $mailData['subject'] = 'Welcome to '. $company->name .'!';
        // $mailData['body'] = "We're pleased to inform you that your account as the Branch Manager at  ". $company->name ." has been successfully created.<br/><br/> Please use the following credentials to access your account:<br/><ul><li>Email: ".$in['email']."</li><li>".$request->password."</li></ul> ". $company->name ."<br/><br/>Upon login, you'll be prompted to set up your account details.<br/>. We're excited to have you join our team and look forward to your valuable contributions.<br/><br/>Best regards,<br/>".auth()->user()->username."";
        // $mailData['recipient'] = $request->email;
        // BasicMailer::sendMail($mailData);
        
        $owner = User::find($user->owner_id);
        $mailData['subject'] = 'New Manager Added';
        $mailData['body'] = "New ". $user->username ." User Added successfully";
        $mailData['recipient'] = $owner->email;
        BasicMailer::sendMail($mailData);
        
        // Admin Email
        $admin = Admin::find(1);
        $mailData['subject'] = 'New Manager Added';
        $mailData['body'] = "New ". $user->email ." User has been successfully added by ". auth()->user()->username ."";
        $mailData['recipient'] = $admin->email;
        BasicMailer::sendMail($mailData);
        
        return redirect()->route('user.manager')->with('status' , 'success');
  }
  public function manager_edit($id)
  {
      $misc = new MiscellaneousController();

      $queryResult['bgImg'] = $misc->getBreadcrumb();
      $queryResult['manager'] = User::find($id);
      return view('frontend.user.manager.edit',$queryResult);
  }
  public function manager_delete($id)
  {
      $manager = User::find($id);
      $manager->delete();
      $company = Company::where('customer_id',$manager->owner_id)->first();
      
      $mailData['subject'] = 'Delete Manager';
      $mailData['body'] = "your ". $manager->username ." Manager delete successfully";
      $mailData['recipient'] = auth()->user()->email;
      BasicMailer::sendMail($mailData);
      
      
    // Admin Email
      $admin = Admin::find(1);
    $mailData['subject'] = 'Manager Delete';
    $mailData['body'] =   $manager->email ." User has been successfully Delete by ". auth()->user()->username ."";
    $mailData['recipient'] =$admin->email ;
    BasicMailer::sendMail($mailData);
        
        // $mailData['subject'] = 'Account Deactivation Notification';
        // $mailData['body'] = "Dear ".$manager->username."<br/><br/> We regret to inform you that your account as the Branch Manager at  ". $company->name ." has been deactivated.<br/><br/>Best regards,<br/>".auth()->user()->username."";
        // $mailData['recipient'] = $manager->email;
        // BasicMailer::sendMail($mailData);
      return redirect()->back()->with('success' , 'Manager deleted successfully!');
  }
  public function manager_update(Request $request, $id){
        
        $rules = [
          'username' => [
                'required',
                'not_in:admin',
                Rule::unique('users', 'username')->ignore($id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id)
            ],
          'first_name' => 'required',
            'last_name' => 'required',
        ];

        if ($request->hasFile('photo')) {
            //$rules['photo'] = 'mimes:png,jpeg,jpg|dimensions:min_width=80,max_width=80,min_width=80,min_height=80';
        };
        if ($request->password) {
            $rules['password'] = 'required | confirmed';
            $rules['password_confirmation'] = 'required';
        }


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }



        $in = $request->all();


        $user  = User::find($id);
        $file = $request->file('photo');
        if ($file) {
            $extension = $file->getClientOriginalExtension();
            $directory = public_path('assets/img/users/');
            $fileName = uniqid() . '.' . $extension;
            @mkdir($directory, 0775, true);
            $file->move($directory, $fileName);

            @unlink(public_path('assets/img/users/') . $driver->image);
            $in['image'] = $fileName;
        }


        $user->first_name = $in['first_name'];
        $user->last_name = $in['last_name'];
        // $user->username = $in['username'];
        // $user->email = $in['email'];
        // $user->password = $in['password'];
        // $user->status = $in['status'];
        $user->contact_number = $in['contact_number'];
        if(isset($in['image'])){
            $user->image = $in['image'];
        }
        
        $user->city = $in['city'];
        $user->state = $in['state'];
        $user->country = $in['country'];
        $user->address = $in['address'];
        if ($request->password) {
            $user->password = Hash::make($in['password']);
        }
        // $user->vendor_id = $in['vendor_id'];
        $user->save();

        // pushing to invoice system 
        
        $response = Http::put(env('INVOICE_SYSTEM_URL').'api/users/'.base64_encode($user->email), [
            'first_name' => $in['first_name'],
            'last_name' => $in['last_name'],
            'contact' => $in['contact_number'],
            'postal_code' => '-',
            'address' => $in['address'],
            'website' => ''
        ]);
  
        $jsonData = $response->json();
        $msg = '';
        if($response->successful()){
            $msg = 'And Customer Update Pushed to Invoice System Successfully!';
        }
        
        $owner = User::find($user->owner_id);
        $mailData['subject'] = 'Update Manager Details';
        $mailData['body'] = "your ". $user->username ." details updated successfully";
        $mailData['recipient'] = $owner->email;
        BasicMailer::sendMail($mailData);
        // Admin Email
        $admin = Admin::find(1);
        $mailData['subject'] = 'Update Manager Details';
        $mailData['body'] =   $user->email ." User has been successfully Updated by ". auth()->user()->username ."";
        $mailData['recipient'] = $admin->email;
        BasicMailer::sendMail($mailData);

        // Session::flash('success', 'Customer updated successfully!'.' '.$msg);

        return redirect()->route('user.manager')->with('success' , 'Manager updated successfully!');
    } 
}