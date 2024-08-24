<?php

namespace App\Http\Controllers\BackEnd\BasicSettings;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CommissionController extends Controller
{
    public function index()
    {
        $commission = Commission::first();
        return view('backend.basic-settings.commission', compact('commission'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'equipment_commission' => 'required'
        ]);
        $commission = Commission::first();

        if (empty($commission)) {
            Commission::query()->create($request->all());
        } else {
            $commission->update($request->all());
        }

        Session::flash('success', 'Commission updated successfully!');

        return redirect()->back();
    }
}
