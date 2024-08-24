<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Setting;
use App\Http\Controllers\Controller;

class StaxConnect extends Controller
{
    
	public $partner_api_key;
	
	public $api_endpoint_enroll;
	
	public $api_endpoint_ephemeral;
	
	public $api_endpoint_merchant_data;
	
	public $api_endpoint_merchant_api_keys;
	
	public $api_endpoint_merchant_create_api;
	
	public $api_endpoint_get_merchant;
	
	public $signup_url;

	public $api_endpoint_partner_webhook;
	public $webhook_url;

    
	public function __construct()
    {
        $this->partner_api_key = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vYXBpcHJvZC5mYXR0bGFicy5jb20vdXNlci84OTU5Yzk4ZS1kYTVhLTRmYmItYjVjNS0xYTM2NDEwNGE5NzQiLCJpYXQiOjE3MTM3OTc2NDMsImV4cCI6NDg2NzM5NzY0MywibmJmIjoxNzEzNzk3NjQzLCJqdGkiOiJVVGJQTGhIMm9FdkFDdlZCIiwiZ29kVXNlciI6dHJ1ZSwibWVyY2hhbnQiOiI0OWJjODk1OC0zYjJhLTQ3YzktYTU3Yy1jMWNkZjEyOWMwNjgiLCJzdWIiOiI4OTU5Yzk4ZS1kYTVhLTRmYmItYjVjNS0xYTM2NDEwNGE5NzQiLCJicmFuZCI6ImNhdGR1bXAtc2FuZGJveCIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjciLCJhc3N1bWluZyI6ZmFsc2V9.2st_t8P10-ktr6TiE5U7LXl5C3zJP_AAKXzyg36Lvq4';
		$this->api_endpoint_enroll = "https://apiprod.fattlabs.com/admin/enroll";
		$this->api_endpoint_ephemeral = "https://apiprod.fattlabs.com/ephemeral";
		
		$this->api_endpoint_merchant_data = "https://apiprod.fattlabs.com/merchant/{id}/registration";
		$this->api_endpoint_merchant_api_keys = "https://apiprod.fattlabs.com/merchant/{id}/apikey";
		
		$this->api_endpoint_merchant_create_api = "https://apiprod.fattlabs.com/merchant/{id}/apikey";
		
		$this->api_endpoint_get_merchant = "https://apiprod.fattlabs.com/merchant/{id}";
		
		$this->api_endpoint_partner_webhook = "https://apiprod.fattlabs.com/webhookadmin/webhook/brand";
		
		$this->signup_url = "https://signup.staxpayments.com/#/sso?jwt[]=";
		
		$this->webhook_url = "http://localhost:8000/api/webhook/staxconnect-webhook-call";
		
		
		$subscribed_webhooks = $this->get_subscribed_webhooks();
		
		if(empty($subscribed_webhooks)){
			//$this->subscribe_webhook();
		}
	}
	
	public function update_merchants_status(){
		$users = Vendor::whereNotNull('stax_merchant_id')->get();
		if( !empty( $users ) ){
			foreach( $users as $user_ ){
				$merchant_data = $this->get_merchant( $user_->stax_merchant_id );
				if(isset($merchant_data['status'])){
					$user_->stax_merchant_status = $merchant_data['status'];
					$user_->save();
					if( isset( $merchant_data['hosted_payments_token'] ) ){
						$user_->stax_enabled = 1;
						$user_->stax_key = $merchant_data['hosted_payments_token'];
						$user_->save();
					}
					
				}
				
			}
		}
	}
	
	public function handle_webhook_trigger( Request $request ){
		Log::info('StaxConnect Webhook Live Request Started');
		//$request_data_all = $request->all();
		$requestContent = $request->getContent();
		Log::info($requestContent);
		return response()->json(['success' => true]);
	}
	
	public function delete_subscribed_webhook( $webhook_id ){
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://apiprod.fattlabs.com/webhookadmin/webhook/brand/".$webhook_id);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  "Content-Type: application/json",
		  "Authorization: Bearer ".$this->partner_api_key,
		  "Accept: application/json"
		));

		$response = curl_exec($ch);
		curl_close($ch);

	}
	
	public function get_subscribed_webhooks(){
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->api_endpoint_partner_webhook);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  "Content-Type: application/json",
		  "Authorization: Bearer ".$this->partner_api_key,
		  "Accept: application/json"
		));

		$response = curl_exec($ch);
		curl_close($ch);
		
		$response_data = json_decode($response, true);
		
		return $response_data;
	}
	
	public function subscribe_webhook(){
		
		$data_ = array();
		$data_['target_url'] = $this->webhook_url;
		$data_['event_name'] = "update_merchant_status";
		$data_['meta'] = array(
			'response_code' => 200,
			'webhook_retry_frequency' => 10,
			'webhook_retry_count' => 5
		);
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->api_endpoint_partner_webhook);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);

		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_ ));

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  "Content-Type: application/json",
		  "Authorization: Bearer ".$this->partner_api_key,
		  "Accept: application/json"
		));

		$response = curl_exec($ch);
		curl_close($ch);
		
		
	}
	
	public function get_merchant( $merchant_id ){
		$api_endpoint_get_merchant = str_replace("{id}", $merchant_id, $this->api_endpoint_get_merchant);
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $api_endpoint_get_merchant);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  "Content-Type: application/json",
		  "Authorization: Bearer ".$this->partner_api_key,
		  "Accept: application/json"
		));

		$response = curl_exec($ch);
		curl_close($ch);
		
		$response_data = json_decode($response, true);
		
		return $response_data;
	}
	
	public function get_direct_login_auth_url( $merchant_id, $user_email ){
		$stax_auth_url = '';
		$merchant_api_key = '';
		
		$merchant_ = $this->get_merchant( $merchant_id );
		
		if(isset($merchant_['status']) && $merchant_['status'] == 'ACTIVE'){
			return $stax_auth_url;
		}
		
		$stax_merchant_apis = $this->get_merchant_api_keys( $merchant_id );
		
		if(isset($stax_merchant_apis['data']) && !empty( $stax_merchant_apis['data'] )){
			$merchant_api_key = $stax_merchant_apis['data'][0]['api_key'];
		}
		else{
			$stax_merchant_api_create = $this->create_merchant_api_key( $merchant_id );
			$merchant_api_key = $stax_merchant_api_create['api_key']??'';
		}
		
		if($merchant_api_key != ''){
			$merchant_reg_data = $this->get_merchant_registration_data( $merchant_id );
			if(isset($merchant_reg_data['user_id'])){
				$stax_token_auth = $this->get_ephemeral_token($merchant_reg_data['user_id'], $user_email, $merchant_api_key);
				
				if( isset($stax_token_auth['token']) && $stax_token_auth['token'] != ''){
					$stax_auth_url = $this->signup_url.$stax_token_auth['token'];
				}
				
			}
		}
		
		return $stax_auth_url;
	}
	
	public function create_merchant_api_key( $merchant_id ){
		$api_endpoint_merchant_create_api = str_replace("{id}", $merchant_id, $this->api_endpoint_merchant_create_api);
		
		$data_ = array();
		$data_['team_role'] = "admin";
		$data_['name'] = "Catdump Key";
		
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $api_endpoint_merchant_create_api);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);

		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_));

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  "Content-Type: application/json",
		  "Authorization: Bearer ".$this->partner_api_key,
		  "Accept: application/json"
		));

		$response = curl_exec($ch);
		curl_close($ch);
		
		$response_data = json_decode($response, true);
		
		return $response_data;
	}
	
	public function get_merchant_api_keys($merchant_id){
		
		$api_endpoint_merchant_api_keys = str_replace("{id}", $merchant_id, $this->api_endpoint_merchant_api_keys);
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $api_endpoint_merchant_api_keys);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  "Content-Type: application/json",
		  "Authorization: Bearer ".$this->partner_api_key,
		  "Accept: application/json"
		));

		$response = curl_exec($ch);
		curl_close($ch);
		
		$response_data = json_decode($response, true);
		
		return $response_data;
	}
	
	public function get_merchant_registration_data( $merchant_id ){
		$ch = curl_init();

		$api_endpoint_merchant_data = str_replace("{id}", $merchant_id, $this->api_endpoint_merchant_data);
		curl_setopt($ch, CURLOPT_URL, $api_endpoint_merchant_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  "Content-Type: application/json",
		  "Authorization: Bearer ".$this->partner_api_key,
		  "Accept: application/json"
		));

		$response = curl_exec($ch);
		curl_close($ch);
		
		$response_data = json_decode($response, true);
		
		return $response_data;
		
	}
	
	public function enroll( $data ){
		$enrollment_data = array();
		$enrollment_data['skip_account_page'] = true;
		$enrollment_data['merchant'] = array();
		$enrollment_data['merchant']['plan'] = 'premium';
		$enrollment_data['merchant']['company_name'] = $data['name'];
		$enrollment_data['merchant']['contact_email'] = $data['email'];

		$enrollment_data['registration'] = array();
		$enrollment_data['registration']['pricing_plan'] = 'test-pricing';
		$enrollment_data['registration']['email'] = $data['email'];
		$enrollment_data['registration']['business_email'] = $data['email'];

		$enrollment_data['user'] = array();
		$enrollment_data['user']['name'] = $data['name'];
		$enrollment_data['user']['email'] = $data['email'];
		$enrollment_data['user']['password'] = $data['password'];
		$enrollment_data['user']['password_confirmation'] = $data['password'];


		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->api_endpoint_enroll);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);

		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($enrollment_data));

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  "Content-Type: application/json",
		  "Authorization: Bearer ".$this->partner_api_key,
		  "Accept: application/json"
		));


		$response = curl_exec($ch);
		curl_close($ch);

		$response_data = json_decode($response, true);
		
		return $response_data;
	}
	
	
	public function get_ephemeral_token( $user_id, $user_email, $merchant_api_key ){

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->api_endpoint_ephemeral.'?user_id='.$user_id.'&user_email='.$user_email);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  "Content-Type: application/json",
		  "Authorization: Bearer ".$merchant_api_key,
		  "Accept: application/json"
		));

		$response = curl_exec($ch);
		curl_close($ch);

		$response_data = json_decode($response, true);
		
		return $response_data;
	}
}
