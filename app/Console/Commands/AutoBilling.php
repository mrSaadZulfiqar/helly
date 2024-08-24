<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\FrontEnd\UserController;
use App\Http\Controllers\FrontEnd\MiscellaneousController;

use App\Http\Controllers\FrontEnd\PaymentGateway\StaxController;

use App\Models\User;
use App\Models\UserCard;
use App\Models\AdditionalInvoice;
use App\Models\Instrument\Equipment;
use App\Models\Instrument\EquipmentCategory;
use App\Models\Instrument\EquipmentBooking;
use App\Models\EquipmentFieldsValue;
use Illuminate\Support\Facades\Log;

class AutoBilling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:billing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Billing';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();
        
        $stax_client = new StaxController();
        
        $multiple_charge_categories = EquipmentCategory::where('multiple_charges', 1)->get()->toArray();
        $multiple_charge_categories_ids = array_column($multiple_charge_categories, 'id');
        
        
        
        if(!empty($multiple_charge_categories_ids)){
            $allEquipment = Equipment::query()->join('equipment_contents', 'equipments.id', '=', 'equipment_contents.equipment_id')
              ->when($multiple_charge_categories_ids, function ($query, $multiple_charge_categories_ids) {
                
                return $query->whereIn('equipment_contents.equipment_category_id', $multiple_charge_categories_ids);
              })
              ->select('equipments.id', 'equipments.thumbnail_image', 'equipments.lowest_price', 'equipment_contents.title', 'equipment_contents.slug', 'equipments.per_day_price', 'equipments.per_week_price', 'equipments.per_month_price', 'equipment_contents.features', 'equipments.offer', 'equipments.vendor_id', 'equipment_contents.equipment_category_id')
              ->get()->toArray();
              
              $multiple_charge_equipments_ids = array_column($allEquipment, 'id');
              
              $bookings = EquipmentBooking::whereNotNull('user_id')->whereDate('end_date', '<', now())->whereIn('equipment_id', $multiple_charge_equipments_ids)->where('return_status', 0)->get();
              
             // Log::info('Hello Here are Bookings: {id}', ['id' => $bookings]);
        
                if(!empty($bookings)){
                    foreach($bookings as $booking){
                        $user___ = User::find($booking->user_id);
                        $user_cards = UserCard::where('user_id', $booking->user_id)->get()->toArray();
                        
                        
                        $equipment_fields = EquipmentFieldsValue::where('equipment_id', $booking->equipment_id)->first();
                        
                        if($equipment_fields){
                            $multiple_charges_settings = json_decode($equipment_fields->multiple_charges_settings, true);
                            
                            if(isset($multiple_charges_settings['additional_daily_cost']) && $multiple_charges_settings['additional_daily_cost'] > 0){
                                
                                $add_invoice = new AdditionalInvoice();
                                $add_invoice->user_id = $booking->user_id;
                                $add_invoice->vendor_id = $booking->vendor_id;
                                $add_invoice->booking_id = $booking->id;
                                $add_invoice->additional_day = now();
                                $add_invoice->amount = $multiple_charges_settings['additional_daily_cost'];
                                $add_invoice->save();
                                
                                $stax_saved_payment_methods = array();
                                if($user___->stax_customer_id != ''){
                                    $stax_saved_payment_methods = $stax_client->get_customer_payment_methods($user___->stax_customer_id);
                                }
                                
                                
                                if(empty($user_cards) || empty($stax_saved_payment_methods)){
                                    
                                    // email will be sent to vendor 
                            
                                }
                                else{
                                    
                                    $stax_payment_method_id = $stax_saved_payment_methods[0]['id'];
                                    
                                    $payment_data = array(
                                        "payment_method_id"=> $stax_payment_method_id,
                                        "meta"=> array(
                                           //"tax"=>4,
                                           //"poNumber"=> "1234",
                                           //"shippingAmount"=> 2,
                                           "subtotal"=>$multiple_charges_settings['additional_daily_cost'],
                                           "lineItems"=> array(
                                               array(
                                                   "id"=> "additional-daily-cost",
                                                   "item"=>"Additional Daily Cost",
                                                   "details"=>"",
                                                   "quantity"=>1,
                                                   "price"=> $multiple_charges_settings['additional_daily_cost']
                                               )
                                           )
                                        ),
                                        "total"=> $multiple_charges_settings['additional_daily_cost'],
                                        "pre_auth"=> 0
                                    );
                                        
                                    $stax_create_payment = $stax_client->create_payment($stax_payment_method_id, $payment_data);
                                    
                                    if($stax_create_payment){
                                        $add_invoice->status = 'paid';
                                        $add_invoice->details = 'Helly Payment Id: '.$stax_create_payment;
                                        $add_invoice->save();
                                    }
                                    else{
                                        // email will be sent to vendor
                                    }
                                    
                                }
                            }
                        }
                        
                        
                    }
                }
                
                
              
        }
        
        //return Command::SUCCESS;
    }
}
