<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Models\BasicSettings\Basic;
use App\Http\Controllers\FrontEnd\SubscriptionController;
use App\Models\PaymentGateway\OnlineGateway;

class CheckPlanFeaturesAccess
{
    public function handle(Request $request, Closure $next)
    {

        return $next($request);

        $data = OnlineGateway::whereKeyword('stax')->first();
        $staxData = json_decode($data->information, true);

        $key = $staxData['key'];

        $settings = Basic::where('id', 2)->first('subscription_enable');
        if ($settings->subscription_enable == "0") {
            return $next($request);
        }
        // get current url 
        $url = $request->url();

        $access_url = [
            env('APP_URL') . "/vendor/logout",
            env('APP_URL') . "/vendor/change-password",
            env('APP_URL') . "/vendor/edit-profile?language=en",
            env('APP_URL') . "/vendor/dashboard",
            env('APP_URL') . "/vendor/read-notifications",
            env('APP_URL') . "/vendor/invoice",
            env('APP_URL') . "/vendor/save-vendor-interest",
            env('APP_URL') . "/vendor/equipment-booking/bookings/get_user_data",
            env('APP_URL') . "/vendor/equipment-booking/bookings/get_cards",
            env('APP_URL') . "/vendor/edit-profile",
            env('APP_URL') . "/vendor/equipment-booking/bookings/equipment",
            env('APP_URL') . "/vendor/equipment-booking/add-customer-from-booking",
            env('APP_URL') . "/vendor/equipment-booking/store-customer-card-ajax",
        ];

        if (in_array($url, $access_url)) {
            return $next($request);
        }

        $currentUrl = preg_replace('/\{.*?\}/', '*', $url); // Replace dynamic segments with wildcard (*)

        // Get user memebrship plans and feature
        $id = Auth::guard('vendor')->user()->id;
        $vendor = Vendor::with('membership_plans', 'membership_plans.plan_features')->find($id);
        if (count($vendor->membership_plans()->where('membership_plans.status', 1)->wherePivot('status', 1)->get()) > 0) {
            foreach ($vendor->membership_plans()->where('membership_plans.status', 1)->wherePivot('status', 1)->get() as $membership_plan) {

                if ($membership_plan->pivot->is_trial_active == 0 && $membership_plan->pivot->payment_status == 'pending') {
                    $arrData = array(
                        'vendor_id' => $id,
                        'plan_id' => $membership_plan->id,
                        'expiration_date' => $membership_plan->expiration_date,
                        'status' => 1,
                        'payment_status' => 'complete',
                        'complete_payment' => $membership_plan->pivot->id,
                    );
                    $paymentFor = 'plan purchase';
                    $request->session()->put('paymentFor', $paymentFor);
                    $request->session()->put('arrData', $arrData);

                    $public_key = $key;
                    $notifyURL = route('subscription.notify');
                    $is_trial_days = false;
                    $plan = $membership_plan;
                    $is_comp_payment = true;
                    return response(view('frontend.subscription.stax', compact('plan', 'public_key', 'notifyURL', 'is_trial_days', 'is_comp_payment')));
                } else {
                    if ($membership_plan->pivot->expiration_date >= Carbon::now()) {
                        if (count($membership_plan->plan_features) > 0) {
                            foreach ($membership_plan->plan_features as $plan_features) {
                                // Check if the permission matches ignoring dynamic segments
                                $plan_feature_url = url('/') . $plan_features->url;
                                if (fnmatch($plan_feature_url, $currentUrl)) {
                                    return $next($request);
                                }
                            }
                        } else {
                            return response(view('errors.access_denied'));
                        }
                    } else {
                        return response(view('errors.access_denied'));
                    }
                }
            }
        } else {
            return response(view('errors.access_denied'));
        }
        return response(view('errors.access_denied'));
    }
}
