<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BackEnd\Custom\OptionController;
use App\Models\Lead;
use App\Models\Vendor;
use App\Models\User;
use App\Models\AdminInfo;
use App\Models\CallLog;

class VoximplantController extends Controller
{
    //
    public function phone_convert($phone){
        $phone = str_replace(' ', '', $phone); // Replaces all spaces with hyphens.
        $phone = preg_replace('/[^A-Za-z0-9]/', '', $phone); // Removes special chars.
        return $phone;
    }
    
    public function lead_calling($id){
        $options = new OptionController();
        $communication_settings = $options->get_options();
        
		$lead = Lead::find($id);
		$lead_id = $lead->id;
		$team_id = Auth::user()->id;
		
        $phone_ = $this->phone_convert($_GET['phone_']);
        
        
        $admin_info = AdminInfo::where('user_id',$team_id)->first(); 
        
		$team_voximplant_phone = $this->phone_convert( $admin_info->voximplant_phone );
		
		$team_member_number = $this->phone_convert( $admin_info->voximplant_phone );
		
		$voximplant_username = $communication_settings['voximplant_username']??'';
        
		$voximplant_password = $communication_settings['voximplant_password']??'';
        
		return view('backend.end-user.communication.vomixplant-webcall', compact('lead', 'phone_', 'voximplant_username', 'voximplant_password', 'team_voximplant_phone', 'lead_id', 'team_id'));
		
	}
    
    public function vendor_calling($id){
        $options = new OptionController();
        $communication_settings = $options->get_options();
        
		$lead = Vendor::find($id);
		$lead_id = $lead->id;
		$team_id = Auth::user()->id;
		
        $phone_ = $this->phone_convert($_GET['phone_']);
        
        
        $admin_info = AdminInfo::where('user_id',$team_id)->first(); 
        
		$team_voximplant_phone = $this->phone_convert( $admin_info->voximplant_phone );
		
		$team_member_number = $this->phone_convert( $admin_info->voximplant_phone );
		
		$voximplant_username = $communication_settings['voximplant_username']??'';
        
		$voximplant_password = $communication_settings['voximplant_password']??'';
        
		return view('backend.end-user.communication.vomixplant-webcall', compact('lead', 'phone_', 'voximplant_username', 'voximplant_password', 'team_voximplant_phone', 'lead_id', 'team_id'));
		
	}
	
	public function customer_calling($id){
        $options = new OptionController();
        $communication_settings = $options->get_options();
        
		$lead = User::find($id);
		$lead_id = $lead->id;
		$team_id = Auth::guard('vendor')->user()->id;
		
        $phone_ = $this->phone_convert($_GET['phone_']);
        
        
        //$admin_info = AdminInfo::where('user_id',$team_id)->first(); 
        
		$team_voximplant_phone = $this->phone_convert( Auth::guard('vendor')->user()->phone );
		
		$team_member_number = $this->phone_convert( Auth::guard('vendor')->user()->phone );
		
		$voximplant_username = $communication_settings['voximplant_username']??'';
        
		$voximplant_password = $communication_settings['voximplant_password']??'';
        
		return view('vendors.communication.vomixplant-webcall', compact('lead', 'phone_', 'voximplant_username', 'voximplant_password', 'team_voximplant_phone', 'lead_id', 'team_id'));
		
	}
	
	
	public function admin_dialer(Request $request){
		
		if(isset($_GET['phone_']) && $_GET['phone_']){
		    
		    $options = new OptionController();
            $communication_settings = $options->get_options();
            
    		$lead = (object) array('name' => '');
    		$lead_id = '';
    		$team_id = Auth::user()->id;
    		
            $phone_ = $this->phone_convert($_GET['phone_']);
            
            
            $admin_info = AdminInfo::where('user_id',$team_id)->first(); 
            
    		$team_voximplant_phone = $this->phone_convert( $admin_info->voximplant_phone );
    		
    		$team_member_number = $this->phone_convert( $admin_info->voximplant_phone );
    		
    		$voximplant_username = $communication_settings['voximplant_username']??'';
            
    		$voximplant_password = $communication_settings['voximplant_password']??'';
            
    		return view('backend.end-user.communication.vomixplant-webcall', compact('lead', 'phone_', 'voximplant_username', 'voximplant_password', 'team_voximplant_phone', 'lead_id', 'team_id'));
    	
		}
		else{
			return view('backend.end-user.communication.dialer');
		}
	}
	
	public function add_voximplant_calllog(Request $request){
		$call_log = new CallLog();
		$call_log->call_id = $_GET['call_id'];
		$call_log->sourse = $_GET['source'];
		$call_log->destination = $_GET['phone_'];
		$call_log->status = $_GET['status'];
		$call_log->duration = $_GET['duration']; // in mili seconds
		$call_log->response = '';
		$call_log->team_id = Auth::user()->id;
		$call_log->vendor_id = $_GET['vendor_id'];
		$call_log->service_provider = 'VoximPlant';
		$call_log->save();
		
		Response::json(array('status'=>'success'));
	}
}
