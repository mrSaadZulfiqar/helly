<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\BasicSettings\Basic;
use Illuminate\Http\Request;
use App\Models\MembershipPlan;
use App\Models\PlanVendor;
use App\Models\Vendor;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Helpers\BasicMailer;
use App\Models\PaymentGateway\OnlineGateway;
class SubscriptionController extends Controller
{
    private $key;
    private $server_api_key;
    
    public function __construct()
    {
        $data = OnlineGateway::whereKeyword('stax')->first();
        $staxData = json_decode($data->information, true);
        
        $this->key = $staxData['key'];
        $this->server_api_key = $staxData['serverapikey'];
    }
    public function index(Request $request)
    {
        $misc = new MiscellaneousController();
        
        $language = $misc->getLanguage();
        
        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_blog', 'meta_description_blog')->first();
        
        $queryResult['pageHeading'] = $misc->getPageHeading($language);
        
        $queryResult['bgImg'] = $misc->getBreadcrumb();
        
        $queryResult['allPlans'] = MembershipPlan::with('plan_features')->where('status', 1)->get();

        return view('frontend.subscription.index', $queryResult);
    }
    
    public function pay_if_trial_days(Request $request)
    {
        $check_plan_trial = PlanVendor::where('vendor_id', Auth::guard('vendor')->user()->id)->where('plan_id',$request->plan_id)->get();
        if(count($check_plan_trial) > 0 )
        {
            return redirect()->back()->with('error','The free trial for this plan has already been utilized.');
        }
        
        $vendorId = Auth::guard('vendor')->user()->id;
        $vendor = Vendor::with('membership_plans')->find($vendorId);
        $plan = MembershipPlan::find($request->plan_id);
        $check_plan = PlanVendor::where('vendor_id', $vendorId)->update(['status' => 0]);
        
        $arrData = array(
            'vendor_id' => $vendorId,
            'plan_id' => $request->plan_id,
            'expiration_date' => $plan->expiration_date,
            'status' => 1,
            'payment_status' => 'pending'
        );
        $this->store($arrData);
        
        $mailData['subject'] = 'New Plan Purchased Successfully!';
        $mailData['body'] = 'Your trial period has started from today!';
        $mailData['recipient'] = $vendor->email;
        BasicMailer::sendMail($mailData);
        
        $misc = new MiscellaneousController();
    
        $queryResult['bgImg'] = $misc->getBreadcrumb();
    
        $queryResult['bookingType'] = 'Membership Purchase';
    
        if (session()->has('shippingMethod')) {
          session()->forget('shippingMethod');
        }
    
        return view('frontend.payment.booking-success', $queryResult);
    }
    public function pay(Request $request)
    {
        
   
        // Check if the vendor contains membership plans
        // if(count($vendor->membership_plans) > 0){
        //     foreach($vendor->membership_plans as $membership_plan){
        //         if($membership_plan->pivot->expiration_date >= Carbon::now()){
        //             Session::flash('error', 'Sorry, your plan already has been activated.');
        //             return redirect()->back();
        //         }
        //     }
        // }
        
        $vendorId = Auth::guard('vendor')->user()->id;
        $vendor = Vendor::with('membership_plans')->find($vendorId);
        $plan = MembershipPlan::find($request->plan_id);
        
        $arrData = array(
            'vendor_id' => $vendorId,
            'plan_id' => $request->plan_id,
            'expiration_date' => $plan->expiration_date,
            'status' => 1,
            'payment_status' => 'complete'
        );
        $paymentFor='plan purchase';
        $request->session()->put('paymentFor', $paymentFor);
        $request->session()->put('arrData', $arrData);
         
        $public_key = $this->key;
        $notifyURL = route('subscription.notify');
        $is_trial_days = false;
        $check_plan_trial = PlanVendor::where('vendor_id', Auth::guard('vendor')->user()->id)->where('plan_id',$request->plan_id)->get();
        if(count($check_plan_trial) > 0 && $plan->trial_days > 0 )
        {
            $is_trial_days = false;   
        }
        
        return view('frontend.subscription.stax', compact('plan','public_key','notifyURL','is_trial_days'));
    }
    
    public function notify(Request $request)
    {
        // get the information from session
        $paymentPurpose = $request->session()->get('paymentFor');
    
        $arrData = $request->session()->get('arrData');
    
        $urlInfo = $request->all();
    
        // assume that the transaction was successful
        $success = true;

        if ($success === true) {
          // remove this session datas
          $request->session()->forget('paymentFor');
          $request->session()->forget('arrData');
    
      
          if ($paymentPurpose == 'plan purchase') {
              
            // store equipment booking information in database
            $bookingInfo = $this->store($arrData);
            
            // $vendor = Vendor::find($bookingInfo['vendor_id']);
            // $mailData['subject'] = 'New Booking Received';
            // $mailData['body'] = "New Booking Received";
            // $mailData['recipient'] = $vendor->email;
            // BasicMailer::sendMail($mailData);
    
            // generate an invoice in pdf format
            // $invoice = $bookingProcess->generateInvoice($bookingInfo);
    
            //calculate commission start

    
            return $this->complete('Membership Purchase');
          }
        } else {
          $request->session()->forget('paymentFor');
          $request->session()->forget('arrData');
          $request->session()->forget('razorpayOrderId');
    
        if ($paymentPurpose == 'plan purchase') {
            // remove session data
            $request->session()->forget('totalPrice');
            $request->session()->forget('equipmentDiscount');
    
            return redirect()->route('equipment.make_booking.cancel');
          }
        }
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
      
    
    public function store($arrData)
    {
        $check_plan = PlanVendor::where('vendor_id', $arrData['vendor_id'])->update(['status' => 0]);
        
        if(isset($arrData['complete_payment']))
        {
            $plan_vendor = PlanVendor::find($arrData['complete_payment']);
            $plan_vendor->is_trial_active = 0;
            $plan_vendor->payment_status = 'complete';
            $plan_vendor->status = 1;
            $plan_vendor->update();
            
        }else{
            $plan_vendor = new PlanVendor();
            $plan_vendor->plan_id = $arrData['plan_id'];
            $plan = MembershipPlan::find($arrData['plan_id']);
            $date = Carbon::now();
            $date->addDays($plan->validity);
            $plan_vendor->expiration_date = $date;
            $plan_vendor->vendor_id = $arrData['vendor_id'];
            $plan_vendor->trial_days = $plan->trial_days;
            $plan_vendor->payment_status = $arrData['payment_status'];
            if($plan->trial_days > 0){
                $plan_vendor->is_trial_active = 1;
            }else{
                $plan_vendor->is_trial_active = 0;
            }
            $plan_vendor->status = 1;
            $plan_vendor->save();
        }

        
        
        
        $misc = new MiscellaneousController();
        
        $language = $misc->getLanguage();
        
        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_blog', 'meta_description_blog')->first();
        
        $queryResult['pageHeading'] = $misc->getPageHeading($language);
        
        $queryResult['bgImg'] = $misc->getBreadcrumb();
        $queryResult['purchaseType'] = "online";
        
        return view('frontend.subscription.purchase-success', $queryResult);
    }
    

    public function show()
    {
        $misc = new MiscellaneousController();
        
        $language = $misc->getLanguage();
        
        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_blog', 'meta_description_blog')->first();
        
        $queryResult['pageHeading'] = $misc->getPageHeading($language);
        
        $queryResult['bgImg'] = $misc->getBreadcrumb();
        $vendorId = Auth::guard('vendor')->user()->id;
        $queryResult['allPlans'] = Vendor::with('membership_plans', 'membership_plans.plan_features')->find($vendorId);
        

        return view('frontend.subscription.show', $queryResult);
    }

    public function purchase_success()
    {
        $misc = new MiscellaneousController();
        
        $language = $misc->getLanguage();
        
        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_blog', 'meta_description_blog')->first();
        
        $queryResult['pageHeading'] = $misc->getPageHeading($language);
        
        $queryResult['bgImg'] = $misc->getBreadcrumb();


        return view('frontend.subscription.purchase-success');
    }
    
    // public function checking(Request $request) {
        
    //     // get current url 
    //     $url = $request->url();
    //     $currentUrl = preg_replace('/\{.*?\}/', '*', $url); // Replace dynamic segments with wildcard (*)
  
    //     // Get user memebrship plans and feature
    //     $id = Auth::guard('vendor')->user()->id;
    //     $vendor = Vendor::with('membership_plans', 'membership_plans.plan_features')->find($id);
    //     if(count($vendor->membership_plans) > 0){
    //         foreach($vendor->membership_plans as $membership_plan){
    //             if($membership_plan->pivot->expiration_date >= Carbon::now()){
    //                 if(count($membership_plan->plan_features) > 0){
    //                     foreach($membership_plan->plan_features as $plan_features){
    //                         // Check if the permission matches ignoring dynamic segments
    //                         if (fnmatch($plan_features->url, $currentUrl)) {
    //                             // return $next($request);
    //                              return "match"; 
    //                         }
    //                     }
    //                 }else{
    //                     return "notmatch"; 
    //                 }
    //             }else{
    //                 return "notmatch"; 
    //             }
    //         }
    //     }else{
    //         return "notmatch"; 
    //     }
    // }
    
    public function check_trial_days()
    {
            $vendor_ids = PlanVendor::groupby('vendor_id')->get()->pluck('vendor_id');
            $vendors = Vendor::with('membership_plans', 'membership_plans.plan_features')->whereIn('id',$vendor_ids)->get();
            foreach($vendors as $vendor)
            {
                $active_plan = $vendor->membership_plans()->wherePivot('status', 1)->get();
                
                foreach ($active_plan as $plan) {
                    $created_at = Carbon::parse($plan->pivot->created_at);
                    $current_date = Carbon::now();
                    $difference = $current_date->diffInDays($created_at);
                    
                    if ($difference > $plan->pivot->trial_days) {
                            if($plan->pivot->is_trial_active == 1)
                            {
                                $mailData['subject'] = 'Your trial period has ended!';
                                $mailData['body'] = 'Your trial period has ended!';
                                $mailData['recipient'] = 'tomlenelti@gufum.com';
                                BasicMailer::sendMail($mailData);
                                $plan->pivot->update(['is_trial_active' => 0]);
                            }
                    }
                }
            }
        
    }

}
