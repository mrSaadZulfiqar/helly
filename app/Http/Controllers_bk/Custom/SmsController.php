<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BackEnd\Custom\OptionController;
use App\Models\Lead;
use App\Models\Vendor;
use App\Models\AdminInfo;
use App\Models\VendorChat;

class SmsController extends Controller
{
    public function phone_convert($phone){
        $phone = str_replace(' ', '', $phone); // Replaces all spaces with hyphens.
        $phone = preg_replace('/[^A-Za-z0-9]/', '', $phone); // Removes special chars.
        return $phone;
    }
    /**
     * get sms history
     *
     */
    public function history_sms_ajax(Request $request){
        $res = array();
        $res['chats'] = array();
        $team_id = Auth::user()->id;
        $admin_info = AdminInfo::where('user_id',$team_id)->first();
        $team_phone = $this->phone_convert( $admin_info->phone );
        
        if(isset($_GET['sms_provider']) && $_GET['sms_provider'] == 'messagebird_sms'){
            $team_phone = $this->phone_convert( $admin_info->message_bird_phone );
        }
		
		if(isset($_GET['sms_provider']) && $_GET['sms_provider'] == 'voximplant_sms'){
            $team_phone = $this->phone_convert( $admin_info->voximplant_phone );
        }
        
        $to = $this->phone_convert( $_GET['vendor_number'] );
        
        if(isset($_GET['first_message_id'])){
            $chats = $this->get_vonage_sms_old( $team_phone, $to, $_GET['first_message_id']);
            if( !empty($chats)){
                $res['chats'] = $chats;
            }
        }
        else{
            $chats = $this->get_vonage_sms_latest( $team_phone, $to, $_GET['last_message_id']);
            if( !empty($chats)){
                $chats = array_reverse($chats);
                
                $res['chats'] = $chats;
            }
        }
        
        return Response::json($res);
    }
    
    
    /**
     * get vendor-customer sms history
     *
     */
    public function history_customer_sms_ajax(Request $request){
        $res = array();
        $res['chats'] = array();
        $team_id = Auth::guard('vendor')->user()->id;
        //$admin_info = AdminInfo::where('user_id',$team_id)->first();
        $team_phone = $this->phone_convert( Auth::guard('vendor')->user()->phone );
        
        if(isset($_GET['sms_provider']) && $_GET['sms_provider'] == 'messagebird_sms'){
            $team_phone = $this->phone_convert( Auth::guard('vendor')->user()->phone );
        }
		
		if(isset($_GET['sms_provider']) && $_GET['sms_provider'] == 'voximplant_sms'){
            $team_phone = $this->phone_convert( Auth::guard('vendor')->user()->phone );
        }
        
        $to = $this->phone_convert( $_GET['vendor_number'] );
        
        if(isset($_GET['first_message_id'])){
            $chats = $this->get_vonage_sms_old( $team_phone, $to, $_GET['first_message_id']);
            if( !empty($chats)){
                $res['chats'] = $chats;
            }
        }
        else{
            $chats = $this->get_vonage_sms_latest( $team_phone, $to, $_GET['last_message_id']);
            if( !empty($chats)){
                $chats = array_reverse($chats);
                
                $res['chats'] = $chats;
            }
        }
        
        return Response::json($res);
    }
    
    public function get_vonage_sms_old( $team_phone, $vendor_phone, $first_msg_id, $search_keyword = ''){
        
        if($first_msg_id > 0){
            $vendor_chat = VendorChat::where(function($query) use ($team_phone,$vendor_phone, $first_msg_id, $search_keyword) {
                $query->where('id','<', $first_msg_id)->where('sender_number', $team_phone)
                      ->where('receiver_number', $vendor_phone);
                      
                if($search_keyword != ''){
                    $query->where('msg','like','%'.$search_keyword.'%');
                }
                
            })->orWhere(function($query) use ($team_phone,$vendor_phone, $first_msg_id, $search_keyword) {
                $query->where('id','<', $first_msg_id)->where('sender_number', $vendor_phone)
                      ->where('receiver_number', $team_phone);
                
                if($search_keyword != ''){
                    $query->where('msg','like','%'.$search_keyword.'%');
                }
                
            })->limit(10)->orderByRaw('created_at DESC')->get()->toArray();
        }
        else{
           $vendor_chat = VendorChat::where(function($query) use ($team_phone,$vendor_phone, $search_keyword) {
                $query->where('sender_number', $team_phone)
                      ->where('receiver_number', $vendor_phone);
                
                if($search_keyword != ''){
                    $query->where('msg','like','%'.$search_keyword.'%');
                }
                
            })->orWhere(function($query) use ($team_phone,$vendor_phone, $search_keyword) {
                $query->where('sender_number', $vendor_phone)
                      ->where('receiver_number', $team_phone);
                
                if($search_keyword != ''){
                    $query->where('msg','like','%'.$search_keyword.'%');
                }
                
            })->limit(10)->orderByRaw('created_at DESC')->get()->toArray(); 
        }
        
        return $vendor_chat;
    }
    
    public function get_vonage_sms_latest( $team_phone, $vendor_phone, $last_msg_id, $search_keyword = ''){
        if($last_msg_id > 0){
            $vendor_chat = VendorChat::where(function($query) use ($team_phone,$vendor_phone, $last_msg_id, $search_keyword) {
                $query->where('id','>', $last_msg_id)->where('sender_number', $team_phone)
                      ->where('receiver_number', $vendor_phone);
                
                if($search_keyword != ''){
                    $query->where('msg','like','%'.$search_keyword.'%');
                }
                
            })->orWhere(function($query) use ($team_phone,$vendor_phone, $last_msg_id, $search_keyword) {
                $query->where('id','>', $last_msg_id)->where('sender_number', $vendor_phone)
                      ->where('receiver_number', $team_phone);
                
                if($search_keyword != ''){
                    $query->where('msg','like','%'.$search_keyword.'%');
                }
                
            })->limit(10)->orderByRaw('created_at DESC')->get()->toArray();
        }
        else{
            $vendor_chat = VendorChat::where(function($query) use ($team_phone,$vendor_phone, $search_keyword) {
                $query->where('sender_number', $team_phone)
                      ->where('receiver_number', $vendor_phone);
                      
                if($search_keyword != ''){
                    $query->where('msg','like','%'.$search_keyword.'%');
                }
               
            })->orWhere(function($query) use ($team_phone,$vendor_phone, $search_keyword) {
                $query->where('sender_number', $vendor_phone)
                      ->where('receiver_number', $team_phone);
                
                if($search_keyword != ''){
                    $query->where('msg','like','%'.$search_keyword.'%');
                }
              
            })->limit(10)->orderByRaw('created_at DESC')->get()->toArray();
        }
        
        return $vendor_chat;
    }
    
    /**
     * send sms
     *
     */
    public function send_sms_ajax(Request $request)
    {
        $res = array();
        
        $team_id = Auth::user()->id;
        $admin_info = AdminInfo::where('user_id',$team_id)->first();
        
        if(isset($_POST['sms_provide_to_send']) && $_POST['sms_provide_to_send'] == 'messagebird_sms'){
            $team_member_number = $this->phone_convert($admin_info->message_bird_phone );
            $send_sms = $this->send_message_bird_sms($this->phone_convert( $_POST['vendor_chat_form_phone_'] ), $_POST['vendor_chat_form_msg']);
            
            if(isset($send_sms['status']) && $send_sms['status'] == 'success'){
                $res['status'] = 'sent';
                $sms_id = $this->vonage_store_sms( $team_member_number, $this->phone_convert($_POST['vendor_chat_form_phone_']), $_POST['vendor_chat_form_msg'], 'messagebird_sms');
                $res['sms_id'] = $sms_id;
            }
            else{
                $res['status'] = 'failed';
            }
        }
		else if(isset($_POST['sms_provide_to_send']) && $_POST['sms_provide_to_send'] == 'voximplant_sms'){
			
            $team_member_number = $this->phone_convert( $admin_info->voximplant_phone );
            $send_sms = $this->send_voximplant_sms($this->phone_convert( $_POST['vendor_chat_form_phone_'] ), $_POST['vendor_chat_form_msg']);
            
			$send_sms = json_decode($send_sms, true);
            if(isset($send_sms['result']) && $send_sms['result'] == '1'){
                $res['status'] = 'sent';
                $sms_id = $this->vonage_store_sms( $team_member_number, $this->phone_convert($_POST['vendor_chat_form_phone_']), $_POST['vendor_chat_form_msg'], 'voximplant_sms');
                $res['sms_id'] = $sms_id;
            }
            else{
                $res['status'] = 'failed';
            }
			
        }else{
            // vonage removed
            
            // $team_member_number = $this->phone_convert( Auth::user()->vonage_phone );
            // $send_sms = $this->send_vonage_sms($this->phone_convert( $_POST['vendor_chat_form_phone_'] ), $_POST['vendor_chat_form_msg']);
            
            // $message = $send_sms->current();
        
            // if ($message->getStatus() == 0) {
            //     $res['status'] = 'sent';
            //     $sms_id = $this->vonage_store_sms( $team_member_number, $this->phone_convert( $_POST['vendor_chat_form_phone_'] ), $_POST['vendor_chat_form_msg'], 'vonage_sms');
            //     $res['sms_id'] = $sms_id;
            // }
            // else{
            //     $res['status'] = 'failed';
            // }
        }
        
        
        return Response::json($res);
    }
    
    /**
     * send customer sms
     *
     */
    public function send_customer_sms_ajax(Request $request)
    {
        $res = array();
        
        $team_id = Auth::guard('vendor')->user()->id;
        //$admin_info = AdminInfo::where('user_id',$team_id)->first();
        
        if(isset($_POST['sms_provide_to_send']) && $_POST['sms_provide_to_send'] == 'messagebird_sms'){
            $team_member_number = $this->phone_convert(Auth::guard('vendor')->user()->phone );
            $send_sms = $this->send_message_bird_sms($this->phone_convert( $_POST['vendor_chat_form_phone_'] ), $_POST['vendor_chat_form_msg']);
            
            if(isset($send_sms['status']) && $send_sms['status'] == 'success'){
                $res['status'] = 'sent';
                $sms_id = $this->vonage_store_sms( $team_member_number, $this->phone_convert($_POST['vendor_chat_form_phone_']), $_POST['vendor_chat_form_msg'], 'messagebird_sms');
                $res['sms_id'] = $sms_id;
            }
            else{
                $res['status'] = 'failed';
            }
        }
		else if(isset($_POST['sms_provide_to_send']) && $_POST['sms_provide_to_send'] == 'voximplant_sms'){
			
            $team_member_number = $this->phone_convert( Auth::guard('vendor')->user()->phone );
            $send_sms = $this->send_voximplant_customer_sms($this->phone_convert( $_POST['vendor_chat_form_phone_'] ), $_POST['vendor_chat_form_msg']);
            
			$send_sms = json_decode($send_sms, true);
            if(isset($send_sms['result']) && $send_sms['result'] == '1'){
                $res['status'] = 'sent';
                $sms_id = $this->vonage_store_sms( $team_member_number, $this->phone_convert($_POST['vendor_chat_form_phone_']), $_POST['vendor_chat_form_msg'], 'voximplant_sms');
                $res['sms_id'] = $sms_id;
            }
            else{
                $res['status'] = 'failed';
            }
			
        }else{
            // vonage removed
            
            // $team_member_number = $this->phone_convert( Auth::user()->vonage_phone );
            // $send_sms = $this->send_vonage_sms($this->phone_convert( $_POST['vendor_chat_form_phone_'] ), $_POST['vendor_chat_form_msg']);
            
            // $message = $send_sms->current();
        
            // if ($message->getStatus() == 0) {
            //     $res['status'] = 'sent';
            //     $sms_id = $this->vonage_store_sms( $team_member_number, $this->phone_convert( $_POST['vendor_chat_form_phone_'] ), $_POST['vendor_chat_form_msg'], 'vonage_sms');
            //     $res['sms_id'] = $sms_id;
            // }
            // else{
            //     $res['status'] = 'failed';
            // }
        }
        
        
        return Response::json($res);
    }
    
    public function send_message_bird_sms($to, $sms_body){
        $team_id = Auth::user()->id;
        $admin_info = AdminInfo::where('user_id',$team_id)->first();
        $team_member_number = $this->phone_convert( $admin_info->message_bird_phone );
        
        $options = new OptionController();
        $communication_settings = $options->get_options();
        
        $message_bird_api_key = $communication_settings['message_bird_api_key'];
        
        $messageBird = new \MessageBird\Client($message_bird_api_key); //MESSAGEBIRD_LIVE_API_KEY, MESSAGEBIRD_TEST_API_KEY
        
        $Message = new \MessageBird\Objects\Message();
        $Message->originator = $team_member_number;
        $Message->recipients = array($to);
        $Message->body = $sms_body;
        
    
        try {
            $response = $messageBird->messages->create($Message);
            //return $response;
            return array('status'=>'success');
        } catch (\MessageBird\Exceptions\AuthenticateException $e) {
            // That means that your accessKey is unknown
            return array('status'=>'failed');
        } catch (\MessageBird\Exceptions\BalanceException $e) {
            // That means that you are out of credits, so do something about it.
            return array('status'=>'failed');
        } catch (\Exception $e) {
            return array('status'=>'failed');
        }
        //echo '<pre>'; print_r( $r); die;
        //$balance = $messageBird->balance->read(); echo $balance; die;
        
    }
    
    public function send_voximplant_customer_sms($to, $sms_body){
        $team_id = Auth::guard('vendor')->user()->id;
        //$admin_info = AdminInfo::where('user_id',$team_id)->first();
        $options = new OptionController();
        $communication_settings = $options->get_options();
		$voximplant_account_id =$communication_settings['voximplant_account_id'];
        $voximplant_api_key = $communication_settings['voximplant_api_key'];
		
		
		$team_member_number = $this->phone_convert( Auth::guard('vendor')->user()->phone );
		
		$sms_body = urlencode($sms_body);
		
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.voximplant.com/platform_api/SendSmsMessage?account_id='.$voximplant_account_id.'&api_key=6973551&api_key='.$voximplant_api_key.'&source='.$team_member_number.'&destination='.$to.'&sms_body='.$sms_body,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
        
    }
    
    public function send_voximplant_sms($to, $sms_body){
        $team_id = Auth::user()->id;
        $admin_info = AdminInfo::where('user_id',$team_id)->first();
        $options = new OptionController();
        $communication_settings = $options->get_options();
		$voximplant_account_id =$communication_settings['voximplant_account_id'];
        $voximplant_api_key = $communication_settings['voximplant_api_key'];
		
		
		$team_member_number = $this->phone_convert( $admin_info->voximplant_phone );
		
		$sms_body = urlencode($sms_body);
		
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.voximplant.com/platform_api/SendSmsMessage?account_id='.$voximplant_account_id.'&api_key=6973551&api_key='.$voximplant_api_key.'&source='.$team_member_number.'&destination='.$to.'&sms_body='.$sms_body,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
        
    }
    
    public function vonage_store_sms( $sender_number, $receiver_number, $msg, $service_provider){
        $vendor_chat = new VendorChat;
 
        $vendor_chat->sender_id = '1';
        $vendor_chat->receiver_id = '1';
        $vendor_chat->sender_number = $sender_number;
        $vendor_chat->receiver_number = $receiver_number;
        $vendor_chat->msg = $msg;
        $vendor_chat->service_provider = $service_provider;
        $vendor_chat->save();
        
        return $vendor_chat->id;
    }
}
