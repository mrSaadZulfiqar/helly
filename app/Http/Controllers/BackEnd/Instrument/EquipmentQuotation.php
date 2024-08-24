<?php

namespace App\Http\Controllers\BackEnd\Instrument;

use App\Http\Controllers\Controller;

use App\Http\Helpers\BasicMailer;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Instrument\Equipment;
use App\Models\Instrument\EquipmentContent;
use App\Models\Language;
use App\Models\EquipmentQuote;
use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class EquipmentQuotation extends Controller
{
    public function quotations(Request $request)
  {

    $vendor = null;

    if ($request->filled('vendor')) {
      $vendor = $request['vendor'];
    }

    $quotations = EquipmentQuote::query()
		->leftJoin('vendors', 'vendors.id', '=', 'equipment_quotes.vendor_id')
      ->when($vendor, function ($query, $vendor) {
        if ($vendor == 'admin') {
          return $query->where('vendor_id', '=', NULL);
        } elseif ($vendor != 'all') {
          return $query->where('vendor_id', '=', $vendor);
        }
      })
	  ->select('equipment_quotes.*','vendors.id as vid', 'vendors.username as vusername')
      ->orderByDesc('id')
      ->paginate(10);
	
	$language = Language::where('code','en')->first();
	$quotations->map(function ($quotation) use ($language) {
	    if( $quotation->equipment_id != ''){
	            $equipment = EquipmentContent::where('language_id', $language->id)->where('equipment_id', $quotation->equipment_id)->first();
              $quotation['equipmentTitle'] = $equipment->title;
        	  $quotation['equipmentSlug'] = $equipment->slug;
	    }
        else{
            $quotation['equipmentTitle'] = '';
        	  $quotation['equipmentSlug'] = '';
        }
    });

    $information['vendors'] = Vendor::where('status', 1)->get();

    $information['quotations'] = $quotations;

    return view('backend.instrument.quotation.index', $information);
  }
  
  public function show($id, Request $request)
  {
	$language = Language::where('code','en')->first();
	$information = array();
    $information['details'] = EquipmentQuote::leftJoin('vendors', 'vendors.id', '=', 'equipment_quotes.vendor_id')
	->select('equipment_quotes.*','vendors.id as vid', 'vendors.username as vusername')
	->find($id);
	
	if($information['details']->equipment_id != ''){
	    $equipment = EquipmentContent::where('language_id', $language->id)->where('equipment_id', $information['details']->equipment_id)->first();
    	$information['equipmentTitle'] = $equipment->title;
    	$information['equipmentSlug'] = $equipment->slug;

	}
	else{
	    $information['equipmentTitle'] = '';
    	$information['equipmentSlug'] = '';
	}
	
    return view('backend.instrument.quotation.details', $information);
  }
  
  public function destroy($id)
  {
    $quotation = EquipmentQuote::find($id);

    $quotation->delete();

    return redirect()->back()->with('success', 'Quotation deleted successfully!');
  }
  
  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $quotation = EquipmentQuote::find($id);

      $quotation->delete();
    }

    Session::flash('success', 'Quotations deleted successfully!');

    return response()->json(['status' => 'success'], 200);
  }
}
