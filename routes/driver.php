<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| driver Interface Routes
|--------------------------------------------------------------------------
*/

Route::prefix('driver')->middleware(['guest:driver', 'change.lang'])->group(function () {
    //Route::get('/signup', 'Vendor\VendorController@signup')->name('vendor.signup');
    //Route::post('/signup/submit', 'Vendor\VendorController@create')->name('vendor.signup_submit');
    Route::get('/login', 'Driver\DriverController@login')->name('driver.login');
    Route::post('/login/submit', 'Driver\DriverController@authentication')->name('driver.login_submit');
    Route::get('/forget-password', 'Driver\DriverController@forget_passord')->name('driver.forget.password');
    Route::post('/send-forget-mail', 'Driver\DriverController@forget_mail')->name('driver.forget.mail');
    Route::get('/reset-password', 'Driver\DriverController@reset_password')->name('driver.reset.password');
    Route::post('/update-forget-password', 'Driver\DriverController@update_password')->name('driver.update-forget-password');
  });

Route::prefix('driver')->middleware('change.lang')->group(function () {
    Route::get('/dashboard', 'Driver\DriverController@index')->name('driver.index');
    
    //Route::get('/email/verify', 'Vendor\VendorController@confirm_email');
  });

Route::prefix('driver')->middleware('auth:driver', 'Deactive')->group(function () {
    
    // code by AG start
    Route::get('/route-management', 'Driver\DriverController@advance_route')->name('driver.advanceroute');
     Route::get('/get-unread-notifications', 'Driver\DriverController@get_unread_notifications')->name('driver.get.notification');
    // code by AG end
    
    // Route::get('dashboard', 'Driver\DriverController@dashboard')->name('driver.dashboard');
    Route::get('/dashboard', 'Driver\BookingController@bookings')->name('driver.dashboard');
    Route::get('/change-password', 'Driver\DriverController@change_password')->name('driver.change_password');
    Route::post('/update-password', 'Driver\DriverController@updated_password')->name('driver.update_password');
    Route::get('/edit-profile', 'Driver\DriverController@edit_profile')->name('driver.edit.profile');
    Route::post('/profile/update', 'Driver\DriverController@update_profile')->name('driver.update_profile');
    Route::get('/logout', 'Driver\DriverController@logout')->name('driver.logout');

    // equipment-booking route start
  Route::prefix('/equipment-booking')->group(function () {
    // booking route
    Route::get('/bookings', 'Driver\BookingController@bookings')->name('driver.equipment_booking.bookings');

    Route::post('/{id}/update-shipping-status', 'Driver\BookingController@updateShippingStatus')->name('driver.equipment_booking.update_shipping_status');

    //Route::post('update-return-status', 'Vendor\BookingController@updateReturnStatus')->name('vendor.equipment_booking.update_return_status');

    Route::get('/{id}/details', 'Driver\BookingController@show')->name('driver.equipment_booking.details');

    // report route
    //Route::get('/report', 'Vendor\BookingController@report')->name('vendor.equipment_booking.report')->middleware('Deactive');

    //Route::get('/export-report', 'Vendor\BookingController@exportReport')->name('vendor.equipment_booking.export_report');
  });
  // equipment-booking route end
});


