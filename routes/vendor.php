<?php

use App\Http\Controllers\FrontEnd\ContactController;
use App\Http\Controllers\Vendor\Invoice\CategoryController;
use App\Http\Controllers\Vendor\Invoice\ProductController;
use App\Mail\NewCustomerCreatedMail;
use App\Models\User;
use App\Models\UserCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\BasicMailer;

/*
|--------------------------------------------------------------------------
| vendor Interface Routes
|--------------------------------------------------------------------------
*/

Route::prefix('vendor')->middleware(['guest:vendor', 'change.lang'])->group(function () {
  Route::get('/signup', 'Vendor\VendorController@signup')->name('vendor.signup');
  Route::post('/signup/submit', 'Vendor\VendorController@create')->name('vendor.signup_submit');
  Route::get('/login', 'Vendor\VendorController@login')->name('vendor.login');
  Route::post('/login/submit', 'Vendor\VendorController@authentication')->name('vendor.login_submit');
  Route::get('/forget-password', 'Vendor\VendorController@forget_passord')->name('vendor.forget.password');
  Route::post('/send-forget-mail', 'Vendor\VendorController@forget_mail')->name('vendor.forget.mail');
  Route::get('/reset-password', 'Vendor\VendorController@reset_password')->name('vendor.reset.password');
  Route::post('/update-forget-password', 'Vendor\VendorController@update_password')->name('vendor.update-forget-password');
});

/*Route::prefix('vendor')->middleware(['change.lang', 'checkVendorPlan'])->group(function () {
  Route::get('/dashboard', 'Vendor\VendorController@index')->name('vendor.index');
});*/

Route::prefix('vendor')->middleware(['change.lang'])->group(function () {
  Route::get('/dashboard', 'Vendor\VendorController@index')->name('vendor.index');
});
Route::get('vendor/email/verify', 'Vendor\VendorController@confirm_email');

// GET EQUIPMENT FIELDS
Route::get('/equipment-management/get-equipment-fields', 'BackEnd\Instrument\CategoryController@get_equipment_fields')->name('vendor.equipment_management.get_equipment_fields');

Route::prefix('vendor')->middleware('auth:vendor', 'Deactive', 'checkPlanFeaturesAccess')->group(function () {


  //Route::get('/invoice', 'Vendor\VendorController@invoice')->name('vendor.invoice');
  
  Route::get('/invoices', 'Vendor\InvoiceController@index')->name('vendor.invoice');
  Route::get('/invoice/create', 'Vendor\InvoiceController@create')->name('vendor.invoice.create');
  Route::post('/invoice/store', 'Vendor\InvoiceController@store')->name('vendor.invoice.store');

  // code by AG start
  Route::get('/route-management', 'Vendor\VendorController@advance_route')->name('vendor.advanceroute');
  Route::get('/get-unread-notifications', 'Vendor\VendorController@get_unread_notifications')->name('vendor.get.notification');
  Route::get('/read-notifications', 'Vendor\VendorController@read_notifications')->name('notifications.mark.read');

  Route::post('/save-vendor-interest', 'Vendor\VendorController@save_vendor_interest')->name('vendor.save_vendor_interest');

  // code by AG end

  //Route::get('dashboard', 'Vendor\VendorController@dashboard')->middleware('checkVendorPlan')->name('vendor.dashboard');
  Route::get('dashboard', 'Vendor\VendorController@dashboard')->name('vendor.dashboard');
  
  Route::get('monthly-income', 'Vendor\VendorController@monthly_income')->name('vendor.monthly_income');
  Route::get('/change-password', 'Vendor\VendorController@change_password')->name('vendor.change_password');
  Route::post('/update-password', 'Vendor\VendorController@updated_password')->name('vendor.update_password');
  Route::get('/edit-profile', 'Vendor\VendorController@edit_profile')->name('vendor.edit.profile');
  Route::post('/profile/update', 'Vendor\VendorController@update_profile')->name('vendor.update_profile');
  Route::get('/logout', 'Vendor\VendorController@logout')->name('vendor.logout');

  // change admin-panel theme (dark/light) route
  Route::post('/change-theme', 'Vendor\VendorController@changeTheme')->name('vendor.change_theme');

  // code by AG start
  // customer route start
  Route::prefix('/customer-management')->group(function () {
    // equipment route
    Route::get('/all-customer', 'Vendor\CustomerController@index')->name('vendor.customer_management.all_customer');

    Route::get('/create-customer', 'Vendor\CustomerController@create')->name('vendor.customer_management.create_customer');

    Route::post('/store-customer', 'Vendor\CustomerController@store')->name('vendor.customer_management.store_customer');

    Route::get('/edit-customer/{id}', 'Vendor\CustomerController@edit')->name('vendor.customer_management.edit_customer');

    // code by AG start
    Route::get('/calling/{id}', 'Vendor\CustomerController@calling')->name('vendor.customer_management.calling');
    Route::get('/chat/{id}', 'Vendor\CustomerController@sms_communication')->name('vendor.customer_management.chat');

    Route::get('vendorsms/sms_history', 'Custom\SmsController@history_customer_sms_ajax')->name('customerchat.history');
    Route::post('vendorsms/sms_send', 'Custom\SmsController@send_customer_sms_ajax')->name('customerchat.send');
    // code by AG start

    Route::post('/update-customer/{id}', 'Vendor\CustomerController@update')->name('vendor.customer_management.update_customer');

    Route::post('/delete-customer/{id}', 'Vendor\CustomerController@destroy')->name('vendor.customer_management.delete_customer');

    Route::post('/bulk-delete-customer', 'Vendor\CustomerController@bulkDestroy')->name('vendor.customer_management.bulk_delete_customer');

    Route::post('/update-email-status/{id}', 'Vendor\CustomerController@updateEmailStatus')->name('vendor.customer_management.customer.update_email_status');

    Route::post('/update-account-status/{id}', 'Vendor\CustomerController@updateAccountStatus')->name('vendor.customer_management.customer.update_account_status');
  });

  // customer route end
  // driver route start
  Route::prefix('/driver-management')->group(function () {
    // equipment route
    Route::get('/all-drivers', 'Vendor\DriverController@index')->name('vendor.driver_management.all_drivers');

    Route::get('/create-driver', 'Vendor\DriverController@create')->name('vendor.driver_management.create_driver');

    //Route::post('/upload-slider-image', 'Vendor\EquipmentController@uploadImage')->name('vendor.driver_management.upload_slider_image');

    //Route::post('/remove-slider-image', 'Vendor\EquipmentController@removeImage')->name('vendor.driver_management.remove_slider_image');

    Route::post('/store-driver', 'Vendor\DriverController@store')->name('vendor.driver_management.store_driver');

    //Route::post('/{id}/update-featured', 'Vendor\EquipmentController@updateFeatured')->name('vendor.driver_management.update_featured');

    Route::get('/edit-driver/{id}', 'Vendor\DriverController@edit')->name('vendor.driver_management.edit_driver');

    //Route::post('/detach-slider-image', 'Vendor\EquipmentController@detachImage')->name('vendor.driver_management.detach_slider_image');

    Route::post('/update-driver/{id}', 'Vendor\DriverController@update')->name('vendor.driver_management.update_driver');

    Route::post('/delete-driver/{id}', 'Vendor\DriverController@destroy')->name('vendor.driver_management.delete_driver');

    Route::post('/bulk-delete-driver', 'Vendor\DriverController@bulkDestroy')->name('vendor.driver_management.bulk_delete_driver');

    Route::get('/change-password/{id}', 'Vendor\DriverController@changePassword')->name('vendor.driver_management.change_password');

    Route::post('/update-password/{id}', 'Vendor\DriverController@updatePassword')->name('vendor.driver_management.update_password');
  });
  // driver route end
  // code by AG end

  // equipment route start
  Route::prefix('/equipment-management')->group(function () {
    // equipment route
    Route::get('/all-equipment', 'Vendor\EquipmentController@index')->name('vendor.equipment_management.all_equipment');
    Route::get('/all-equipment-data-api', 'Vendor\EquipmentController@allEquipmentData')->name('vendor.equipment.data');

    Route::get('/create-equipment', 'Vendor\EquipmentController@create')->name('vendor.equipment_management.create_equipment');

    Route::post('/upload-slider-image', 'Vendor\EquipmentController@uploadImage')->name('vendor.equipment_management.upload_slider_image');

    Route::post('/remove-slider-image', 'Vendor\EquipmentController@removeImage')->name('vendor.equipment_management.remove_slider_image');

    Route::post('/store-equipment', 'Vendor\EquipmentController@store')->name('vendor.equipment_management.store_equipment');

    Route::post('/{id}/update-featured', 'Vendor\EquipmentController@updateFeatured')->name('vendor.equipment_management.update_featured');

    Route::get('/edit-equipment/{id}', 'Vendor\EquipmentController@edit')->name('vendor.equipment_management.edit_equipment');

    Route::post('/detach-slider-image', 'Vendor\EquipmentController@detachImage')->name('vendor.equipment_management.detach_slider_image');

    Route::post('/update-equipment/{id}', 'Vendor\EquipmentController@update')->name('vendor.equipment_management.update_equipment');

    Route::post('/delete-equipment/{id}', 'Vendor\EquipmentController@destroy')->name('vendor.equipment_management.delete_equipment');

    Route::post('/bulk-delete-equipment', 'Vendor\EquipmentController@bulkDestroy')->name('vendor.equipment_management.bulk_delete_equipment');


    // code by AG start

    // code by AG end
  });
  // equipment route end

  Route::group(['as' => 'vendor.invoice-system.', 'prefix' => 'invoice-system'], function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('invoices', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('quotations', ContactController::class);
    Route::resource('taxes', ContactController::class);
  });
  // equipment-booking route start
  Route::prefix('/equipment-booking')->group(function () {
    Route::prefix('/settings')->group(function () {
      // location route
      Route::get('/locations', 'Vendor\LocationController@index')->name('vendor.equipment_booking.settings.locations');

      Route::post('/store-location', 'Vendor\LocationController@store')->name('vendor.equipment_booking.settings.store_location');

      Route::post('/update-location', 'Vendor\LocationController@update')->name('vendor.equipment_booking.settings.update_location');

      Route::post('/delete-location/{id}', 'Vendor\LocationController@destroy')->name('vendor.equipment_booking.settings.delete_location');

      Route::post('/bulk-delete-location', 'Vendor\LocationController@bulkDestroy')->name('vendor.equipment_booking.settings.bulk_delete_location');
    });


    Route::post('/store-customer-card-ajax', function () {
      $rules = [
        'card_number' => 'required|string|min:16|max:16',
        'cvv' => 'required|string|min:3|max:3',
        'exp_month' => 'required|integer|min:1|max:12',
        'exp_year' => 'required|integer',
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'address1' => 'required|string',
        'user_id' => 'required|exists:users,id',
        'location' => 'required',
        'lat' => 'required',
        'lng' => 'required',
        'city' => 'required',
      ];

      $validator = validator()->make(request()->all(), $rules);

      if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
      }

      $validated = $validator->validated();

      $card = UserCard::create($validated);

      return response()->json(['success' => true], 200);
    })->name('store-customer-card-ajax');

    Route::post("/add-customer-from-booking", function (Request $request) {
      $request->validate([
        "first_name" => "required",
        "last_name" => "required",
        "email" => "required|email|unique:users,email",
      ]);

      $data = $request->only(['first_name', 'last_name', 'email', 'username']);
      $passwordString = '@@3266ALft#';
      $data['password'] = Hash::make($passwordString);
      $data['username'] = strtolower($request->first_name.$request->last_name.uniqid());
      $data['vendor_id'] = auth()->user()->id;

      try {
        $customer = new User();
        $customer->first_name = $data['first_name'];
        $customer->last_name = $data['last_name'];
        $customer->email = $data['email'];
        $customer->password = $data['password'];
        $customer->vendor_id = $data['vendor_id'];
        $customer->username = $data['username'];
        $customer->status = 1;
        $customer->save();

        $mailData['subject'] = 'Welcome to Helly';
        $mailData['body'] = 'Hi '. $request->first_name. ' ' . $request->last_name .',<br><br>';
        $mailData['body'] .= "Customer Details: <br><br>";
        $mailData['body'] .= "<ul><li>Password: ".$passwordString."</li><li>Login here : " . route('user.login') . "</li></ul><br><br>";
        $mailData['body'] .= "Thank you for your attention to this matter.<br><br>";
        $mailData['body'] .= "Best regards,<br>";
        $mailData['body'] .= "Helly";
        $mailData['recipient'] = $request->email;
        BasicMailer::sendMail($mailData);

      } catch (\Exception $e) {
        // dd($e->getMessage());
        Session::flash('error', $e->getMessage());
        info('ADD CUSTOMER ON BOOKING : ' . $e->getMessage());
        // dd($e->getMessage());
        return redirect()->route('vendor.equipment_booking.create');
      }

      Session::flash('success', 'New Customer account created successfully');
      return redirect()->route('vendor.equipment_booking.create', ['customer_id' => $customer->id]);
    })->name('add-customer-from-booking');
	
	
	// code by AG start
	Route::post("/add-customer-from-invoice", function (Request $request) {
      $request->validate([
        "first_name" => "required",
        "last_name" => "required",
        "username" => "required",
        "email" => "required|email|unique:users,email",
      ]);

      $data = $request->only(['first_name', 'last_name', 'email', 'username']);
      $data['password'] = Hash::make('password');
      $data['vendor_id'] = auth()->user()->id;

      try {
        $customer = new User();
        $customer->first_name = $data['first_name'];
        $customer->last_name = $data['last_name'];
        $customer->email = $data['email'];
        $customer->password = $data['password'];
        $customer->vendor_id = $data['vendor_id'];
        $customer->username = $data['username'];
        $customer->status = 1;
        $customer->save();

        // Mail::to($customer->email)->send(new NewCustomerCreatedMail($customer, $data['password']));

      } catch (\Exception $e) {
        // dd($e->getMessage());
        Session::flash('error', $e->getMessage());
        info('ADD CUSTOMER ON INVOICE : ' . $e->getMessage());
        // dd($e->getMessage());
        return redirect()->route('vendor.invoice.create');
      }

      Session::flash('success', 'New Customer account created successfully');
      return redirect()->route('vendor.invoice.create', ['customer_id' => $customer->id]);
    })->name('add-customer-from-invoice');
	
	// code by AG end

    // inventory management route

    Route::get('/inventory', 'Vendor\InventoryManagementController@index')->name('vendor.equipment_inventory');
    Route::get('/inventory/warehouse/equipment', 'Vendor\InventoryManagementController@warehouse_equipments')->name('vendor.warehouse.equipment');

    // booking route
    Route::get('/bookings', 'Vendor\BookingController@bookings')->name('vendor.equipment_booking.bookings');
    Route::get('/bookings/create', 'Vendor\BookingController@create')->name('vendor.equipment_booking.create');
    Route::post('/bookings/store', 'Vendor\BookingController@indexb')->name('vendor.equipment_booking.store');
    Route::get('/bookings/equipment', 'Vendor\BookingController@get_equipment')->name('vendor.equipment_booking.get_equipment');
    Route::get('/bookings/{id}/edit', 'Vendor\BookingController@edit')->name('vendor.equipment_booking.edit');
    Route::post('/bookings/{id}/update', 'Vendor\BookingController@update')->name('vendor.equipment_booking.update');
    Route::get('/bookings/get_cards', 'Vendor\BookingController@get_cards')->name('vendor.equipment_booking.get_cards');
    Route::get('/bookings/get_user_data', 'Vendor\BookingController@get_user_data')->name('vendor.equipment_booking.get_user_data');

    Route::post('/{id}/update-payment-status', 'Vendor\BookingController@updatePaymentStatus')->name('vendor.equipment_booking.update_payment_status');

    Route::post('/{id}/update-shipping-status', 'Vendor\BookingController@updateShippingStatus')->name('vendor.equipment_booking.update_shipping_status');

    // code by AG start
    Route::post('/{id}/assign-booking-route', 'Vendor\BookingController@assign_booking_route')->name('vendor.assign.bookingroute');

    Route::post('/{id}/update-accept-status', 'Vendor\BookingController@update_accept_status')->name('vendor.equipment_booking.accept_status');

    Route::post('/{id}/accept-declined-booking', 'Vendor\BookingController@accept_declined_booking')->name('vendor.equipment_booking.accept_status_by_other');
    Route::post('/{id}/select-equipment-for-booking-you-are-accepting/language=en', 'Vendor\BookingController@select_equipment_for_accept_booking')->name('vendor.equipment_booking.select_equipment_for_accept_booking');

    Route::post('/{id}/assign-driver', 'Vendor\BookingController@assign_driver')->name('vendor.equipment_booking.assign_driver');

    Route::get('/{id}/charge_additional_tonnage', 'Vendor\BookingController@charge_additional_tonnage')->name('vendor.equipment_booking.charge_additional_tonnage');

    Route::post('/{id}/process_charge_additional_tonnage', 'Vendor\BookingController@process_charge_additional_tonnage')->name('vendor.equipment_booking.process_charge_additional_tonnage');

    // code by AG end

    Route::post('update-return-status', 'Vendor\BookingController@updateReturnStatus')->name('vendor.equipment_booking.update_return_status');

    Route::get('/{id}/details', 'Vendor\BookingController@show')->name('vendor.equipment_booking.details');

    // report route
    Route::get('/report', 'Vendor\BookingController@report')->name('vendor.equipment_booking.report')->middleware('Deactive');

    Route::get('/export-report', 'Vendor\BookingController@exportReport')->name('vendor.equipment_booking.export_report');
  });
  // equipment-booking route end

  // shipping-method route
  Route::get('/shipping-methods', 'Vendor\VendorController@methodSettings')->name('vendor.equipment_booking.settings.shipping_methods');

  Route::post('/update-method-settings', 'Vendor\VendorController@updateMethodSettings')->name('vendor.equipment_booking.settings.update_method_settings');

  // Route::prefix('withdraw')->middleware('Deactive')->group(function () {
  //   Route::get('/', 'Vendor\VendorWithdrawController@index')->name('vendor.withdraw');
  //   Route::get('/create', 'Vendor\VendorWithdrawController@create')->name('vendor.withdraw.create');
  //   Route::get('/get-method/input/{id}', 'Vendor\VendorWithdrawController@get_inputs');

  //   Route::get('/balance-calculation/{method}/{amount}', 'Vendor\VendorWithdrawController@balance_calculation');

  //   Route::post('/send-request', 'Vendor\VendorWithdrawController@send_request')->name('vendor.withdraw.send-request');
  //   Route::post('/witdraw/bulk-delete', 'Vendor\VendorWithdrawController@bulkDelete')->name('vendor.witdraw.bulk_delete_withdraw');
  //   Route::post('/witdraw/delete', 'Vendor\VendorWithdrawController@Delete')->name('vendor.witdraw.delete_withdraw');
  // });

  Route::get('/transcation', 'Vendor\VendorController@transcation')->name('vendor.transcation');
  Route::post('/transcation/delete', 'Vendor\VendorController@destroy')->name('vendor.transcation.delete');
  Route::post('/transcation/bulk-delete', 'Vendor\VendorController@bulk_destroy')->name('vendor.transcation.bulk_delete');

  #====support tickets ============
  Route::get('support/ticket/create', 'Vendor\SupportTicketController@create')->name('vendor.support_ticket.create');
  Route::post('support/ticket/store', 'Vendor\SupportTicketController@store')->name('vendor.support_ticket.store');
  Route::get('support/tickets', 'Vendor\SupportTicketController@index')->name('vendor.support_tickets');
  Route::get('support/message/{id}', 'Vendor\SupportTicketController@message')->name('vendor.support_tickets.message');
  Route::post('support-ticket/zip-upload', 'Vendor\SupportTicketController@zip_file_upload')->name('vendor.support_ticket.zip_file.upload');
  Route::post('support-ticket/reply/{id}', 'Vendor\SupportTicketController@ticketreply')->name('vendor.support_ticket.reply');

  Route::post('support-ticket/delete/{id}', 'Vendor\SupportTicketController@delete')->name('vendor.support_tickets.delete');
  Route::post('support-ticket/bulk/delete/', 'Vendor\SupportTicketController@bulk_delete')->name('vendor.support_tickets.bulk_delete');
});



Route::prefix('vendor')->middleware('change.lang')->group(function () {
  Route::get('/{username}', 'FrontEnd\VendorController@details')->name('frontend.vendor.details');
});

Route::get('vendor/driver/schedule', 'Vendor\BookingController@driver_schedule');
