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

class CheckVendorPlan
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
        // Get user memebrship plans and feature
        $id = Auth::guard('vendor')->user()->id;
        $vendor = Vendor::with('membership_plans', 'membership_plans.plan_features')->find($id);
        if(count($vendor->membership_plans) > 0)
        {
           return $next($request);
        }
        else{
            return redirect('/subscription')->with('error','Please purchase a plan first.');
        }
    }
}
