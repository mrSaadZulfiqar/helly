<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\Instrument\Location;
use App\Models\Language;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\AdditionalAddress;
use App\Models\Instrument\EquipmentCategory; // code by AG
use App\Http\Helpers\BasicMailer;
class LocationController extends Controller
{
    public function index(Request $request)
    {
        // first, get the language info from db
        $language = Language::query()->where('code', '=', $request->language)->first();
        $information['language'] = $language;

        // then, get the locations of that language from db
        $information['locations'] = $language->location()->where('vendor_id', Auth::guard('vendor')->user()->id)->orderByDesc('id')->get();

        $information['currencyInfo'] = $this->getCurrencyInfo();

        // also, get all the languages from db
        $information['langs'] = Language::all();
        
        $information['equipment_categories'] = EquipmentCategory::all();

        $information['twoWayDeliveryStatus'] = Vendor::query()->where('id', Auth::guard('vendor')->user()->id)->pluck('two_way_delivery_status')->first();

        return view('vendors.location.index', $information);
    }

    public function store(Request $request)
    {
        $twoWayDeliveryStatus = Vendor::query()->where('id', Auth::guard('vendor')->user()->id)->pluck('two_way_delivery_status')->first();

        $rules = [
            'language_id' => 'required',
            'name' => 'required',
            'charge' => $twoWayDeliveryStatus == 1 && $request['rate_type'] == 'flat_rate' ? 'required' : '',
            // 'serial_number' => 'required',
            'equipment_category_id' => 'required',
            'rate_type' => 'required',
            'radius' => 'required',
            'distance_rate' => $twoWayDeliveryStatus == 1 && $request['rate_type'] == 'rate_by_distance' ? 'required' : '',
        ];

        $message = [
            'language_id.required' => 'The language field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }
        $in = $request->all();

        $in['vendor_id'] = Auth::guard('vendor')->user()->id;
        $additional = AdditionalAddress::where('vendor_id',auth()->user()->id)->where('address',$request->name)->first(); 
        $in['additional_address'] = 0; // $additional->id;
        //$in['zipcodes'] = json_encode($request->zipcodes); 

        $location = Location::create($in);
        
              
        $mailData['subject'] = 'New Location Added to Equipment Vendor Profile';

        $mailData['body'] = 'Dear '.Auth::guard('vendor')->user()->username.',<br><br>';
        $mailData['body'] .= "We are writing to inform you that a new location has been added to the equipment vendor profile in our system. <br><br>";
        $mailData['body'] .= "Details of the new location: <br><br>";
        $mailData['body'] .= "<ul><li>Vendor Name: ".Auth::guard('vendor')->user()->username."</li><li>Location: ".$location->location_name."</li><li>Address : ".$location->name."</li><li>Contact Information : ".Auth::guard('vendor')->user()->email."</li></ul><br><br>";
        $mailData['body'] .= "This addition aims to provide our team with more options and accessibility when sourcing equipment and services. Please update your records accordingly to ensure seamless communication and procurement processes. <br><br>";
        $mailData['body'] .= "If you have any questions or require further information, feel free to reach out to our team. <br><br>";
        $mailData['body'] .= "Thank you for your attention to this matter. <br><br>";
        $mailData['body'] .= "Best regards,<br>";
        $mailData['body'] .= "CAT Dump";
        
        $mailData['recipient'] = Auth::guard('vendor')->user()->email;


        BasicMailer::sendMail($mailData);

        Session::flash('success', 'New location added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $twoWayDeliveryStatus = Vendor::query()->where('id', Auth::guard('vendor')->user()->id)->pluck('two_way_delivery_status')->first();
        
        $rules = [
            'name' => 'required',
            'charge' => $twoWayDeliveryStatus == 1 && $request['rate_type'] == 'flat_rate' ? 'required' : '',
            // 'serial_number' => 'required',
            'equipment_category_id' => 'required',
            'rate_type' => 'required',
            'radius' => 'required',
            'location_name' => 'required',
            'distance_rate' => $twoWayDeliveryStatus == 1 && $request['rate_type'] == 'rate_by_distance' ? 'required' : '',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }
        $location = Location::query()->find($request->id);

        $in = $request->all();
        
        
        $in['vendor_id'] = Auth::guard('vendor')->user()->id;
        $additional = AdditionalAddress::where('vendor_id',auth()->user()->id)->where('address',$request->name)->first(); 
        $in['additional_address'] =  0; //$additional->id;
        //$in['zipcodes'] = json_encode($request->zipcodes); 

        $location->update($in);
        
        Session::flash('success', 'Location updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $location = Location::query()->find($id);

        $location->delete();

        return redirect()->back()->with('success', 'Location deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $location = Location::query()->find($id);

            $location->delete();
        }

        Session::flash('success', 'Locations deleted successfully!');

        return Response::json(['status' => 'success'], 200);
    }
}
