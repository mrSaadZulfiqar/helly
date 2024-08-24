<?php

namespace App\Http\Controllers\BackEnd\Membership;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\PlanFeature;
use App\Models\PlanVendor;
use App\Models\MembershipPlan;
use App\Models\FeaturePlan;

class MemberShipPlans extends Controller
{
    public function index()
    {
        $plans = MembershipPlan::paginate(10);
        return view('backend.membership_plans.index',compact('plans'));
    }
    
    public function create()
    {
        return view('backend.membership_plans.create');
    }
    
    public function store(Request $request)
    {
        
        $check_plan = MembershipPlan::where('level',$request->level)->get();
        if(count($check_plan) > 0)
        {
            return redirect()->back()->with('warning','This level is already in use.');
        }
        $plan = new MembershipPlan();
        $plan->name = $request->name;
        $plan->validity = $request->validity;
        $plan->description = $request->description;
        $plan->price = $request->price;
        $plan->trial_days = $request->trial_days;
        $plan->level = $request->level;
        $plan->status = $request->status;
        $plan->save();
        return redirect()->route('admin.plans.index')->with('success','Plan Created Successfully!');
    }
    
    public function edit($id)
    {
        $plan = MembershipPlan::find($id);
        return view('backend.membership_plans.edit',compact('plan'));
    }
    
    public function update(Request $request,$id)
    {
        
        
        $check_plan = MembershipPlan::where('level',$request->level)->where('id','!=',$id)->get();
        if(count($check_plan) > 0)
        {
            return redirect()->back()->with('warning','This level is already in use.');
        }
        
        $plan = MembershipPlan::find($id);
        $plan->name = $request->name;
        $plan->validity = $request->validity;
        $plan->description = $request->description;
        $plan->price = $request->price;
        $plan->status = $request->status;
        $plan->trial_days = $request->trial_days;
        $plan->level = $request->level;
        $plan->update();
        return redirect()->route('admin.plans.index')->with('success','Plan Updated Successfully!');
    }
    
    public function destroy($id)
    {
        
        $assign_plan = PlanVendor::where('plan_id',$id)->get();
        if(count($assign_plan) > 0)
        {
            return redirect()->back()->with('warning','You are not able to delete this plan.');
        }
        $plan = MembershipPlan::find($id);
        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success','Plan Deleted Successfully!');
    }
    
    public function features($id)
    {
        $features = PlanFeature::where('status',1)->get();
        $plan = MembershipPlan::find($id);
        $features_plan = FeaturePlan::where('plan_id',$id)->get();
        return view('backend.membership_plans.features',compact('features','plan','features_plan'));
    }
    
   public function features_store(Request $request, $id)
    {
        $features = $request->features;
        if (!is_array($features)) {
            $features = [];
        }
        $existingFeatureIds = FeaturePlan::where('plan_id', $id)->pluck('feature_id')->toArray();
        if (count($features) > 0) {
            FeaturePlan::where('plan_id', $id)->whereNotIn('feature_id', $features)->delete();
        } else {
            FeaturePlan::where('plan_id', $id)->delete();
        }
        foreach ($features as $feature) {
            if (!in_array($feature, $existingFeatureIds)) {
                $feature_plan = new FeaturePlan();
                $feature_plan->plan_id = $id;
                $feature_plan->feature_id = $feature;
                $feature_plan->save();
            }
        }
        return redirect()->route('admin.plans.index')->with('success', 'Features Updated Successfully!');
    }




    
}