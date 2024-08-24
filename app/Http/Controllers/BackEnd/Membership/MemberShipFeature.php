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

class MemberShipFeature extends Controller
{
    public function index()
    {
        $features = PlanFeature::paginate(10);
        return view('backend.membership_features.index',compact('features'));
    }
    
    public function create()
    {
        return view('backend.membership_features.create');
    }
    
    public function store(Request $request)
    {
        $feature = new PlanFeature();
        $feature->name = $request->name;
        $feature->url = $request->url;
        $feature->status = $request->status;
        $feature->save();
        return redirect()->route('admin.features.index')->with('success','Feature Created Successfully!');
    }
    
    public function edit($id)
    {
        $feature = PlanFeature::find($id);
        return view('backend.membership_features.edit',compact('feature'));
    }
    
    public function update(Request $request,$id)
    {
        $feature = PlanFeature::find($id);
        $feature->name = $request->name;
        $feature->url = $request->url;
        $feature->status = $request->status;
        $feature->update();
        return redirect()->route('admin.features.index')->with('success','Feature Updated Successfully!');
    }
    
    public function destroy($id)
    {
        $feature = PlanFeature::find($id);
        $feature->delete();
        return redirect()->route('admin.features.index')->with('success','Feature Deleted Successfully!');
    }
    
}