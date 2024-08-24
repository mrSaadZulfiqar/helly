<?php

namespace App\Http\Controllers\BackEnd\Custom;

use App\Http\Controllers\Controller;
use App\Models\ShippingStatus;
use App\Models\Language;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ShippingStatusController extends Controller
{
  public function index()
  {
    // get the coupons from db
    $information['shippingStatus'] = ShippingStatus::orderByDesc('id')->get();

    $language = Language::query()->where('is_default', '=', 1)->first();

    return view('backend.shipping-status.index', $information);
  }

  public function store(Request $request)
  {

    ShippingStatus::create($request->all());

    Session::flash('success', 'New shipping status added successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  public function update(Request $request)
  {
    ShippingStatus::find($request->id)->update($request->all());

    Session::flash('success', 'Shipping status updated successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    ShippingStatus::find($id)->delete();

    return redirect()->back()->with('success', 'Shipping status deleted successfully!');
  }
}
