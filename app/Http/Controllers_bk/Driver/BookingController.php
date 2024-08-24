<?php

namespace App\Http\Controllers\Driver;

use App\Exports\EquipmentBookingsExport;
use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Instrument\EquipmentBooking;
use App\Models\Instrument\EquipmentContent;
use App\Models\Instrument\SecurityDepositRefund;
use App\Models\Language;
use App\Models\ShippingStatus;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\User;
use App\Models\BookingDriver; // code by AG
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Driver; // code by AG
use App\Models\BookingUpdate;
use App\Models\Vendor;
use PDF;
use App\Http\Helpers\UploadFile;

use App\Notifications\BasicNotify; // code by AG
class BookingController extends Controller
{
    //
    public function bookings(Request $request)
    {
        $information['basicData'] = Basic::select('self_pickup_status', 'two_way_delivery_status')->first();
        
        $information['shippingStatus'] = ShippingStatus::get();

        $language = Language::where('code', $request->language??'en')->first();

        $bookingNumber = $paymentStatus = $shippingType = $shippingStatus = $returnStatuss = null;

        if ($request->filled('booking_no')) {
            $bookingNumber = $request['booking_no'];
        }
        if ($request->filled('payment_status')) {
            $paymentStatus = $request['payment_status'];
        }
        if ($request->filled('shipping_type')) {
            $shippingType = $request['shipping_type'];
        }
        if ($request->filled('shipping_status')) {
            $shippingStatus = $request['shipping_status'];
        }
        if ($request->filled('return_status')) {
            $returnStatus = $request['return_status'];
            $returnStatuss = 1;
        } else {
            $returnStatus = null;
        }

        $bookings = EquipmentBooking::query()->join('booking_drivers', 'equipment_bookings.id', '=', 'booking_drivers.booking_id')
        ->select('equipment_bookings.*','booking_drivers.id as bid', 'booking_drivers.driver_id as driver_id')->where('driver_id', Auth::guard('driver')->user()->id)
        
        ->when($bookingNumber, function ($query, $bookingNumber) {
            return $query->where('booking_number', 'like', '%' . $bookingNumber . '%');
        })
            ->when($paymentStatus, function ($query, $paymentStatus) {
                return $query->where('payment_status', '=', $paymentStatus);
            })
            ->when($shippingType, function ($query, $shippingType) {
                return $query->where('shipping_method', '=', $shippingType);
            })
            ->when($shippingStatus, function ($query, $shippingStatus) {
                return $query->where('shipping_status', '=', $shippingStatus);
            })
            ->when($returnStatuss, function ($query) use ($returnStatus) {
                return $query->where('return_status', '=', $returnStatus);
            })
            ->orderBy('equipment_bookings.id', 'desc')
            ->paginate(10);

        $bookings->map(function ($booking) use ($language) {
            $equipment = $booking->equipmentInfo()->first();
            $booking['equipmentTitle'] = $equipment->content()->where('language_id', $language->id)->select('title', 'slug')->first();
        });

        
        $information['bookings'] = $bookings;

        return view('drivers.booking.index', $information);
    }
    
    public function show($id, Request $request)
    {
        $details = EquipmentBooking::find($id);

        $information['details'] = $details;
        
        $information['shippingStatus'] = ShippingStatus::get();
        
        $driver = BookingDriver::join('drivers', 'booking_drivers.driver_id', '=', 'drivers.id')
        ->select('drivers.*','booking_drivers.id as bid', 'booking_drivers.driver_id as driver_id')
        ->where('booking_id', $details->id)->first();
        

        if ($driver->driver_id != Auth::guard('driver')->user()->id) {
            return redirect()->route('driver.dashboard');
        }

        $information['language'] = Language::where('code', $request->language)->first();

        $information['tax'] = Basic::select('equipment_tax_amount')->first();

        return view('drivers.booking.details', $information);
    }
    
    public function updateShippingStatus(Request $request, $id)
    {
        $booking = EquipmentBooking::find($id);
        
        $booking_updates = BookingUpdate::where('booking_id',$id)->get()->toArray();
        $booking_updates_status = array_column($booking_updates,'status');
        
        $vendor_ = Vendor::find($booking->vendor_id);
        
        $shipping_status_obj = ShippingStatus::where('slug',$request['shipping_status'])->first();
        $statusMsg = $shipping_status_obj->name;
        
        $delivery_proof_img_data = array();
        if($request['shipping_status'] == 'delivered' || $request['shipping_status'] == 'swaped'){
            // store proof image in storage
            $delivery_proof_img = UploadFile::store(public_path('assets/img/delivery-proofs/'), $request->file('helly_proof_of_delivery'));
            $delivery_proof_img_data = array(
                    'delivery_from_image' => asset('assets/img/delivery-proofs/' . $delivery_proof_img)
                );
        }
        
        
        $booking->update([
                'shipping_status' => $request['shipping_status']
            ]);
            
        $booking_update = new BookingUpdate();
        $booking_update->booking_id = $id;
        $booking_update->status = $request['shipping_status'];
        
         $booking_update->status_type = 'shipping_status';
        
        if($booking_updates_status[count($booking_updates_status) - 1] == 'swap_requested'){ //if(in_array('swap_requested',$booking_updates_status)){
            
            $booking_update->status_type = 'swapping_status';
        }
        if($booking_updates_status[count($booking_updates_status) - 1] == 'relocation_requested'){
            
            $booking_update->status_type = 'relocation_status';
            
        }
        if(in_array('pickup_requested',$booking_updates_status)){
            
            $booking_update->status_type = 'return_status';
            
        }
       
        $booking_update->update_by_user_id = Auth::guard('driver')->user()->id;
        $booking_update->user_type = 'driver';
        $booking_update->update_details = json_encode($delivery_proof_img_data);
        $booking_update->save();

          $equipment_content = EquipmentContent::where('equipment_id', $booking->equipment_id)->first();
        $vendor_detail = Vendor::where('id', $booking->vendor_id)->first();
        
        if($request['shipping_status'] == "assigned"){
            
            $mailData['subject'] = 'Your Equipment Shipment Notification';

            $mailData['body'] = 'Dear ' .$booking->name.',';
            $mailData['body'] .= "Your equipment order has been processed and is now being prepared for shipment. <br><br>";
            $mailData['body'] .= "Your order details are as follows: <br><br>";
            
            $mailData['body'] .= "Order Number: ".$booking->booking_number." <br>";
            $mailData['body'] .= "Equipment: ".$equipment_content->title." <br>";
            $mailData['body'] .= "Quantity: "."1"." <br>";
            $mailData['body'] .= "Shipping Address: ".$booking->location." <br><br>";
            
            $mailData['body'] .= "Your equipment is scheduled to be shipped on ".$booking->start_date ." ".$booking->end_date.".<br><br>";
            $mailData['body'] .= "You will receive a separate email with the tracking information once your package has been dispatched. <br><br>";
            $mailData['body'] .= "Thank you for choosing ".$vendor_detail->username.". Should you have any questions or concerns regarding your order, feel free to contact our customer support team at .".$vendor_detail->email."<br><br>";
            
            $mailData['body'] .= "Best regards,<br>";
            $mailData['body'] .= $vendor_detail->username ."Team";

            $mailData['recipient'] = $booking->email;
    
            $mailData['sessionMessage'] = 'Shipping status updated & mail has been sent successfully!';
    
            BasicMailer::sendMail($mailData);
            
        }
        elseif($request['shipping_status'] == "pickedup"){
            
            $mailData['subject'] = 'Final Equipment Pickup Notification';

            $mailData['body'] = 'Dear ' .$booking->name.',';
            $mailData['body'] .= "We are reaching out to inform you that the final pickup of the equipment is scheduled as follows: <br><br>";

            $mailData['body'] .= "Order Number: ".$booking->booking_number." <br>";
            $mailData['body'] .= "Equipment: ".$equipment_content->title." <br>";
            $mailData['body'] .= "Final Pickup Date: ".$booking->start_date ." ". $booking->end_date ." <br>";
            $mailData['body'] .= "Pickup Address: ".$booking->location." <br><br>";
            
            $mailData['body'] .= "Our team will be arriving at the specified location on the designated date for the final pickup of the equipment.<br><br>";
             
            $mailData['body'] .= "Should you have any questions or require further assistance, please don’t hesitate to contact us at .".$vendor_detail->email."<br><br>";
            $mailData['body'] .= "Thank you for your cooperation throughout this process.<br><br>";
            $mailData['body'] .= "Best regards,<br>";
            $mailData['body'] .= $vendor_detail->username ."Team";

            $mailData['recipient'] = $booking->email;
    
            $mailData['sessionMessage'] = 'Shipping status updated & mail has been sent successfully!';
    
            BasicMailer::sendMail($mailData);
            
        }
        elseif($request['shipping_status'] == "out_for_delivery"){
            
            $mailData['subject'] = 'Your Equipment is Out for Delivery';

            $mailData['body'] = 'Dear ' .$booking->name.',';
            $mailData['body'] .= "Exciting news! Your equipment order is now out for delivery and is on its way to you. Here are the details of your order: <br><br>";
            
            $mailData['body'] .= "Order Number: ".$booking->booking_number." <br>";
            $mailData['body'] .= "Equipment: ".$equipment_content->title." <br>";
            $mailData['body'] .= "Quantity: "."1"." <br>";
            $mailData['body'] .= "Shipping Address: ".$booking->location." <br><br>";
            
            $mailData['body'] .= "Our delivery partner will be bringing your (Equipment Type) to you soon. Please ensure someone is available to receive the delivery at the provided shipping address.<br><br>";
            $mailData['body'] .= "If you have any questions or need further assistance, please don’t hesitate to contact our customer support team at ".$vendor_detail->email.". <br><br>";
            $mailData['body'] .= "Thank you for choosing ".$vendor_detail->username.".  We appreciate your business.<br><br>";
            
            $mailData['body'] .= "Best regards,<br>";
            $mailData['body'] .= $vendor_detail->username ."Team";

            $mailData['recipient'] = $booking->email;
    
            $mailData['sessionMessage'] = 'Shipping status updated & mail has been sent successfully!';
    
            BasicMailer::sendMail($mailData);
            
        }
        elseif($request['shipping_status'] == "delivered"){
            
            $mailData['subject'] = 'Your Equipment is Delivered';

            $mailData['body'] = 'Dear ' .$booking->name.',';
            $mailData['body'] .= "Your equipment order has been Delivered. <br><br>";
            $mailData['body'] .= "Your order details are as follows: <br><br>";
            
            $mailData['body'] .= "Order Number: ".$booking->booking_number." <br>";
            $mailData['body'] .= "Equipment: ".$equipment_content->title." <br>";
            $mailData['body'] .= "Quantity: "."1"." <br>";
            $mailData['body'] .= "Shipping Address: ".$booking->location." <br><br>";
        
            $mailData['body'] .= "Thank you for choosing ".$vendor_detail->username.". Should you have any questions or concerns regarding your order, feel free to contact our customer support team at .".$vendor_detail->email."<br><br>";
            
            $mailData['body'] .= "Best regards,<br>";
            $mailData['body'] .= $vendor_detail->username ."Team";

            $mailData['recipient'] = $booking->email;
    
            $mailData['sessionMessage'] = 'Shipping status updated & mail has been sent successfully!';
    
            BasicMailer::sendMail($mailData);
            
        }
        
        // $mailData['subject'] = 'Your booking status';

        // $mailData['body'] = 'Hi ' . $booking->name . ',<br/><br/>Booking (#'.$booking->booking_number.') <br>This email is to notify the shipping status of your booked equipment. ' . $statusMsg;

        // $mailData['recipient'] = $booking->email;

        // $mailData['sessionMessage'] = 'Shipping status updated & mail has been sent successfully!';
        
        

        // BasicMailer::sendMail($mailData);
        
        // email to vendor
        $mailData['subject'] = 'Booking Status Notification';

        $mailData['body'] = 'Hi ' . $vendor_->username . ',<br/><br/>This email is to notify the shipping status of booking #'.$booking->booking_number .' : '. $statusMsg;

        $mailData['recipient'] = $vendor_->email;

        //$mailData['sessionMessage'] = 'Shipping status updated & mail has been sent successfully!';
        
        $vendor_->notify(new BasicNotify($mailData['body']));

        BasicMailer::sendMail($mailData);

        return redirect()->back();
    }
}
