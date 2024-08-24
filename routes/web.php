<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Interface Routes
|--------------------------------------------------------------------------
*/
// Route::get('/up-eq','FrontEnd\MiscellaneousController@up_eq');

Route::get("/", function(){
    return redirect("vendor/login");
});


Route::get('product-invoice', function () {
  return view('frontend.equipment.invoice');
});
Route::get('/calculator', 'FrontEnd\HomeController@calculator')->name('calculator');
// Route::get('/testing', 'FrontEnd\HomeController@testing')->name('testing');

Route::post('/push-notification/store-endpoint', 'FrontEnd\PushNotificationController@store');

Route::get('/change-language', 'FrontEnd\MiscellaneousController@changeLanguage')->name('change_language');

Route::post('/store-subscriber', 'FrontEnd\MiscellaneousController@storeSubscriber')->name('store_subscriber');

Route::get('/offline', 'FrontEnd\HomeController@offline');

Route::get('/request-a-quote', 'FrontEnd\Instrument\EquipmentController@request_a_quote_page')->name('request_a_quote_page.page');

Route::middleware('change.lang')->group(function () {
  Route::get('/', function(){
    //return abort(404);
	return redirect("vendor/login");
  })->name('index');

  Route::prefix('/equipment')->group(function () {
    Route::get('/dev', 'FrontEnd\Instrument\EquipmentController@index')->name('all_equipment.dev');
    Route::get('/', 'FrontEnd\Instrument\EquipmentController@index_with_map')->name('all_equipment');

    Route::get('/{slug}', 'FrontEnd\Instrument\EquipmentController@show')->name('equipment_details');

    Route::get('/{id}/min-price', 'FrontEnd\Instrument\EquipmentController@minPrice');

    Route::post('/change-shipping-method', 'FrontEnd\Instrument\EquipmentController@changeShippingMethod');

    Route::post('/apply-coupon', 'FrontEnd\Instrument\EquipmentController@applyCoupon');
	
	// code by AG start
    Route::prefix('/request-a-quote')->group(function () {
        Route::post('', function(){
          return abort(404);
        })->name('equipment.request_quote');
    });
    // code by AG end

    Route::prefix('/make-booking')->group(function () {
      Route::post('', 'FrontEnd\Instrument\BookingProcessController@index')->name('equipment.make_booking');

      Route::get('/paypal/notify', 'FrontEnd\PaymentGateway\PayPalController@notify')->name('equipment.make_booking.paypal.notify');

      Route::get('/instamojo/notify', 'FrontEnd\PaymentGateway\InstamojoController@notify')->name('equipment.make_booking.instamojo.notify');

      Route::get('/paystack/notify', 'FrontEnd\PaymentGateway\PaystackController@notify')->name('equipment.make_booking.paystack.notify');

      Route::get('/flutterwave/notify', 'FrontEnd\PaymentGateway\FlutterwaveController@notify')->name('equipment.make_booking.flutterwave.notify');

      Route::post('/razorpay/notify', 'FrontEnd\PaymentGateway\RazorpayController@notify')->name('equipment.make_booking.razorpay.notify');

      // code by AG start
      Route::post('/stax/notify', 'FrontEnd\PaymentGateway\StaxController@notify')->name('equipment.make_booking.stax.notify');
      
      Route::post('/stax/swap_charge_process', 'FrontEnd\PaymentGateway\StaxController@swap_charge_process')->name('equipment.swap_charge.stax.notify');
      
      
      Route::get('/resolvepay/notify', 'FrontEnd\PaymentGateway\ResolvepayController@notify')->name('equipment.make_booking.resolve.notify');
      
      Route::get('/resolvepay/swap_charge_process', 'FrontEnd\PaymentGateway\ResolvepayController@swap_charge_process')->name('equipment.swap_charge.resolve.notify');
      // code by AG end

      Route::get('/mercadopago/notify', 'FrontEnd\PaymentGateway\MercadoPagoController@notify')->name('equipment.make_booking.mercadopago.notify');

      Route::get('/mollie/notify', 'FrontEnd\PaymentGateway\MollieController@notify')->name('equipment.make_booking.mollie.notify');

      Route::post('/paytm/notify', 'FrontEnd\PaymentGateway\PaytmController@notify')->name('equipment.make_booking.paytm.notify');

      Route::get('/complete/{type?}', 'FrontEnd\Instrument\BookingProcessController@complete')->name('equipment.make_booking.complete')->middleware('change.lang');

      Route::get('/cancel', 'FrontEnd\Instrument\BookingProcessController@cancel')->name('equipment.make_booking.cancel');
      
    });
        
    // Route::post('/{id}/store-review', 'FrontEnd\Instrument\EquipmentController@storeReview')->name('equipment_details.store_review');
    
  });
 
  Route::get('security-diposit-refund/agree/{id}', 'BackEnd\Instrument\SecurityDepositController@agree')->name('security-diposit-refund.agree');

  Route::get('security-diposit-refund/raise-dispute/{id}', 'BackEnd\Instrument\SecurityDepositController@raise_dispute')->name('security-diposit-refund.raise-dispute');

  Route::prefix('/shop')->group(function () {
    Route::get('/products', 'FrontEnd\Shop\ProductController@index')->name('shop.products');

    Route::prefix('/product')->group(function () {
      Route::get('/{slug}', 'FrontEnd\Shop\ProductController@show')->name('shop.product_details');

      Route::get('/{id}/add-to-cart/{quantity}', 'FrontEnd\Shop\ProductController@addToCart')->name('shop.product.add_to_cart');
    });

    Route::get('/cart', 'FrontEnd\Shop\ProductController@cart')->name('shop.cart');

    Route::post('/update-cart', 'FrontEnd\Shop\ProductController@updateCart')->name('shop.update_cart');

    Route::get('/cart/remove-product/{id}', 'FrontEnd\Shop\ProductController@removeProduct')->name('shop.cart.remove_product');

    Route::prefix('/checkout')->group(function () {
      Route::get('', 'FrontEnd\Shop\ProductController@checkout')->name('shop.checkout');

      Route::post('/apply-coupon', 'FrontEnd\Shop\ProductController@applyCoupon');

      Route::get('/offline-gateway/{id}/check-attachment', 'FrontEnd\Shop\ProductController@checkAttachment');
    });

    Route::prefix('/purchase-product')->group(function () {
      Route::post('', 'FrontEnd\Shop\PurchaseProcessController@index')->name('shop.purchase_product');

      Route::get('/paypal/notify', 'FrontEnd\PaymentGateway\PayPalController@notify')->name('shop.purchase_product.paypal.notify');

      Route::get('/instamojo/notify', 'FrontEnd\PaymentGateway\InstamojoController@notify')->name('shop.purchase_product.instamojo.notify');

      Route::get('/paystack/notify', 'FrontEnd\PaymentGateway\PaystackController@notify')->name('shop.purchase_product.paystack.notify');

      Route::get('/flutterwave/notify', 'FrontEnd\PaymentGateway\FlutterwaveController@notify')->name('shop.purchase_product.flutterwave.notify');

      Route::post('/razorpay/notify', 'FrontEnd\PaymentGateway\RazorpayController@notify')->name('shop.purchase_product.razorpay.notify');

      // code by AG start
      Route::post('/stax/notify', 'FrontEnd\PaymentGateway\StaxController@notify')->name('shop.purchase_product.stax.notify');
      
      Route::get('/resolvepay/notify', 'FrontEnd\PaymentGateway\ResolvepayController@notify')->name('shop.purchase_product.resolve.notify');
      // code by AG end

      Route::get('/mercadopago/notify', 'FrontEnd\PaymentGateway\MercadoPagoController@notify')->name('shop.purchase_product.mercadopago.notify');

      Route::get('/mollie/notify', 'FrontEnd\PaymentGateway\MollieController@notify')->name('shop.purchase_product.mollie.notify');

      Route::post('/paytm/notify', 'FrontEnd\PaymentGateway\PaytmController@notify')->name('shop.purchase_product.paytm.notify');

      Route::get('/complete/{type?}', 'FrontEnd\Shop\PurchaseProcessController@complete')->name('shop.purchase_product.complete')->middleware('change.lang');

      Route::get('/cancel', 'FrontEnd\Shop\PurchaseProcessController@cancel')->name('shop.purchase_product.cancel');
    });

    Route::post('/product/{id}/store-review', 'FrontEnd\Shop\ProductController@storeReview')->name('shop.product_details.store_review');
  });


  // Route::get('/vendors', 'FrontEnd\VendorController@index')->name('frontend.vendors');
  

  // Route::prefix('vendor')->group(function () {
  //   Route::post('review', 'FrontEnd\VendorController@review')->name('vendor.review');
  //   Route::post('contact/message', 'FrontEnd\VendorController@contact')->name('vendor.contact.message');
  // });

  // Route::prefix('/blog')->group(function () {
  //   Route::get('', 'FrontEnd\BlogController@index')->name('blog');

  //   Route::get('/{slug}', 'FrontEnd\BlogController@show')->name('blog_details');
  // });
  
//   Route::prefix('/subscription')->group(function () {
//     Route::get('', 'FrontEnd\SubscriptionController@index')->name('subscription.index');
//     Route::post('', 'FrontEnd\SubscriptionController@pay')->name('vendor.subscription.pay')->middleware('auth:vendor');
//     Route::post('/pay_trial', 'FrontEnd\SubscriptionController@pay_if_trial_days')->name('vendor.subscription.pay.trial')->middleware('auth:vendor');
//     Route::post('store', 'FrontEnd\SubscriptionController@store')->name('vendor.subscription.store')->middleware('auth:vendor');
//     Route::post('success', 'FrontEnd\SubscriptionController@purchase_success')->name('subscription.success')->middleware('auth:vendor');
//     Route::get('check/trial_days', 'FrontEnd\SubscriptionController@check_trial_days')->name('subscription.check_trial_days');
//     Route::post('notify', 'FrontEnd\SubscriptionController@notify')->name('subscription.notify');

//     Route::get('/active', 'FrontEnd\SubscriptionController@show')->name('subscription.show')->middleware('auth:vendor');
// // Route::get('/checking', 'FrontEnd\SubscriptionController@checking')->name('subscription.checking')->middleware('auth:vendor');

//   });
  

  Route::get('/faq', 'FrontEnd\FaqController@faq')->name('faq');

  Route::prefix('/contact')->group(function () {
    Route::get('', 'FrontEnd\ContactController@contact')->name('contact');

    Route::post('/send-mail', 'FrontEnd\ContactController@sendMail')->name('contact.send_mail')->withoutMiddleware('change.lang');
  });
});

Route::post('/advertisement/{id}/count-view', 'FrontEnd\MiscellaneousController@countAdView');

Route::prefix('/user')->middleware(['guest:web', 'change.lang'])->group(function () {
  Route::prefix('/login')->group(function () {
    // user redirect to login page route
    Route::get('', 'FrontEnd\UserController@login')->name('user.login');

    // user login via facebook route
    Route::prefix('/facebook')->group(function () {
      Route::get('', 'FrontEnd\UserController@redirectToFacebook')->name('user.login.facebook');

      Route::get('/callback', 'FrontEnd\UserController@handleFacebookCallback');
    });

    // user login via google route
    Route::prefix('/google')->group(function () {
      Route::get('', 'FrontEnd\UserController@redirectToGoogle')->name('user.login.google');

      Route::get('/callback', 'FrontEnd\UserController@handleGoogleCallback');
    });
  });

  // user login submit route
  Route::post('/login-submit', 'FrontEnd\UserController@loginSubmit')->name('user.login_submit')->withoutMiddleware('change.lang');

  // user forget password route
  Route::get('/forget-password', 'FrontEnd\UserController@forgetPassword')->name('user.forget_password');

  // send mail to user for forget password route
  Route::post('/send-forget-password-mail', 'FrontEnd\UserController@forgetPasswordMail')->name('user.send_forget_password_mail')->withoutMiddleware('change.lang');

  // reset password route
  Route::get('/reset-password', 'FrontEnd\UserController@resetPassword');

  // user reset password submit route
  Route::post('/reset-password-submit', 'FrontEnd\UserController@resetPasswordSubmit')->name('user.reset_password_submit')->withoutMiddleware('change.lang');

  // user redirect to signup page route
  Route::get('/signup', 'FrontEnd\UserController@signup')->name('user.signup');

  // user signup submit route
  Route::post('/signup-submit', 'FrontEnd\UserController@signupSubmit')->name('user.signup_submit')->withoutMiddleware('change.lang');

  // signup verify route
  Route::get('/signup-verify/{token}', 'FrontEnd\UserController@signupVerify')->withoutMiddleware('change.lang');
  
  Route::get('/read/notification/{id?}', 'FrontEnd\UserController@read_notification')->name('read.notification');
});

Route::prefix('/user')->middleware(['auth:web', 'account.status', 'change.lang'])->group(function () {
  // user redirect to dashboard route
  Route::get('/dashboard', 'FrontEnd\UserController@redirectToDashboard')->name('user.dashboard');

  // edit profile route
  Route::get('/edit-profile', 'FrontEnd\UserController@editProfile')->name('user.edit_profile');

  // update profile route
  Route::post('/update-profile', 'FrontEnd\UserController@updateProfile')->name('user.update_profile')->withoutMiddleware('change.lang');

  // change password route
  Route::get('/change-password', 'FrontEnd\UserController@changePassword')->name('user.change_password');
  
  // code by AG start
  // change password route
  Route::get('/payment-methods', 'FrontEnd\UserController@paymentMethods')->name('user.payment_methods');
  Route::get('/add-payment-methods', 'FrontEnd\UserController@addPaymentMethods')->name('user.add_payment_methods');
  Route::post('/add-payment-methods', 'FrontEnd\UserController@storePaymentMethods')->name('user.store_payment_methods');
  
  Route::get('/edit-payment-methods/{id}', 'FrontEnd\UserController@editPaymentMethods')->name('user.edit_payment_method');
  Route::post('/update-payment-methods/{id}', 'FrontEnd\UserController@updatePaymentMethods')->name('user.update_payment_method');
  Route::post('/delete-payment-methods/{id}', 'FrontEnd\UserController@deletePaymentMethods')->name('user.delete_payment_method');
  // code by AG end

  // update password route
  Route::post('/update-password', 'FrontEnd\UserController@updatePassword')->name('user.update_password')->withoutMiddleware('change.lang');

  // equipment bookings route
  Route::get('/equipment-bookings', 'FrontEnd\UserController@bookings')->name('user.equipment_bookings');

  // booking details route
  Route::get('/equipment-booking/{id}/details', 'FrontEnd\UserController@bookingDetails')->name('user.equipment_booking.details');
  
  // code by AG start
  Route::get('/equipment-booking/{id}/additional_service', 'FrontEnd\UserController@additional_service')->name('user.equipment_booking.additional_service');
  
  Route::get('/equipment-booking/{id}/swap_equipment', 'FrontEnd\UserController@swap_equipment')->name('user.equipment_booking.swap_equipment');
  Route::get('/equipment-booking/{id}/return_equipment', 'FrontEnd\UserController@return_equipment')->name('user.equipment_booking.return_equipment');
  Route::get('/equipment-booking/{id}/relocate_equipment', 'FrontEnd\UserController@relocate_equipment')->name('user.equipment_booking.relocate_equipment');
  // code by AG end

  // product orders route
  Route::get('/product-orders', 'FrontEnd\UserController@orders')->name('user.product_orders');

  Route::prefix('/product-order')->group(function () {
    // order details route
    Route::get('/{id}/details', 'FrontEnd\UserController@orderDetails')->name('user.product_order.details');

    // download digital product route
    Route::post('/product/{id}/download', 'FrontEnd\UserController@downloadProduct')->name('user.product_order.product.download');
  });

  // user logout attempt route
  Route::get('/logout', 'FrontEnd\UserController@logoutSubmit')->name('user.logout')->withoutMiddleware('change.lang');
  
  
    //   manager routes
    Route::get('/managers', 'FrontEnd\ManagerController@manager')->name('user.manager');
    Route::get('/managers/create', 'FrontEnd\ManagerController@manager_create')->name('user.manager.create');
    Route::post('/managers/store', 'FrontEnd\ManagerController@manager_store')->name('user.manager.store');
    Route::post('/managers/delete/{id}', 'FrontEnd\ManagerController@manager_delete')->name('user.manager.delete');
    Route::get('/managers/edit/{id}', 'FrontEnd\ManagerController@manager_edit')->name('user.manager.edit');
    Route::post('/managers/update/{id}', 'FrontEnd\ManagerController@manager_update')->name('user.manager.update');
  
    //   branch routes
    Route::get('/branches', 'FrontEnd\BranchController@branch')->name('user.branch');
    Route::get('/branches/create', 'FrontEnd\BranchController@branch_create')->name('user.branch.create');
    Route::post('/branches/store', 'FrontEnd\BranchController@branch_store')->name('user.branch.store');
    Route::post('/branches/delete/{id}', 'FrontEnd\BranchController@branch_delete')->name('user.branch.delete');
    Route::get('/branch/edit/{id}', 'FrontEnd\BranchController@branch_edit')->name('user.branch.edit');
    Route::post('/branch/update/{id}', 'FrontEnd\BranchController@branch_update')->name('user.branch.update');
    Route::post('/branch/assign', 'FrontEnd\BranchController@assign_manager')->name('user.branch.manager.assign');
    Route::post('/branch/unassign', 'FrontEnd\BranchController@unassign_manager')->name('user.branch.manager.unassign');
    Route::get('/branch/get_manager', 'FrontEnd\BranchController@get_manager')->name('user.branch.manager.get_manager');

});

// service unavailable route
Route::get('/service-unavailable', 'FrontEnd\MiscellaneousController@serviceUnavailable')->name('service_unavailable')->middleware('exists.down');

/*
|--------------------------------------------------------------------------
| admin frontend route 
|--------------------------------------------------------------------------
*/

Route::prefix('/admin')->middleware('guest:admin')->group(function () {
  // admin redirect to login page route
  Route::get('/', 'BackEnd\AdminController@login')->name('admin.login');

  // admin login attempt route
  Route::post('/auth', 'BackEnd\AdminController@authentication')->name('admin.auth');

  // admin forget password route
  Route::get('/forget-password', 'BackEnd\AdminController@forgetPassword')->name('admin.forget_password');

  // send mail to admin for forget password route
  Route::post('/mail-for-forget-password', 'BackEnd\AdminController@forgetPasswordMail')->name('admin.mail_for_forget_password');
});


/*
|--------------------------------------------------------------------------
| Custom Page Route For UI
|--------------------------------------------------------------------------
*/

// Route::get("/test-page", function(){
//   return view('helly.test-page');
// });

// Route::get("/test-auth", function(){
//   return view('helly.test-auth');
// });


// Route::get('/{slug}', 'FrontEnd\PageController@page')->name('dynamic_page')->middleware('change.lang');

// fallback route
Route::fallback(function () {
  return view('errors.404');
})->middleware('change.lang');
