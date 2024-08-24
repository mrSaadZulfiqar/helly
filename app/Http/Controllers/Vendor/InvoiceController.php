<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index(Request $request){
		$language = Language::where('code', $request->language)->first();

        $information['langs'] = Language::all();

        $information['invoices'] = array(
								array('id'=>1)
								);
			
		return view('vendors.v1.invoice.index', $information);
	}
	
	public function recurring_invoices(){
		
	}
	
	public function create(){
		$information['customers'] = User::where('vendor_id',auth()->user()->id)->get();
		$information['currencyInfo'] = $this->getCurrencyInfo();

        $languages = Language::all();

        $information['languages'] = $languages;

        return view('vendors.v1.invoice.create', $information);
	}
	
	public function store(){
		
	}
	
	public function edit(){
		
	}
	
	public function update(){
		
	}
	
	public function delete(){
		
	}
	
}
