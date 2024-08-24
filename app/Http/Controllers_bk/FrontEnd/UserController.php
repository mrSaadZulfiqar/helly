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
use App\Models\BranchUser;
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

use App\Models\Instrument\EquipmentContent;

class UserController extends Controller
{
  public function __construct()
  {
    $bs = DB::table('basic_settings')
      ->select('facebook_app_id', 'facebook_app_secret', 'google_client_id', 'google_client_secret')
      ->first();

    Config::set('services.facebook.client_id', $bs->facebook_app_id);
    Config::set('services.facebook.client_secret', $bs->facebook_app_secret);
    Config::set('services.facebook.redirect', url('user/login/facebook/callback'));

    Config::set('services.google.client_id', $bs->google_client_id);
    Config::set('services.google.client_secret', $bs->google_client_secret);
    Config::set('services.google.redirect', url('user/login/google/callback'));
  }

  public function login(Request $request)
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_login', 'meta_description_login')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    // get the status of digital product (exist or not in the cart)
    if (!empty($request->input('digital_item'))) {
      $queryResult['digitalProductStatus'] = $request->input('digital_item');
    }
		$queryResult['recaptchaInfo'] = Basic::select('google_recaptcha_status')->firstOrFail();

    $queryResult['bs'] = Basic::query()->select('google_recaptcha_status', 'facebook_login_status', 'google_login_status')->first();

    return view('frontend.login', $queryResult);
  }

  public function redirectToFacebook()
  {
    return Socialite::driver('facebook')->redirect();
  }

  public function handleFacebookCallback()
  {
    return $this->authenticationViaProvider('facebook');
  }

  public function redirectToGoogle()
  {
    return Socialite::driver('google')->redirect();
  }

  public function handleGoogleCallback()
  {
    return $this->authenticationViaProvider('google');
  }

  public function authenticationViaProvider($driver)
  {
    // get the url from session which will be redirect after login
    if (Session::has('redirectTo')) {
      $redirectURL = Session::get('redirectTo');
    } else {
      $redirectURL = route('user.dashboard');
    }

    $responseData = Socialite::driver($driver)->user();
    $userInfo = $responseData->user;

    $isUser = User::query()->where('email', '=', $userInfo['email'])->first();

    if (!empty($isUser)) {
      // log in
      if ($isUser->status == 1) {
        Auth::login($isUser);

        return redirect($redirectURL);
      } else {
        Session::flash('error', 'Sorry, your account has been deactivated.');

        return redirect()->route('user.login');
      }
    } else {
      // get user avatar and save it
      $avatar = $responseData->getAvatar();
      $fileContents = file_get_contents($avatar);

      $avatarName = $responseData->getId() . '.jpg';
      $path = public_path('assets/img/users/');

      file_put_contents($path . $avatarName, $fileContents);

      // sign up
      $user = new User();

      if ($driver == 'facebook') {
        $user->first_name = $userInfo['name'];
      } else {
        $user->first_name = $userInfo['given_name'];
        $user->last_name = $userInfo['family_name'];
      }

      $user->image = $avatarName;
      $user->username = $userInfo['id'];
      $user->email = $userInfo['email'];
      $user->email_verified_at = date('Y-m-d H:i:s');
      $user->status = 1;
      $user->provider = ($driver == 'facebook') ? 'facebook' : 'google';
      $user->provider_id = $userInfo['id'];
      $user->save();

      Auth::login($user);

      return redirect($redirectURL);
    }
  }

  public function loginSubmit(Request $request)
  {
    // get the url from session which will be redirect after login
    if ($request->session()->has('redirectTo')) {
      $redirectURL = $request->session()->get('redirectTo');
    } else {
      $redirectURL = null;
    }

    $info = Basic::select('google_recaptcha_status')->first();

    $rules = [
      'email' => 'required|email:rfc,dns',
      'password' => 'required'
    ];

    if ($info->google_recaptcha_status == 1) {
      $rules['g-recaptcha-response'] = 'required';
    }

    $messages = [];

    if ($info->google_recaptcha_status == 1) {
      $messages['g-recaptcha-response.required'] = 'Please verify that you are not a robot.';
      $messages['g-recaptcha-response.captcha'] = 'Captcha error! try again later or contact site admin.';
    }

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->route('user.login')->withErrors($validator->errors())->withInput();
    }

    // get the email and password which has provided by the user
    $credentials = $request->only('email', 'password');
    if ($info->google_recaptcha_status == 1) {

    // login attempt
    $response = $request->input('g-recaptcha-response');
        $secretKey = config('recaptcha.RECAPTCHA_SECRET_KEY');
        $url = 'https://www.google.com/recaptcha/api/siteverify';

        $response = Http::asForm()->post($url, [
            'secret' => $secretKey,
            'response' => $response,
        ]);

        $body = $response->json();
        if (!($body['success'])) {
            return redirect()->back()->with('error', 'reCAPTCHA validation failed. Please try again.');
        }
    }

            if (Auth::guard('web')->attempt($credentials)) {
              $authUser = Auth::guard('web')->user();
        
              // first, check whether the user's email address verified or not
              if (is_null($authUser->email_verified_at)) {
                Session::flash('error', 'Please, verify your email address.');
        
                // logout auth user as condition not satisfied
                Auth::guard('web')->logout();
        
                return redirect()->back();
              }
        
              // second, check whether the user's account is active or not
              if ($authUser->status == 0) {
                Session::flash('error', 'Sorry, your account has been deactivated.');
        
                // logout auth user as condition not satisfied
                Auth::guard('web')->logout();
        
                return redirect()->back();
              }
        
              // otherwise, redirect auth user to next url
              if (is_null($redirectURL)) {
                return redirect()->route('user.dashboard');
              } else {
                // before, redirect to next url forget the session value
                $request->session()->forget('redirectTo');
        
                return redirect($redirectURL);
              }
            } else {
              Session::flash('error', 'Incorrect email address or password!');
        
              return redirect()->back();
            }
  }

  public function forgetPassword()
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_forget_password', 'meta_description_forget_password')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    return view('frontend.forget-password', $queryResult);
  }

  public function forgetPasswordMail(Request $request)
  {
    $rules = [
      'email' => [
        'required',
        'email:rfc,dns',
        new MatchEmailRule('user')
      ]
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors())->withInput();
    }

    $user = User::query()->where('email', '=', $request->email)->first();

    // store user email in session to use it later
    $request->session()->put('userEmail', $user->email);

    // get the mail template information from db
    $mailTemplate = MailTemplate::query()->where('mail_type', '=', 'reset_password')->first();
    $mailData['subject'] = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    // get the website title info from db
    $info = Basic::select('website_title')->first();

    $name = $user->first_name . ' ' . $user->last_name;

    $link = '<a href=' . url("user/reset-password") . '>Click Here</a>';

    $mailBody = str_replace('{customer_name}', $name, $mailBody);
    $mailBody = str_replace('{password_reset_link}', $link, $mailBody);
    $mailBody = str_replace('{website_title}', $info->website_title, $mailBody);

    $mailData['body'] = $mailBody;

    $mailData['recipient'] = $user->email;

    $mailData['sessionMessage'] = 'A mail has been sent to your email address.';

    BasicMailer::sendMail($mailData);

    return redirect()->back();
  }

  public function resetPassword()
  {
    $misc = new MiscellaneousController();

    $bgImg = $misc->getBreadcrumb();

    return view('frontend.reset-password', compact('bgImg'));
  }

  public function resetPasswordSubmit(Request $request)
  {
    if ($request->session()->has('userEmail')) {
      // get the user email from session
      $emailAddress = $request->session()->get('userEmail');

      $rules = [
        'new_password' => 'required|confirmed',
        'new_password_confirmation' => 'required'
      ];

      $messages = [
        'new_password.confirmed' => 'Password confirmation failed.',
        'new_password_confirmation.required' => 'The confirm new password field is required.'
      ];

      $validator = Validator::make($request->all(), $rules, $messages);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator->errors());
      }

      $user = User::query()->where('email', '=', $emailAddress)->first();

      $user->update([
        'password' => Hash::make($request->new_password)
      ]);

      Session::flash('success', 'Password updated successfully.');
    } else {
      Session::flash('error', 'Something went wrong!');
    }

    return redirect()->route('user.login');
  }

  public function signup()
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_signup', 'meta_description_signup')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    $queryResult['recaptchaInfo'] = Basic::select('google_recaptcha_status')->first();

    return view('frontend.signup', $queryResult);
  }

  public function signupSubmit(Request $request)
  {
    $info = Basic::select('google_recaptcha_status', 'website_title')->first();

    // validation start
    $rules = [
      'username' => 'required|unique:users|max:255',
      'email' => 'required|email:rfc,dns|unique:users|max:255',
      'password' => 'required|confirmed',
      'password_confirmation' => 'required',
      'account_type' => 'required'
    ];
    if($request->account_type == 'corperate_account')
    {
        $rules['company_name'] = 'required';
    }
    session()->put('account_type','corperate_account');
   

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors())->withInput();
    }
    if ($info->google_recaptcha_status == 1) {
    // validation end
    $response = $request->input('g-recaptcha-response');
    $secretKey = config('recaptcha.RECAPTCHA_SECRET_KEY');
    $url = 'https://www.google.com/recaptcha/api/siteverify';

    $response = Http::asForm()->post($url, [
        'secret' => $secretKey,
        'response' => $response,
    ]);

    $body = $response->json();
        if (!($body['success'])) {
            return redirect()->back()->with('error', 'reCAPTCHA validation failed. Please try again.');
        }
    }
    session()->forget('account_type');


    $user = new User();
    $user->username = $request->username;
    $user->email = $request->email;
    $user->account_type = $request->account_type;
    $user->password = Hash::make($request->password);

    // first, generate a random string
    $randStr = Str::random(20);

    // second, generate a token
    $token = md5($randStr . $request->username . $request->email);

    $user->verification_token = $token;
    $user->save();
    
    if($request->company_name)
    {
        $company = new Company();
        $company->name = $request->company_name;
        $company->customer_id = $user->id;
        $company->save();
    }
    /**
     * prepare a verification mail and, send it to user to verify his/her email address,
     * get the mail template information from db
     */
    $mailTemplate = MailTemplate::query()->where('mail_type', '=', 'verify_email')->first();
    $mailData['subject'] = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    $link = '<a href=' . url("user/signup-verify/" . $token) . '>Click Here</a>';

    $mailBody = str_replace('{username}', $request->username, $mailBody);
    $mailBody = str_replace('{verification_link}', $link, $mailBody);
    $mailBody = str_replace('{website_title}', $info->website_title, $mailBody);

    $mailData['body'] = $mailBody;

    $mailData['recipient'] = $request->email;

    $mailData['sessionMessage'] = 'A verification link has been sent to your email address.';

    BasicMailer::sendMail($mailData);
    
    $mailData = array();
    
    $mailData['subject'] = 'Welcome to CAT DUMP!';

    $mailData['body'] = 'Dear '.$user->username.',<br><br>';
    $mailData['body'] .= "Welcome to CAT DUMP! We are thrilled to have you on board. Your account has been successfully created, and you were invited by Admin CAT DUMP.<br><br>";
    $mailData['body'] .= "Here are your account details:<br><br>";
    $mailData['body'] .= "Email address: : ".$user->email."<br><br>";
    $mailData['body'] .= "Should you encounter any login issues, please utilize the 'Forgot Password' option to reset your password conveniently.<br><br>";
    $mailData['body'] .= "At CAT DUMP, you can seamlessly manage all your invoices with ease. Thank you for joining us, and we wish you a fantastic day ahead!<br><br>";
    $mailData['body'] .= "Best regards,<br>";
    $mailData['body'] .= "CAT Dump";
    
    $mailData['recipient'] = $user->email;


    BasicMailer::sendMail($mailData);
    
    $admin_ = Admin::find(1);
    $mailData = array();
    $mailData['subject'] = 'New Customer Registration';
    $mailData['body'] = 'Username: '.$request->username;
    $mailData['recipient'] = $admin_->email;
    BasicMailer::sendMail($mailData);
    
    $admin_->notify(new BasicNotify('<a href="'. url('admin/user-management/user/'.$user->id.'/details') .'">New Customer Registration Received</a>'));

    return redirect()->back();
   
  }

  public function signupVerify(Request $request, $token)
  {
    try {
      $user = User::where('verification_token', $token)->firstOrFail();

      // after verify user email, put "null" in the "verification token"
      $user->update([
        'email_verified_at' => date('Y-m-d H:i:s'),
        'status' => 1,
        'verification_token' => null
      ]);

      Session::flash('success', 'Your email has been verified.');

      // after email verification, authenticate this user
      Auth::guard('web')->login($user);

      return redirect()->route('user.dashboard');
    } catch (ModelNotFoundException $e) {
      Session::flash('error', 'Could not verify your email address!');

      return redirect()->route('user.signup');
    }
  }

  public function redirectToDashboard()
  {
    $misc = new MiscellaneousController();

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    $user = Auth::guard('web')->user();

    $queryResult['authUser'] = $user;

    $queryResult['numOfOrders'] = $user->productOrder()->count();
    if(auth()->user()->account_type == 'corperate_account')
    {
        $company_id = Company::where('customer_id',auth()->user()->id)->first();
        $queryResult['numOfBookings'] = EquipmentBooking::where('company_id',$company_id->id)->count();
        
        
        $queryResult['company'] = Company::where('customer_id',auth()->user()->id)->first();
    }
    else if(auth()->user()->owner_id != null)
    {
        $branch_ids = BranchUser::where('user_id',auth()->user()->id)->get()->pluck('branch_id');
        
        $queryResult['numOfBookings'] = EquipmentBooking::whereIn('branch_id',$branch_ids)->count();
        $queryResult['company'] = Company::where('customer_id',auth()->user()->owner_id)->first();
    }
    else{
        $queryResult['numOfBookings'] = $user->equipmentBooking()->count();
    }


    return view('frontend.user.dashboard', $queryResult);
  }

  public function editProfile()
  {
    $misc = new MiscellaneousController();

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    $queryResult['authUser'] = Auth::guard('web')->user();

    return view('frontend.user.edit-profile', $queryResult);
  }

  public function updateProfile(UserProfileRequest $request)
  {
    $authUser = Auth::guard('web')->user();

    if ($request->hasFile('image')) {
      $newImg = $request->file('image');
      $oldImg = $authUser->image;
      $imageName = UploadFile::update(public_path('assets/img/users/'), $newImg, $oldImg);
    }

    $authUser->update($request->except('image') + [
      'image' => $request->hasFile('image') ? $imageName : $authUser->image
    ]);
    
    $company = Company::where('customer_id',auth()->user()->id)->first();
    $company->name = $request->company_name ;
    $company->update();

    Session::flash('success', 'Your profile has been updated successfully.');

    return redirect()->back();
  }

  public function changePassword()
  {
    $misc = new MiscellaneousController();

    $bgImg = $misc->getBreadcrumb();

    return view('frontend.user.change-password', compact('bgImg'));
  }

  public function updatePassword(Request $request)
  {
    $rules = [
      'current_password' => [
        'required',
        new MatchOldPasswordRule('user')
      ],
      'new_password' => 'required|confirmed',
      'new_password_confirmation' => 'required'
    ];

    $messages = [
      'new_password.confirmed' => 'Password confirmation failed.',
      'new_password_confirmation.required' => 'The confirm new password field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $user = Auth::guard('web')->user();

    $user->update([
      'password' => Hash::make($request->new_password)
    ]);

    Session::flash('success', 'Password updated successfully.');

    return redirect()->back();
  }

  public function bookings()
  {
    $misc = new MiscellaneousController();

    $queryResult['bgImg'] = $misc->getBreadcrumb();
    if(auth()->user()->account_type == 'corperate_account' && auth()->user()->owner_id == null)
    {
        $company_id = Company::where('customer_id',auth()->user()->id)->first();
        $bookings = EquipmentBooking::where('company_id',$company_id->id)->orderByDesc('id')->get();
    }
    else if(auth()->user()->account_type == 'corperate_account' && auth()->user()->owner_id != null || auth()->user()->account_type == null && auth()->user()->owner_id != null)
    {
        $branch_ids = BranchUser::where('user_id',auth()->user()->id)->get()->pluck('branch_id');
        $bookings = EquipmentBooking::whereIn('branch_id',$branch_ids)->orderByDesc('id')->get();
    }
    else{
        $authUser = Auth::guard('web')->user();
        $bookings = $authUser->equipmentBooking()->orderByDesc('id')->get();
    }
    $language = $misc->getLanguage();

    $bookings->map(function ($booking) use ($language) {
      $equipment = $booking->equipmentInfo()->first();
      $booking['equipmentInfo'] = $equipment->content()->where('language_id', $language->id)
        ->select('title', 'slug', 'equipment_category_id')
        ->first();
    });
    $queryResult['bookings'] = $bookings;
    
    return view('frontend.user.equipment-bookings', $queryResult);
  }

  public function bookingDetails($id)
  {
    $misc = new MiscellaneousController();

    $queryResult['bgImg'] = $misc->getBreadcrumb();
    
    $booking_updates = BookingUpdate::where('booking_id',$id)->get();

    $details = EquipmentBooking::query()->find($id);
    if ($details) {
      $queryResult['details'] = $details;

    //   if ($details->user_id != Auth::guard('web')->user()->id) {
    //     return redirect()->route('user.dashboard');
    //   }

      $queryResult['language'] = $misc->getLanguage();

      $queryResult['tax'] = Basic::select('equipment_tax_amount')->first();
      
      
      // code by AG start
      $add_invoices = AdditionalInvoice::where('booking_id', $details->id)->get();
      
      $queryResult['additional_invoices'] = $add_invoices;
      //echo '<pre>'; print_r($booking_updates); die;
      
      $status_timeline_html = '';
      $status_timeline = array(
            'accepted'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">1</span>
                                </span>
                                <h5>Accepted</h5>
                              </div>
                              <div class="seperator"></div>',
            'assigned'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">2</span>
                                </span>
                                <h5>Assigned</h5>
                              </div>
                              <div class="seperator"></div>',
            'pickedup'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">3</span>
                                </span>
                                <h5>Pickedup</h5>
                              </div>
                              <div class="seperator"></div>',
            'out_for_delivery'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">4</span>
                                </span>
                                <h5>Out For Delivery</h5>
                              </div>
                              <div class="seperator"></div>',
            'delivered'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">5</span>
                                </span>
                                <h5>Delivered</h5>
                              </div>
                              '
          );
         $status_timeline_for_swaping = array(
            'assigned_to_swap'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">1</span>
                                </span>
                                <h5>Assigned to swap</h5>
                              </div>
                              <div class="seperator"></div>',
            'pickedup_to_swap'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">2</span>
                                </span>
                                <h5>Pickedup to swap</h5>
                              </div>
                              <div class="seperator"></div>',
            'out_for_swap'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">3</span>
                                </span>
                                <h5>Out For Swap</h5>
                              </div>
                              <div class="seperator"></div>',
            'swaped'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">4</span>
                                </span>
                                <h5>Swaped</h5>
                              </div>
                              '
          );
          
          $status_timeline_for_pickup = array(
            'assigned_for_pickup'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">1</span>
                                </span>
                                <h5>Assigned</h5>
                              </div>
                              <div class="seperator"></div>',
            'out_for_pickup'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">2</span>
                                </span>
                                <h5>Out for pickup</h5>
                              </div>
                              <div class="seperator"></div>',
            'pickedup_from_customer'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">2</span>
                                </span>
                                <h5>Pickedup From Customer</h5>
                              </div>
                              <div class="seperator"></div>',
            'returned'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">3</span>
                                </span>
                                <h5>Returned</h5>
                              </div>',
          );
          
          
          $status_timeline_for_relocation = array(
            'assigned_to_relocate'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">1</span>
                                </span>
                                <h5>Assigned to relocate</h5>
                              </div>
                              <div class="seperator"></div>',
            'pickedup_to_relocate'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">2</span>
                                </span>
                                <h5>Pickedup to relocate</h5>
                              </div>
                              <div class="seperator"></div>',
            'out_for_relocate'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">3</span>
                                </span>
                                <h5>Out For relocate</h5>
                              </div>
                              <div class="seperator"></div>',
            'relocated'=>'<div class="step">
                                <span class="number-container">
                                    <span class="number">4</span>
                                </span>
                                <h5>Relocated</h5>
                              </div>
                              '
          );
          
          
          
        if( !empty($booking_updates)){
            $status_timeline_html .= '<div class="bar-progress mt-5 mb-5 mt-lg-0">';
            $step = 1;
            foreach($booking_updates as $key => $update){
                
                              
                if($update->status == 'swap_requested'){
                    $step = 0;
                    $status_timeline = $status_timeline_for_swaping;
                    $status_timeline_html .= '</div>';
                    $status_timeline_html .= '<div class="bar-progress mt-5 mb-5 mt-lg-0"><div class="step step-active">
                                <span class="number-container">
                                    <span class="number">S</span>
                                </span>
                                <h5>Swap Requested</h5>
                                <small class="status-at">at '.date("Y-m-d h:i A", strtotime($update->created_at)).'</small>
                              </div>
                              </div>';
                    $status_timeline_html .= '<div class="bar-progress mt-5 mb-5 mt-lg-0">';
                }
                else if($update->status == 'pickup_requested'){
                    $step = 0;
                    $status_timeline = $status_timeline_for_pickup;
                    $status_timeline_html .= '</div>';
                    $status_timeline_html .= '<div class="bar-progress mt-5 mb-5 mt-lg-0"><div class="step step-active">
                                <span class="number-container">
                                    <span class="number">P</span>
                                </span>
                                <h5>Pickup Requested</h5>
                                <small class="status-at">at '.date("Y-m-d h:i A", strtotime($update->created_at)).'</small>
                              </div>
                              </div>';
                    $status_timeline_html .= '<div class="bar-progress mt-5 mb-5 mt-lg-0">';
                }
                else if($update->status == 'relocation_requested'){
                    $step = 0;
                    $status_timeline = $status_timeline_for_relocation;
                    $status_timeline_html .= '</div>';
                    $status_timeline_html .= '<div class="bar-progress mt-5 mb-5 mt-lg-0"><div class="step step-active">
                                <span class="number-container">
                                    <span class="number">R</span>
                                </span>
                                <h5>Relocation Requested</h5>
                                <small class="status-at">at '.date("Y-m-d h:i A", strtotime($update->created_at)).'</small>
                              </div>
                              </div>';
                    $status_timeline_html .= '<div class="bar-progress mt-5 mb-5 mt-lg-0">';
                }
                else{
                    if($update->status != 'pickedup_from_customer'){
                        $status_timeline_html .= '<div class="step step-active">
                                    <span class="number-container">
                                        <span class="number">'.$step.'</span>
                                    </span>
                                    <h5>'.$update->status.'
                                    </h5>
                                    <small class="status-at">at '.date("Y-m-d h:i A", strtotime($update->created_at)).'</small>
                                  </div>
                                  ';
                        if($update->status != 'delivered' && $update->status != 'swaped' && $update->status != 'returned' && $update->status != 'relocated'){
                            $status_timeline_html .= '<div class="seperator"></div>';
                        }
                    }
                    
                    
                }
                if(( count($booking_updates) - 1 ) == $key){
                    $start_unfilled = false;
                    foreach($status_timeline as $key => $step_){
                        if($start_unfilled){
                            if($key != 'pickedup_from_customer'){
                                $status_timeline_html .= $step_;
                            }
                            
                        }
                        if($key == $update->status){
                            $start_unfilled = true;
                        }
                    }
                }             
                $step = $step + 1;
            }
            
            $status_timeline_html .= '</div>';
        }
      $queryResult['status_timeline_html'] = $status_timeline_html;
      // code by AG end

      return view('frontend.user.booking-details', $queryResult);
    } else {
      return view('errors.404');
    }
  }
  
  // code by AG start
  public function additional_service($id, Request $request){
    $misc = new MiscellaneousController();

    $queryResult['bgImg'] = $misc->getBreadcrumb();
    $queryResult['booking_id'] = $id;
    $details = EquipmentBooking::query()->find($id);
    
    
    if ($details) {
        $vendor__ = Vendor::find($details->vendor_id);
          $equipment_fields = EquipmentFieldsValue::where('equipment_id', $details->equipment_id)->first();

    		if($equipment_fields){
    			$multiple_charges_settings = json_decode($equipment_fields->multiple_charges_settings, true);
    		}
    		else{
    			$multiple_charges_settings = array();
    		}
    		
    	    if(isset($multiple_charges_settings['additional_service_cost']) && $multiple_charges_settings['additional_service_cost'] > 0){
    	        
    	        $queryResult['additional_service_cost'] = $multiple_charges_settings['additional_service_cost'];
    	        
    	        if(isset($_GET['process_additional_service']) && $_GET['process_additional_service'] == true){
                      
                    $additional_service_total = round(($multiple_charges_settings['additional_service_cost'] * $_GET['additional_services_count']), 2);
                      $add_invoice = new AdditionalInvoice();
                    $add_invoice->user_id = $details->user_id;
                    $add_invoice->vendor_id = $details->vendor_id;
                    $add_invoice->booking_id = $details->id;
                    $add_invoice->additional_day = now();
                    $add_invoice->amount = $additional_service_total;
                    $add_invoice->details = 'Temporary Toilet Additional Services';
                    $add_invoice->save();
                    
                    $mailData['subject'] = 'Temporary Toilet Additional Services Booked for Booking #'.$details->booking_number;

                    $mailData['body'] = 'Hi ' . $vendor__->username . ',<br/><br/>Booking (#'.$details->booking_number.')<br>Temporary Toilet Additional Services Booked';
            
                    $mailData['recipient'] = $vendor__->email;
            
                    $mailData['sessionMessage'] = 'Temporary Toilet Additional Services Booked successfully!';
                    $vendor__->notify(new BasicNotify($mailData['body']));
            
                    BasicMailer::sendMail($mailData);
                    
                    
                    $stax_payment_in = new StaxController();
                    return $stax_payment_in->swap_charge_payment($request,$additional_service_total, $details, $add_invoice->id);
                    
                    return redirect()->route('user.equipment_bookings')->with('success','Temporary Toilet Additional Services Booked.');
                      
                  }
                  else{
                      
                      return view('frontend.user.additional_service_process', $queryResult);
                  }
                  
    	    }
    	    else{
    	        $mailData['subject'] = 'Temporary Toilet Additional Services Booked for Booking #'.$details->booking_number;

                $mailData['body'] = 'Hi ' . $vendor__->username . ',<br/><br/>Booking (#'.$details->booking_number.')<br>Temporary Toilet Additional Services Booked';
        
                $mailData['recipient'] = $vendor__->email;
        
                $mailData['sessionMessage'] = 'Temporary Toilet Additional Services Booked successfully!';
                
                $vendor__->notify(new BasicNotify($mailData['body']));
        
                BasicMailer::sendMail($mailData);
                
                return redirect()->route('user.equipment_bookings')->with('success','Temporary Toilet Additional Services Booked.');
    	    }
    		
         
      }
      return view('errors.404');
  }
  
  
  // code by AG start
  public function relocate_equipment($id, Request $request){
    $misc = new MiscellaneousController();

    $queryResult['bgImg'] = $misc->getBreadcrumb();
    $queryResult['booking_id'] = $id;
    $details = EquipmentBooking::query()->find($id);
    
    
    if ($details) {
        $vendor__ = Vendor::find($details->vendor_id);
          $equipment_fields = EquipmentFieldsValue::where('equipment_id', $details->equipment_id)->first();

    		if($equipment_fields){
    			$multiple_charges_settings = json_decode($equipment_fields->multiple_charges_settings, true);
    		}
    		else{
    			$multiple_charges_settings = array();
    		}
    		
    	    if(isset($multiple_charges_settings['relocation_fee']) && $multiple_charges_settings['relocation_fee'] > 0){
    	        
    	        $queryResult['relocation_fee'] = $multiple_charges_settings['relocation_fee'];
    	        
    	        if(isset($_GET['process_relocation']) && $_GET['process_relocation'] == true){
                      
                      $add_invoice = new AdditionalInvoice();
                    $add_invoice->user_id = $details->user_id;
                    $add_invoice->vendor_id = $details->vendor_id;
                    $add_invoice->booking_id = $details->id;
                    $add_invoice->additional_day = now();
                    $add_invoice->amount = $multiple_charges_settings['relocation_fee'];
                    $add_invoice->details = 'Relocation Charge';
                    $add_invoice->save();
                    
                    
                    $booking_update = new BookingUpdate();
                    $booking_update->booking_id = $id;
                    $booking_update->status = "relocation_requested";
                    $booking_update->status_type = 'relocation_requested';
                    $booking_update->update_by_user_id = Auth::guard('web')->user()->id;
                    $booking_update->user_type = 'customer';
                     $booking_update->update_details = json_encode(array("relocation_address"=>$_GET['relocation_address'],"lat"=>$_GET['lat'],"long"=>$_GET['long']));
                    $booking_update->save();
                    
                    $details->shipping_status = 'pending';
                    $details->save();
                    
                    $driver_ = BookingDriver::where('booking_id',$id)->first();
                    $driver_->delete();
                    
                    $mailData['subject'] = 'Equipment relocation requested for Booking #'.$details->booking_number;

                    $mailData['body'] = 'Hi ' . $vendor__->username . ',<br/><br/>Booking (#'.$details->booking_number.')<br>Relocation Requested';
            
                    $mailData['recipient'] = $vendor__->email;
            
                    $mailData['sessionMessage'] = 'Relocation requested successfully!';
                    
                    $vendor__->notify(new BasicNotify($mailData['body']));
            
                    BasicMailer::sendMail($mailData);
                    
                    
                    $stax_payment_in = new StaxController();
                    return $stax_payment_in->swap_charge_payment($request,$multiple_charges_settings['relocation_fee'], $details, $add_invoice->id);
                    
                    return redirect()->route('user.equipment_bookings')->with('success','Relocation Requested.');
                      
                  }
                  else{
                      
                      return view('frontend.user.relocation_process', $queryResult);
                  }
                  
    	    }
    	    else{
    	        $booking_update = new BookingUpdate();
                $booking_update->booking_id = $id;
                $booking_update->status = "relocation_requested";
                $booking_update->status_type = 'relocation_requested';
                $booking_update->update_by_user_id = Auth::guard('web')->user()->id;
                $booking_update->user_type = 'customer';
                 $booking_update->update_details = json_encode(array("relocation_address"=>$_GET['relocation_address'],"lat"=>$_GET['lat'],"long"=>$_GET['long']));
                $booking_update->save();
                
                $details->shipping_status = 'pending';
                $details->save();
                
                $driver_ = BookingDriver::where('booking_id',$id)->first();
                $driver_->delete();
                
                $mailData['subject'] = 'Equipment relocation requested for Booking #'.$details->booking_number;

                $mailData['body'] = 'Hi ' . $vendor__->username . ',<br/><br/>Booking (#'.$details->booking_number.')<br>Relocation Requested';
        
                $mailData['recipient'] = $vendor__->email;
        
                $mailData['sessionMessage'] = 'Relocation requested successfully!';
                
                $vendor__->notify(new BasicNotify($mailData['body']));
        
                BasicMailer::sendMail($mailData);
                
                return redirect()->route('user.equipment_bookings')->with('success','Relocation Requested.');
    	    }
    		
         
      }
      return view('errors.404');
  }
  
  public function swap_equipment($id, Request $request)
  {     
      
      $misc = new MiscellaneousController();

    $queryResult['bgImg'] = $misc->getBreadcrumb();
    $queryResult['booking_id'] = $id;
    
      $details = EquipmentBooking::query()->find($id);
      $equipment_content = EquipmentContent::where('equipment_id', $details->equipment_id)->first();
      if ($details) {
          $vendor__ = Vendor::find($details->vendor_id);
          $equipment_fields = EquipmentFieldsValue::where('equipment_id', $details->equipment_id)->first();

    		if($equipment_fields){
    			$multiple_charges_settings = json_decode($equipment_fields->multiple_charges_settings, true);
    		}
    		else{
    			$multiple_charges_settings = array();
    		}
    		
    		
    		
    	    if(isset($multiple_charges_settings['swap_charge']) && $multiple_charges_settings['swap_charge'] > 0){
    	        
    	        $queryResult['swap_charge'] = $multiple_charges_settings['swap_charge'];
    	        
    	        if(isset($_GET['process_swap']) && $_GET['process_swap'] == true){
    	            
                      
                    $add_invoice = new AdditionalInvoice();
                    $add_invoice->user_id = $details->user_id;
                    $add_invoice->vendor_id = $details->vendor_id;
                    $add_invoice->booking_id = $details->id;
                    $add_invoice->additional_day = now();
                    $add_invoice->amount = $multiple_charges_settings['swap_charge'];
                    $add_invoice->details = 'Swap Charge';
                    $add_invoice->save();
                    
                    
                    $booking_update = new BookingUpdate();
                    $booking_update->booking_id = $id;
                    $booking_update->status = "swap_requested";
                    $booking_update->status_type = 'swap_requested';
                    $booking_update->update_by_user_id = Auth::guard('web')->user()->id;
                    $booking_update->user_type = 'customer';
                    $booking_update->save();
                    
                    $details->shipping_status = 'pending';
                    $details->save();
                    
                    $driver_ = BookingDriver::where('booking_id',$id)->first();
                    $driver_->delete();
                    
                    $mailData['subject'] = 'Equipment swap requested for Booking #'.$details->booking_number;

                    $mailData['body'] = 'Hi ' . $vendor__->username . ',<br/><br/>Booking (#'.$details->booking_number.')<br>Swap Requested';
            
                    $mailData['recipient'] = $vendor__->email;
            
                    $mailData['sessionMessage'] = 'Swap requested successfully!';
                    
                    $vendor__->notify(new BasicNotify($mailData['body']));
            
                    BasicMailer::sendMail($mailData);
                    
                    $mailData2['subject'] = 'Equipment Pickup Confirmation & Future Requests';

                    $mailData2['body'] = 'Dear ' . $details->name.",<br><br>";
                    $mailData2['body'] .= "We’ve received your request for a swap of equipment, and we’re here to assist you promptly. Your request details are as follows:<br><br>";
                    
                    $mailData2['body'] .= "Request Number: ".$details->booking_number."<br>";
                    $mailData2['body'] .= "Original Equipment: ".$equipment_content->decription."<br>";
                    $mailData2['body'] .= "Reason for Swap:: "."N/A"."<br>";
                    $mailData2['body'] .= "Preferred Replacement Equipment: ".$equipment_content->decription."<br><br>";
                    
                    $mailData2['body'] .= "Your return has been processed, and you should receive confirmation shortly.<br><br>";
                    $mailData2['body'] .= "We value your business and would like to remind you that if you have any future requests or equipment needs, please don’t hesitate to reach out to us. At CAT Dump, we’re committed to providing top-notch service to our customers.<br><br>";
                    $mailData2['body'] .= "Thank you for choosing CAT Dump. We look forward to assisting you again in the future.<br><br>";
                    
                    $mailData2['body'] .= "Best regards,<br>";
                    $mailData2['body'] .= $vendor__->username."<br><br>";
                    $mailData2['body'] .= "Customer Service Team<br>";
                    $mailData2['body'] .= "CAT Dump";


        
                    $mailData2['recipient'] = $details->email;
            
                    $mailData2['sessionMessage'] = 'Swap requested successfully!';
                    
                    $stax_payment_in = new StaxController();
                    return $stax_payment_in->swap_charge_payment($request,$multiple_charges_settings['swap_charge'], $details, $add_invoice->id);
                    
                    
                    
                    return redirect()->route('user.equipment_bookings')->with('success','Swap Requested.');
                      
                  }
                  else{
                      
                      return view('frontend.user.swap_confirmation', $queryResult);
                  }
                  
    	    }
    	    else{
    	        $booking_update = new BookingUpdate();
                $booking_update->booking_id = $id;
                $booking_update->status = "swap_requested";
                $booking_update->status_type = 'swap_requested';
                $booking_update->update_by_user_id = Auth::guard('web')->user()->id;
                $booking_update->user_type = 'customer';
                $booking_update->save();
                
                $details->shipping_status = 'pending';
                $details->save();
                
                $driver_ = BookingDriver::where('booking_id',$id)->first();
                $driver_->delete();
                
                $mailData['subject'] = 'Equipment swap requested for Booking #'.$details->booking_number;

                $mailData['body'] = 'Hi ' . $vendor__->username . ',<br/><br/>Booking (#'.$details->booking_number.')<br>Swap Requested';
        
                $mailData['recipient'] = $vendor__->email;
        
                $mailData['sessionMessage'] = 'Swap requested successfully!';
                
                $vendor__->notify(new BasicNotify($mailData['body']));
        
                BasicMailer::sendMail($mailData);
                
                $mailData2['subject'] = 'Equipment Pickup Confirmation & Future Requests';

                    $mailData2['body'] = 'Dear ' . $details->name.",<br><br>";
                    $mailData2['body'] .= "We’ve received your request for a swap of equipment, and we’re here to assist you promptly. Your request details are as follows:<br><br>";
                    
                    $mailData2['body'] .= "Request Number: ".$details->booking_number."<br>";
                    $mailData2['body'] .= "Original Equipment: ".$equipment_content->decription."<br>";
                    $mailData2['body'] .= "Reason for Swap:: "."N/A"."<br>";
                    $mailData2['body'] .= "Preferred Replacement Equipment: ".$equipment_content->decription."<br><br>";
                    
                    $mailData2['body'] .= "Your return has been processed, and you should receive confirmation shortly.<br><br>";
                    $mailData2['body'] .= "We value your business and would like to remind you that if you have any future requests or equipment needs, please don’t hesitate to reach out to us. At CAT Dump, we’re committed to providing top-notch service to our customers.<br><br>";
                    $mailData2['body'] .= "Thank you for choosing CAT Dump. We look forward to assisting you again in the future.<br><br>";
                    
                    $mailData2['body'] .= "Best regards,<br>";
                    $mailData2['body'] .= $vendor__->username."<br><br>";
                    $mailData2['body'] .= "Customer Service Team<br>";
                    $mailData2['body'] .= "CAT Dump";


        
                    $mailData2['recipient'] = $details->email;
            
                    $mailData2['sessionMessage'] = 'Swap requested successfully!';
                

                BasicMailer::sendMail($mailData2);
                
                return redirect()->route('user.equipment_bookings')->with('success','Swap Requested.');
    	    }
    		
         
      }
      return view('errors.404');
    
  }
  
  public function return_equipment($id)
  {
      $details = EquipmentBooking::query()->find($id);
      if ($details) {
           $vendor__ = Vendor::find($details->vendor_id);
           
        $booking_update = new BookingUpdate();
        $booking_update->booking_id = $id;
        $booking_update->status = "pickup_requested";
        $booking_update->status_type = 'pickup_requested';
        $booking_update->update_by_user_id = Auth::guard('web')->user()->id;
        $booking_update->user_type = 'customer';
        $booking_update->save();
        
        $details->shipping_status = 'pending';
        $details->save();
        
        $driver_ = BookingDriver::where('booking_id',$id)->first();
        $driver_->delete();
        
        $mailData['subject'] = 'Equipment pickup requested for Booking #'.$details->booking_number;

        $mailData['body'] = 'Hi ' . $vendor__->username . ',<br/><br/>Booking (#'.$details->booking_number.')<br>Pickup Requested';

        $mailData['recipient'] = $vendor__->email;

        $mailData['sessionMessage'] = 'Pickup requested successfully!';
        
        $vendor__->notify(new BasicNotify($mailData['body']));

        BasicMailer::sendMail($mailData);
        
        $mailData2['subject'] = 'Equipment Pickup Confirmation & Future Requests';

        $mailData2['body'] = 'Dear ' . $details->name.",<br><br>";
        $mailData2['body'] .= "We wanted to inform you that the equipment you requested for return has been successfully picked up. Here are the pickup details:<br><br>";
        
        $mailData2['body'] .= "Request Number: ".$details->name."<br>";
        $mailData2['body'] .= "Equipment: ".$details->name."<br>";
        $mailData2['body'] .= "Pickup Date: ".$details->start_date." ".$details->end_date."<br>";
        $mailData2['body'] .= "Pickup Address: ".$details->location."<br><br>";
        
        $mailData2['body'] .= "Your return has been processed, and you should receive confirmation shortly.<br><br>";
        $mailData2['body'] .= "We value your business and would like to remind you that if you have any future requests or equipment needs, please don’t hesitate to reach out to us. At CAT Dump, we’re committed to providing top-notch service to our customers.<br><br>";
        $mailData2['body'] .= "Thank you for choosing CAT Dump. We look forward to assisting you again in the future.<br><br>";
        
        $mailData2['body'] .= "Best regards,<br>";
        $mailData2['body'] .= $vendor__->username."<br><br>";
        $mailData2['body'] .= "Customer Service Team<br>";
        $mailData2['body'] .= "CAT Dump";



        $mailData2['recipient'] = $details->email;

        $mailData2['sessionMessage'] = 'Swap requested successfully!';
        
        return redirect()->back()->with('success','Return Requested.');
      }
      return view('errors.404');
    
  }
  // code by AG end

  public function orders()
  {
    $misc = new MiscellaneousController();

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    $authUser = Auth::guard('web')->user();

    $queryResult['orders'] = $authUser->productOrder()->orderByDesc('id')->get();

    return view('frontend.user.product-orders', $queryResult);
  }

  public function orderDetails($id)
  {
    $misc = new MiscellaneousController();

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    $language = $misc->getLanguage();

    $order = ProductOrder::query()->find($id);
    if ($order) {
      if ($order->user_id != Auth::guard('web')->user()->id) {
        return redirect()->route('user.dashboard');
      }

      $queryResult['details'] = $order;

      $queryResult['tax'] = Basic::select('product_tax_amount')->first();

      $items = $order->item()->get();

      $items->map(function ($item) use ($language) {
        $product = $item->productInfo()->first();
        $item['price'] = $product->current_price;
        $item['productType'] = $product->product_type;
        $item['inputType'] = $product->input_type;
        $item['link'] = $product->link;
        $content = $product->content()->where('language_id', $language->id)->first();
        $item['productTitle'] = $content->title;
        $item['slug'] = $content->slug;
      });

      $queryResult['items'] = $items;

      return view('frontend.user.order-details', $queryResult);
    } else {
      return view('errors.404');
    }
  }

  public function downloadProduct($id, Request $request)
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $product = Product::find($id);

    $slug = $product->content()->where('language_id', $language->id)->pluck('slug')->first();

    $pathToFile = public_path('assets/file/products/') . $product->file;

    try {
      return response()->download($pathToFile, $slug . '.zip');
    } catch (FileNotFoundException $e) {
      Session::flash('error', 'Sorry, this file does not exist anymore!');

      return redirect()->back();
    }
  }

  public function logoutSubmit(Request $request)
  {
    Auth::guard('web')->logout();

    if ($request->session()->has('redirectTo')) {
      $request->session()->forget('redirectTo');
    }

    return redirect()->route('user.login');
  }
  
  // code by AG start
  public function paymentMethods(){
    $misc = new MiscellaneousController();

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    $authUser = Auth::guard('web')->user();
    
    // $owner = User::find(auth()->user()->owner_id);
    // if(isset($owner))
    // {
    //     $queryResult['cards'] = UserCard::where('user_id', $owner->id)->get();
    // }else{
        
    // }
    $branches = BranchUser::where('user_id',$authUser->id)->get()->pluck('branch_id');
    
    $queryResult['cards'] = UserCard::where('user_id', $authUser->id)->orwhereIn('branch_id',$branches)->get();
    return view('frontend.user.payment-methods', $queryResult);
  }
  
public function addPaymentMethods(){
    $misc = new MiscellaneousController();

    $queryResult['bgImg'] = $misc->getBreadcrumb();
    $authUser = Auth::guard('web')->user();
    $queryResult['cards'] = UserCard::where('user_id', $authUser->id)->get();
    $queryResult['company'] = Company::where('customer_id', $authUser->id)->first();

    // Check if $queryResult['company'] is not null before accessing its 'id' property
    if ($queryResult['company']) {
        $queryResult['branches'] = CompanyBranch::where('company_id', $queryResult['company']->id)->get();
    } else {
        // Handle the case where $queryResult['company'] is null
        $queryResult['branches'] = []; // or any other default value
    }

    return view('frontend.user.add-payment-methods', $queryResult);
}

  
  public function storePaymentMethods(Request $request){
        $rules = [
          'first_name' => 'required',
          'last_name' => 'required',
          'card_number' => 'required',
          'cvv' => 'required',
          'exp_month' => 'required',
          'exp_year' => 'required',
          'address1' => 'required',
          'address2' => 'required',
          'location' => 'required',
          'city' => 'required'
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
          return redirect()->back()->withErrors($validator->errors());
        }
        
        $in = $request->all();
        $in['user_id'] = Auth::guard('web')->user()->id;
        
        
        
        $customer_email = Auth::guard('web')->user()->email;
        $stax_client = new StaxController();
        $stax_customer_exists = $stax_client->get_customer( $customer_email );
        
        $customer__ = Auth::guard('web')->user();
        $customer__update = User::find($in['user_id']);
        if($stax_customer_exists){
            
            $customer__update->stax_customer_id = $stax_customer_exists;
            $customer__update->save();
        }
        else{
            
            $customer_data = array(
              "firstname"=> $customer__->first_name,
              "lastname"=>  $customer__->last_name,
              //"company"=> "ABC INC",
              "email"=>  $customer__->email,
              //"cc_emails"=> ["demo@abc.com"],
              "phone"=>  $customer__->contact_number,
              "address_1"=>  $customer__->address,
              //"address_2"=> "Unit 12",
              "address_city"=>  $customer__->city,
              //"address_state"=>  $customer__->state,
              //"address_zip"=> "32801",
              //"address_country"=>  $customer__->country,
              "reference"=> "BARTLE"
              );
              
            $stax_customer_add = $stax_client->create_customer( $customer_data );
            
            if($stax_customer_add){
               
                $customer__update->stax_customer_id = $stax_customer_add;
                $customer__update->save();
            }
        }
        if(isset($in['is_default']))
        {
            if($in['is_default'] == 1)
            {
                $authUser = Auth::guard('web')->user();
                $get_cards = UserCard::where('user_id', $authUser->id)->where('is_default', 1)->first();
        
                if($get_cards){
                    $get_cards->is_default = 0;
                    $get_cards->update();
                }
                
            }
        }
        
        $card = UserCard::create($in);
        
        if($customer__update->stax_customer_id != ''){
            $payment_method_data = array(
                "method"=> "card",
                "person_name"=> $in['first_name']." ".$in['last_name'],
                "card_number"=> $in['card_number'],
                "card_cvv"=> $in['cvv'],
                "card_exp"=> $in['exp_month']."".substr($in['exp_year'],2),
                "customer_id"=> $customer__update->stax_customer_id
                );
            $add_payment_method = $stax_client->create_payment_method( $customer__update->stax_customer_id, $payment_method_data );
            
            if($add_payment_method){
                $card->stax_payment_method_id = $add_payment_method;
                $card->save();
            }
        }
        
        
        
        return redirect()->route('user.payment_methods')->with('success', 'Payment Method Added Successfully!');

  }
  
  public function editPaymentMethods($id){
        $card = UserCard::find($id);
        $user_id = Auth::guard('web')->user()->id;
        
        if(empty($card) || $card->user_id != $user_id){
          return redirect()->back();
        }
      
        $misc = new MiscellaneousController();

        $queryResult['bgImg'] = $misc->getBreadcrumb();
        $queryResult['card'] = $card;
        $authUser = Auth::guard('web')->user();
        $queryResult['company'] = Company::where('customer_id', $authUser->id)->first();
        if(auth()->user()->account_type == "corperate_account"){
        $queryResult['branches'] = CompanyBranch::where('company_id', $queryResult['company']->id)->get();
        }
        return view('frontend.user.edit-payment-methods', $queryResult);
  }
  
  public function deletePaymentMethods($id){
      $is_default = "";
      $card = UserCard::find($id);
        $user_id = Auth::guard('web')->user()->id;
        
        if(empty($card) || $card->user_id != $user_id){
          return redirect()->back();
        }
        
        $stax_client = new StaxController();
        $stax_client->delete_payment_method($card->stax_payment_method_id);
        if($card->is_default == '1')
        {
            $is_default = true;
        }
        $card->delete();
        $new_card = UserCard::where('user_id',$user_id)->first();
        if($new_card)
        {
            $new_card->is_default = 1 ; 
            $new_card->update();
        }
        return redirect()->back()->with('success', 'Payment Method deleted successfully!');
  }
  
  public function updatePaymentMethods($id, Request $request){
      $card = UserCard::find($id);
        $user_id = Auth::guard('web')->user()->id;
        
        if(empty($card) || $card->user_id != $user_id){
          return redirect()->back();
        }
        
      $rules = [
          'first_name' => 'required',
          'last_name' => 'required',
          'card_number' => 'required',
          'cvv' => 'required',
          'exp_month' => 'required',
          'exp_year' => 'required',
          'address1' => 'required',
          'address2' => 'required',
          'city' => 'required'
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
          return redirect()->back()->withErrors($validator->errors());
        }
        
        
        $in = $request->all();
        
        if(isset($in['is_default']))
        {
            if($in['is_default'] == 1)
            {
                $authUser = Auth::guard('web')->user();
                $get_cards = UserCard::where('user_id', $authUser->id)->where('is_default',1)->first();
                if($get_cards){
                    $get_cards->is_default = 0;
                    $get_cards->update();
                }
            }
            if($in['is_default'] != 1)
            {
                $in['is_default'] = 0;
            }
        }
        
        
        $update_card = $card->update($in);
        return redirect()->route('user.payment_methods')->with('success', 'Payment Method Updated Successfully!');
  }
  // code by AG end
  
  
    

}
