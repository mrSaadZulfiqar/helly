<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVendorRequest;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\Admin;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Instrument\Equipment;
use App\Models\Instrument\EquipmentBooking;
use App\Models\Language;
use App\Models\Transcation;
use App\Models\Vendor;
use App\Models\VendorInfo;
use App\Rules\MatchEmailRule;
use App\Rules\MatchOldPasswordRule;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use PHPMailer\PHPMailer\PHPMailer;



class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::all();
        return response()->json([
            'vendors' => $vendors
        ]);
    }
    
    public function store(Request $request)
    {
        $vendor = Vendor::create($request->all());
    
        return response()->json([
            'message' => "Vendor saved successfully!",
            'vendor' => $vendor
        ], 200);
    }
    
    public function update(StoreVendorRequest $request, Vendor $vendor)
    {
        $vendor->update($request->all());
    
        return response()->json([
            'message' => "Vendor updated successfully!",
            'vendor' => $vendor
        ], 200);
    }
    
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
    
        return response()->json([
            'message' => "Vendor deleted successfully!",
        ], 200);
    }
}
