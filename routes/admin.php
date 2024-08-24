<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BackEnd\Membership\MemberShipFeature;
use App\Http\Controllers\BackEnd\Membership\MemberShipPlans;

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/


Route::prefix('/admin')->middleware('auth:admin')->group(function () {

    // code by AG start
    Route::get('/route-management', 'BackEnd\AdminController@advance_route')->name('admin.advanceroute');
     Route::get('/get-unread-notifications', 'BackEnd\AdminController@get_unread_notifications')->name('admin.get.notification');
    // code by AG end

  // admin redirect to dashboard route
  Route::get('/dashboard', 'BackEnd\AdminController@redirectToDashboard')->name('admin.dashboard');
  Route::get('/monthly-profit', 'BackEnd\AdminController@monthly_profit')->name('admin.monthly_profit');
  Route::get('/monthly-earning', 'BackEnd\AdminController@monthly_earning')->name('admin.monthly_earning');

  // change admin-panel theme (dark/light) route
  Route::get('/change-theme', 'BackEnd\AdminController@changeTheme')->name('admin.change_theme');

  // admin profile settings route start
  Route::get('/edit-profile', 'BackEnd\AdminController@editProfile')->name('admin.edit_profile');

  Route::post('/update-profile', 'BackEnd\AdminController@updateProfile')->name('admin.update_profile');

  Route::get('/change-password', 'BackEnd\AdminController@changePassword')->name('admin.change_password');

  Route::post('/update-password', 'BackEnd\AdminController@updatePassword')->name('admin.update_password');
  // admin profile settings route end

  // admin logout attempt route
  Route::get('/logout', 'BackEnd\AdminController@logout')->name('admin.logout');


  // admin management route start
  Route::prefix('/admin-management')->middleware('permission:Admin Management')->group(function () {
    // role-permission route
    Route::get('/role-permissions', 'BackEnd\Administrator\RolePermissionController@index')->name('admin.admin_management.role_permissions');

    Route::post('/store-role', 'BackEnd\Administrator\RolePermissionController@store')->name('admin.admin_management.store_role');

    Route::get('/role/{id}/permissions', 'BackEnd\Administrator\RolePermissionController@permissions')->name('admin.admin_management.role.permissions');

    Route::post('/role/{id}/update-permissions', 'BackEnd\Administrator\RolePermissionController@updatePermissions')->name('admin.admin_management.role.update_permissions');

    Route::post('/update-role', 'BackEnd\Administrator\RolePermissionController@update')->name('admin.admin_management.update_role');

    Route::post('/delete-role/{id}', 'BackEnd\Administrator\RolePermissionController@destroy')->name('admin.admin_management.delete_role');

    // registered admin route
    Route::get('/registered-admins', 'BackEnd\Administrator\SiteAdminController@index')->name('admin.admin_management.registered_admins');

    Route::post('/store-admin', 'BackEnd\Administrator\SiteAdminController@store')->name('admin.admin_management.store_admin');

    Route::post('/update-status/{id}', 'BackEnd\Administrator\SiteAdminController@updateStatus')->name('admin.admin_management.update_status');

    Route::post('/update-admin', 'BackEnd\Administrator\SiteAdminController@update')->name('admin.admin_management.update_admin');

    Route::post('/delete-admin/{id}', 'BackEnd\Administrator\SiteAdminController@destroy')->name('admin.admin_management.delete_admin');
  });
  // admin management route end


  // language management route start
  // Route::prefix('/language-management')->middleware('permission:Language Management')->group(function () {
  //   Route::get('', 'BackEnd\LanguageController@index')->name('admin.language_management');

  //   Route::post('/store', 'BackEnd\LanguageController@store')->name('admin.language_management.store');

  //   Route::post('/{id}/make-default-language', 'BackEnd\LanguageController@makeDefault')->name('admin.language_management.make_default_language');

  //   Route::post('/update', 'BackEnd\LanguageController@update')->name('admin.language_management.update');

  //   Route::get('/{id}/edit-keyword', 'BackEnd\LanguageController@editKeyword')->name('admin.language_management.edit_keyword');

  //   Route::post('add-keyword', 'BackEnd\LanguageController@addKeyword')->name('admin.language_management.add_keyword');

  //   Route::post('/{id}/update-keyword', 'BackEnd\LanguageController@updateKeyword')->name('admin.language_management.update_keyword');

  //   Route::post('/{id}/delete', 'BackEnd\LanguageController@destroy')->name('admin.language_management.delete');

  //   Route::get('/{id}/check-rtl', 'BackEnd\LanguageController@checkRTL');
  // });
  // language management route end


  Route::prefix('/basic-settings')->middleware('permission:Basic Settings')->group(function () {
    // basic settings favicon route
    Route::get('pwa', 'BackEnd\BasicSettings\BasicController@pwa')->name('admin.pwa');
    Route::post('/pwa/post', 'BackEnd\BasicSettings\BasicController@updatepwa')->name('admin.pwa.update');

    Route::get('/favicon', 'BackEnd\BasicSettings\BasicController@favicon')->name('admin.basic_settings.favicon');

    Route::post('/update-favicon', 'BackEnd\BasicSettings\BasicController@updateFavicon')->name('admin.basic_settings.update_favicon');

    // basic settings logo route
    Route::get('/logo', 'BackEnd\BasicSettings\BasicController@logo')->name('admin.basic_settings.logo');

    Route::post('/update-logo', 'BackEnd\BasicSettings\BasicController@updateLogo')->name('admin.basic_settings.update_logo');

    // basic settings information route
    Route::get('/information', 'BackEnd\BasicSettings\BasicController@information')->name('admin.basic_settings.information');

    Route::post('/update-info', 'BackEnd\BasicSettings\BasicController@updateInfo')->name('admin.basic_settings.update_info');

    Route::get('/general-settings', 'BackEnd\BasicSettings\BasicController@general_settings')->name('admin.basic_settings.general_settings');

    Route::post('/update-general-settings', 'BackEnd\BasicSettings\BasicController@update_general_setting')->name('admin.basic_settings.general_settings.update');

    Route::get('/contact-page', 'BackEnd\BasicSettings\BasicController@contact_page')->name('admin.basic_settings.contact_page');

    Route::post('/update-contact-page', 'BackEnd\BasicSettings\BasicController@update_contact_page')->name('admin.basic_settings.contact_page.update');

    // basic settings (theme & home) route
    Route::get('/theme-and-home', 'BackEnd\BasicSettings\BasicController@themeAndHome')->name('admin.basic_settings.theme_and_home');

    Route::post('/update-theme-and-home', 'BackEnd\BasicSettings\BasicController@updateThemeAndHome')->name('admin.basic_settings.update_theme_and_home');

    // basic settings currency route
    Route::get('/currency', 'BackEnd\BasicSettings\BasicController@currency')->name('admin.basic_settings.currency');

    Route::post('/update-currency', 'BackEnd\BasicSettings\BasicController@updateCurrency')->name('admin.basic_settings.update_currency');

    // basic settings appearance route
    Route::get('/appearance', 'BackEnd\BasicSettings\BasicController@appearance')->name('admin.basic_settings.appearance');

    Route::post('/update-appearance', 'BackEnd\BasicSettings\BasicController@updateAppearance')->name('admin.basic_settings.update_appearance');

    // basic settings mail route start
    Route::get('/mail-from-admin', 'BackEnd\BasicSettings\BasicController@mailFromAdmin')->name('admin.basic_settings.mail_from_admin');

    Route::post('/update-mail-from-admin', 'BackEnd\BasicSettings\BasicController@updateMailFromAdmin')->name('admin.basic_settings.update_mail_from_admin');

    Route::get('/mail-to-admin', 'BackEnd\BasicSettings\BasicController@mailToAdmin')->name('admin.basic_settings.mail_to_admin');

    Route::post('/update-mail-to-admin', 'BackEnd\BasicSettings\BasicController@updateMailToAdmin')->name('admin.basic_settings.update_mail_to_admin');

    Route::get('/mail-templates', 'BackEnd\BasicSettings\MailTemplateController@index')->name('admin.basic_settings.mail_templates');

    Route::get('/edit-mail-template/{id}', 'BackEnd\BasicSettings\MailTemplateController@edit')->name('admin.basic_settings.edit_mail_template');

    Route::post('/update-mail-template/{id}', 'BackEnd\BasicSettings\MailTemplateController@update')->name('admin.basic_settings.update_mail_template');
    // basic settings mail route end

    // basic settings breadcrumb route
    Route::get('/breadcrumb', 'BackEnd\BasicSettings\BasicController@breadcrumb')->name('admin.basic_settings.breadcrumb');

    Route::post('/update-breadcrumb', 'BackEnd\BasicSettings\BasicController@updateBreadcrumb')->name('admin.basic_settings.update_breadcrumb');

    // basic settings page-headings route
    Route::get('/page-headings', 'BackEnd\BasicSettings\PageHeadingController@pageHeadings')->name('admin.basic_settings.page_headings');

    Route::post('/update-page-headings', 'BackEnd\BasicSettings\PageHeadingController@updatePageHeadings')->name('admin.basic_settings.update_page_headings');

    // basic settings plugins route start
    Route::get('/plugins', 'BackEnd\BasicSettings\BasicController@plugins')->name('admin.basic_settings.plugins');

    Route::post('/update-disqus', 'BackEnd\BasicSettings\BasicController@updateDisqus')->name('admin.basic_settings.update_disqus');

    Route::post('/update-tawkto', 'BackEnd\BasicSettings\BasicController@updateTawkTo')->name('admin.basic_settings.update_tawkto');

    Route::post('/update-recaptcha', 'BackEnd\BasicSettings\BasicController@updateRecaptcha')->name('admin.basic_settings.update_recaptcha');

    Route::post('/update-facebook', 'BackEnd\BasicSettings\BasicController@updateFacebook')->name('admin.basic_settings.update_facebook');

    Route::post('/update-google', 'BackEnd\BasicSettings\BasicController@updateGoogle')->name('admin.basic_settings.update_google');

    Route::post('/update-whatsapp', 'BackEnd\BasicSettings\BasicController@updateWhatsApp')->name('admin.basic_settings.update_whatsapp');
    // basic settings plugins route end

    // basic settings seo route
    Route::get('/seo', 'BackEnd\BasicSettings\SEOController@index')->name('admin.basic_settings.seo');

    Route::post('/update-seo', 'BackEnd\BasicSettings\SEOController@update')->name('admin.basic_settings.update_seo');

    // basic settings maintenance-mode route
    Route::get('/maintenance-mode', 'BackEnd\BasicSettings\BasicController@maintenance')->name('admin.basic_settings.maintenance_mode');

    Route::post('/update-maintenance-mode', 'BackEnd\BasicSettings\BasicController@updateMaintenance')->name('admin.basic_settings.update_maintenance_mode');

    // basic settings cookie-alert route
    Route::get('/cookie-alert', 'BackEnd\BasicSettings\CookieAlertController@cookieAlert')->name('admin.basic_settings.cookie_alert');

    Route::post('/update-cookie-alert', 'BackEnd\BasicSettings\CookieAlertController@updateCookieAlert')->name('admin.basic_settings.update_cookie_alert');

    // basic-settings social-media route
    Route::get('/social-medias', 'BackEnd\BasicSettings\SocialMediaController@index')->name('admin.basic_settings.social_medias');

    Route::post('/store-social-media', 'BackEnd\BasicSettings\SocialMediaController@store')->name('admin.basic_settings.store_social_media');

    Route::post('/update-social-media', 'BackEnd\BasicSettings\SocialMediaController@update')->name('admin.basic_settings.update_social_media');

    Route::post('/delete-social-media/{id}', 'BackEnd\BasicSettings\SocialMediaController@destroy')->name('admin.basic_settings.delete_social_media');
    
    // basic-settings recaptcha route
    Route::get('/recaptcha', 'BackEnd\BasicSettings\RecaptchaController@index')->name('admin.basic_settings.recaptcha');
    Route::post('/recaptcha', 'BackEnd\BasicSettings\RecaptchaController@update')->name('admin.basic_settings.store_recaptcha');
    
    
    // code by AG start
    Route::get('/communication-settings', 'BackEnd\Custom\OptionController@index')->name('admin.basic_settings.communication_settings');
    Route::post('/communication-settings-update', 'BackEnd\Custom\OptionController@update')->name('admin.basic_settings.communication_settings_update');
    // code by AG end
});


  // announcement-popup route start
  Route::prefix('/announcement-popups')->middleware('permission:Announcement Popups')->group(function () {
    Route::get('', 'BackEnd\PopupController@index')->name('admin.announcement_popups');

    Route::get('/select-popup-type', 'BackEnd\PopupController@popupType')->name('admin.announcement_popups.select_popup_type');

    Route::get('/create-popup/{type}', 'BackEnd\PopupController@create')->name('admin.announcement_popups.create_popup');

    Route::post('/store-popup', 'BackEnd\PopupController@store')->name('admin.announcement_popups.store_popup');

    Route::post('/popup/{id}/update-status', 'BackEnd\PopupController@updateStatus')->name('admin.announcement_popups.update_popup_status');

    Route::get('/edit-popup/{id}', 'BackEnd\PopupController@edit')->name('admin.announcement_popups.edit_popup');

    Route::post('/update-popup/{id}', 'BackEnd\PopupController@update')->name('admin.announcement_popups.update_popup');

    Route::post('/delete-popup/{id}', 'BackEnd\PopupController@destroy')->name('admin.announcement_popups.delete_popup');

    Route::post('/bulk-delete-popup', 'BackEnd\PopupController@bulkDestroy')->name('admin.announcement_popups.bulk_delete_popup');
  });
  // announcement-popup route end


  // menu-builder route start
  Route::prefix('/menu-builder')->middleware('permission:Menu Builder')->group(function () {
    Route::get('', 'BackEnd\MenuBuilderController@index')->name('admin.menu_builder');

    Route::post('/update-menus', 'BackEnd\MenuBuilderController@update')->name('admin.menu_builder.update_menus');
  });
  // menu-builder route end


  // home-page route start
  Route::prefix('/home-page')->middleware('permission:Home Page')->group(function () {
    // hero section
    Route::prefix('/hero-section')->group(function () {
      // slider version route
      Route::prefix('/slider-version')->group(function () {
        Route::get('', 'BackEnd\HomePage\Hero\SliderController@index')->name('admin.home_page.hero_section.slider_version');

        Route::post('/store', 'BackEnd\HomePage\Hero\SliderController@store')->name('admin.home_page.hero_section.slider_version.store');

        Route::post('/update', 'BackEnd\HomePage\Hero\SliderController@update')->name('admin.home_page.hero_section.slider_version.update');

        Route::post('/{id}/delete', 'BackEnd\HomePage\Hero\SliderController@destroy')->name('admin.home_page.hero_section.slider_version.delete');
      });

      // static version route
      Route::prefix('/static-version')->group(function () {
        Route::get('', 'BackEnd\HomePage\Hero\StaticController@index')->name('admin.home_page.hero_section.static_version');

        Route::post('/update-image', 'BackEnd\HomePage\Hero\StaticController@updateImage')->name('admin.home_page.hero_section.static_version.update_image');

        Route::post('/update-information', 'BackEnd\HomePage\Hero\StaticController@updateInformation')->name('admin.home_page.hero_section.static_version.update_information');
      });
    });

    // about section
    Route::get('/about-section', 'BackEnd\HomePage\AboutController@index')->name('admin.home_page.about_section');

    Route::post('/update-about-section-image', 'BackEnd\HomePage\AboutController@updateImage')->name('admin.home_page.update_about_section_image');

    Route::post('/update-about-section', 'BackEnd\HomePage\AboutController@update')->name('admin.home_page.update_about_section');

    // work process section
    Route::get('/work-process-section', 'BackEnd\HomePage\WorkProcessController@sectionInfo')->name('admin.home_page.work_process_section');

    Route::post('/update-work-process-section', 'BackEnd\HomePage\WorkProcessController@updateSectionInfo')->name('admin.home_page.update_work_process_section');

    Route::prefix('/work-process')->group(function () {
      Route::post('/store', 'BackEnd\HomePage\WorkProcessController@storeWorkProcess')->name('admin.home_page.store_work_process');

      Route::post('/update', 'BackEnd\HomePage\WorkProcessController@updateWorkProcess')->name('admin.home_page.update_work_process');

      Route::post('{id}/delete', 'BackEnd\HomePage\WorkProcessController@destroyWorkProcess')->name('admin.home_page.delete_work_process');

      Route::post('/bulk-delete', 'BackEnd\HomePage\WorkProcessController@bulkDestroyWorkProcess')->name('admin.home_page.bulk_delete_work_process');
    });

    // features section
    Route::get('/feature-section', 'BackEnd\HomePage\FeatureController@sectionInfo')->name('admin.home_page.feature_section');

    Route::post('/update-feature-section', 'BackEnd\HomePage\FeatureController@updateSectionInfo')->name('admin.home_page.update_feature_section');

    Route::prefix('/feature')->group(function () {
      Route::post('/store', 'BackEnd\HomePage\FeatureController@storeFeature')->name('admin.home_page.store_feature');

      Route::post('/update', 'BackEnd\HomePage\FeatureController@updateFeature')->name('admin.home_page.update_feature');

      Route::post('{id}/delete', 'BackEnd\HomePage\FeatureController@destroyFeature')->name('admin.home_page.delete_feature');

      Route::post('/bulk-delete', 'BackEnd\HomePage\FeatureController@bulkDestroyFeature')->name('admin.home_page.bulk_delete_feature');
    });

    // counter section
    Route::get('/counter-section', 'BackEnd\HomePage\CounterController@index')->name('admin.home_page.counter_section');

    Route::post('/update-counter-section-image', 'BackEnd\HomePage\CounterController@updateImage')->name('admin.home_page.update_counter_section_image');

    Route::prefix('/counter')->group(function () {
      Route::post('/store', 'BackEnd\HomePage\CounterController@storeCounter')->name('admin.home_page.store_counter');

      Route::post('/update', 'BackEnd\HomePage\CounterController@updateCounter')->name('admin.home_page.update_counter');

      Route::post('{id}/delete', 'BackEnd\HomePage\CounterController@destroyCounter')->name('admin.home_page.delete_counter');

      Route::post('/bulk-delete', 'BackEnd\HomePage\CounterController@bulkDestroyCounter')->name('admin.home_page.bulk_delete_counter');
    });

    // equipment section
    Route::get('/equipment-section', 'BackEnd\HomePage\EquipmentController@index')->name('admin.home_page.equipment_section');

    Route::post('/update-equipment-section', 'BackEnd\HomePage\EquipmentController@update')->name('admin.home_page.update_equipment_section');

    // testimonial section
    Route::get('/testimonial-section', 'BackEnd\HomePage\TestimonialController@index')->name('admin.home_page.testimonial_section');

    Route::post('/update-testimonial-section', 'BackEnd\HomePage\TestimonialController@updateSectionInfo')->name('admin.home_page.update_testimonial_section');

    Route::prefix('/testimonial')->group(function () {
      Route::post('/store', 'BackEnd\HomePage\TestimonialController@storeTestimonial')->name('admin.home_page.store_testimonial');

      Route::post('/update', 'BackEnd\HomePage\TestimonialController@updateTestimonial')->name('admin.home_page.update_testimonial');

      Route::post('{id}/delete', 'BackEnd\HomePage\TestimonialController@destroyTestimonial')->name('admin.home_page.delete_testimonial');

      Route::post('/bulk-delete', 'BackEnd\HomePage\TestimonialController@bulkDestroyTestimonial')->name('admin.home_page.bulk_delete_testimonial');
    });

    // product section
    Route::get('/product-section', 'BackEnd\HomePage\ProductController@index')->name('admin.home_page.product_section');

    Route::post('/update-product-section', 'BackEnd\HomePage\ProductController@updateSectionInfo')->name('admin.home_page.update_product_section');

    // call to action section
    Route::get('/call-to-action-section', 'BackEnd\HomePage\CallToActionController@index')->name('admin.home_page.call_to_action_section');

    Route::post('/update-call-to-action-section-image', 'BackEnd\HomePage\CallToActionController@updateImage')->name('admin.home_page.update_call_to_action_section_image');

    Route::post('/update-call-to-action-section', 'BackEnd\HomePage\CallToActionController@update')->name('admin.home_page.update_call_to_action_section');

    // blog section
    Route::get('/blog-section', 'BackEnd\HomePage\BlogController@index')->name('admin.home_page.blog_section');

    Route::post('/update-blog-section', 'BackEnd\HomePage\BlogController@update')->name('admin.home_page.update_blog_section');

    // section customization
    Route::get('/section-customization', 'BackEnd\HomePage\SectionController@index')->name('admin.home_page.section_customization');

    Route::post('/update-section-status', 'BackEnd\HomePage\SectionController@update')->name('admin.home_page.update_section_status');

    // partners route
    Route::get('/partners', 'BackEnd\HomePage\PartnerController@index')->name('admin.home_page.partners');

    Route::post('/store-partner', 'BackEnd\HomePage\PartnerController@store')->name('admin.home_page.store_partner');

    Route::post('/update-partner', 'BackEnd\HomePage\PartnerController@update')->name('admin.home_page.update_partner');

    Route::post('/delete-partner/{id}', 'BackEnd\HomePage\PartnerController@destroy')->name('admin.home_page.delete_partner');
  });
  // home-page route end


  // payment-gateway route start
  Route::prefix('/payment-gateways')->middleware('permission:Payment Gateways')->group(function () {
    Route::get('/online-gateways', 'BackEnd\PaymentGateway\OnlineGatewayController@index')->name('admin.payment_gateways.online_gateways');

    Route::post('/update-paypal-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updatePayPalInfo')->name('admin.payment_gateways.update_paypal_info');

    Route::post('/update-instamojo-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateInstamojoInfo')->name('admin.payment_gateways.update_instamojo_info');

    Route::post('/update-paystack-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updatePaystackInfo')->name('admin.payment_gateways.update_paystack_info');

    Route::post('/update-flutterwave-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateFlutterwaveInfo')->name('admin.payment_gateways.update_flutterwave_info');

    Route::post('/update-razorpay-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateRazorpayInfo')->name('admin.payment_gateways.update_razorpay_info');

    Route::post('/update-mercadopago-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateMercadoPagoInfo')->name('admin.payment_gateways.update_mercadopago_info');

    Route::post('/update-mollie-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateMollieInfo')->name('admin.payment_gateways.update_mollie_info');

    Route::post('/update-stripe-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateStripeInfo')->name('admin.payment_gateways.update_stripe_info');

    Route::post('/update-paytm-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updatePaytmInfo')->name('admin.payment_gateways.update_paytm_info');

    // code by AG start
    Route::post('/update-stax-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateStaxInfo')->name('admin.payment_gateways.update_stax_info');
    Route::post('/update-square-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateSquareInfo')->name('admin.payment_gateways.update_square_info');
    
    Route::post('/update-resolve-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateResolveInfo')->name('admin.payment_gateways.update_resolve_info');
    
    // code by AG end

    Route::get('/offline-gateways', 'BackEnd\PaymentGateway\OfflineGatewayController@index')->name('admin.payment_gateways.offline_gateways');

    Route::post('/store-offline-gateway', 'BackEnd\PaymentGateway\OfflineGatewayController@store')->name('admin.payment_gateways.store_offline_gateway');

    Route::post('/update-status/{id}', 'BackEnd\PaymentGateway\OfflineGatewayController@updateStatus')->name('admin.payment_gateways.update_status');

    Route::post('/update-offline-gateway', 'BackEnd\PaymentGateway\OfflineGatewayController@update')->name('admin.payment_gateways.update_offline_gateway');

    Route::post('/delete-offline-gateway/{id}', 'BackEnd\PaymentGateway\OfflineGatewayController@destroy')->name('admin.payment_gateways.delete_offline_gateway');
  });
  // payment-gateway route end
  
  // payment-gateway route start
    Route::prefix('/membership')->name('admin.')->group(function () {
        Route::resource('plans', MemberShipPlans::class);
        Route::resource('features', MemberShipFeature::class);
        Route::get('plans/{id}/features', [MemberShipPlans::class,'features'])->name('plan.feature');
        Route::post('plans/{id}/features', [MemberShipPlans::class,'features_store'])->name('plan.feature.store');
    });
  // payment-gateway route end


  // equipment route start
  Route::prefix('/equipment-management')->middleware('permission:Equipment')->group(function () {
    // equipment category route
    Route::get('/categories', 'BackEnd\Instrument\CategoryController@index')->name('admin.equipment_management.categories');

    Route::post('/store-category', 'BackEnd\Instrument\CategoryController@store')->name('admin.equipment_management.store_category');

    Route::post('/update-category', 'BackEnd\Instrument\CategoryController@update')->name('admin.equipment_management.update_category');

    Route::post('/delete-category/{id}', 'BackEnd\Instrument\CategoryController@destroy')->name('admin.equipment_management.delete_category');

    Route::post('/bulk-delete-category', 'BackEnd\Instrument\CategoryController@bulkDestroy')->name('admin.equipment_management.bulk_delete_category');

    // code by AG start
    Route::get('/edit-equipment-fields/{id}', 'BackEnd\Instrument\CategoryController@edit_equipment_fields')->name('admin.equipment_management.edit_equipment_field');
    Route::post('/update-equipment-fields/{id}', 'BackEnd\Instrument\CategoryController@update_equipment_fields')->name('admin.equipment_management.update_equipment_field');
    Route::get('/get-equipment-fields', 'BackEnd\Instrument\CategoryController@get_equipment_fields')->name('admin.equipment_management.get_equipment_fields');

    // code by AG end


    // equipment route
    Route::get('/all-equipment', 'BackEnd\Instrument\EquipmentController@index')->name('admin.equipment_management.all_equipment');

    Route::get('/create-equipment', 'BackEnd\Instrument\EquipmentController@create')->name('admin.equipment_management.create_equipment');

    Route::get('/get-location', 'BackEnd\Instrument\EquipmentController@get_location')->name('admin.equipment_management.get_location');

    Route::post('/upload-slider-image', 'BackEnd\Instrument\EquipmentController@uploadImage')->name('admin.equipment_management.upload_slider_image');

    Route::post('/remove-slider-image', 'BackEnd\Instrument\EquipmentController@removeImage')->name('admin.equipment_management.remove_slider_image');

    Route::post('/store-equipment', 'BackEnd\Instrument\EquipmentController@store')->name('admin.equipment_management.store_equipment');

    Route::post('/{id}/update-featured', 'BackEnd\Instrument\EquipmentController@updateFeatured')->name('admin.equipment_management.update_featured');

    Route::get('/edit-equipment/{id}', 'BackEnd\Instrument\EquipmentController@edit')->name('admin.equipment_management.edit_equipment');

    Route::post('/detach-slider-image', 'BackEnd\Instrument\EquipmentController@detachImage')->name('admin.equipment_management.detach_slider_image');

    Route::post('/update-equipment/{id}', 'BackEnd\Instrument\EquipmentController@update')->name('admin.equipment_management.update_equipment');

    Route::post('/delete-equipment/{id}', 'BackEnd\Instrument\EquipmentController@destroy')->name('admin.equipment_management.delete_equipment');

    Route::post('/bulk-delete-equipment', 'BackEnd\Instrument\EquipmentController@bulkDestroy')->name('admin.equipment_management.bulk_delete_equipment');
  });
  // equipment route end
  
  // code by AG start 
  // equipment-quotations route start
  // Route::prefix('/equipment-quotations')->middleware('permission:Equipment')->group(function () {
	//   // quotations route
  //   Route::get('/quotations', 'BackEnd\Instrument\EquipmentQuotation@quotations')->name('admin.equipment_quotations.quotations');

  //   Route::get('/{id}/details', 'BackEnd\Instrument\EquipmentQuotation@show')->name('admin.equipment_quotations.details');

  //   Route::post('/{id}/delete', 'BackEnd\Instrument\EquipmentQuotation@destroy')->name('admin.equipment_quotations.delete');

  //   Route::post('/bulk-delete', 'BackEnd\Instrument\EquipmentQuotation@bulkDestroy')->name('admin.equipment_quotations.bulk_delete');

  // });
  // code by AG end


  // equipment-booking route start
  Route::prefix('/equipment-booking')->middleware('permission:Equipment Booking')->group(function () {
    Route::prefix('/settings')->group(function () {
      // tax route
      Route::get('/tax-amount', 'BackEnd\BasicSettings\BasicController@equipmentTaxAmount')->name('admin.equipment_booking.settings.tax_amount');

      Route::post('/update-tax-amount', 'BackEnd\BasicSettings\BasicController@updateEquipmentTaxAmount')->name('admin.equipment_booking.settings.update_tax_amount');

      // coupon route
      Route::get('/coupons', 'BackEnd\Instrument\CouponController@index')->name('admin.equipment_booking.settings.coupons');

      Route::post('/store-coupon', 'BackEnd\Instrument\CouponController@store')->name('admin.equipment_booking.settings.store_coupon');

      Route::post('/update-coupon', 'BackEnd\Instrument\CouponController@update')->name('admin.equipment_booking.settings.update_coupon');

      Route::post('/delete-coupon/{id}', 'BackEnd\Instrument\CouponController@destroy')->name('admin.equipment_booking.settings.delete_coupon');
    
      // code by AG start
        
      // shipping status route
      Route::get('/shipping-status', 'BackEnd\Custom\ShippingStatusController@index')->name('admin.equipment_booking.settings.shippingstatus');

      Route::post('/store-shipping-status', 'BackEnd\Custom\ShippingStatusController@store')->name('admin.equipment_booking.settings.store_shippingstatus');

      Route::post('/update-shipping-status', 'BackEnd\Custom\ShippingStatusController@update')->name('admin.equipment_booking.settings.update_shippingstatus');

      Route::post('/delete-shipping-status/{id}', 'BackEnd\Custom\ShippingStatusController@destroy')->name('admin.equipment_booking.settings.delete_shippingstatus');
    
      // code by AG end
      // shipping-method route
      Route::get('/shipping-methods', 'BackEnd\BasicSettings\BasicController@methodSettings')->name('admin.equipment_booking.settings.shipping_methods');

      Route::post('/update-method-settings', 'BackEnd\BasicSettings\BasicController@updateMethodSettings')->name('admin.equipment_booking.settings.update_method_settings');

      // location route
      Route::get('/locations', 'BackEnd\Instrument\LocationController@index')->name('admin.equipment_booking.settings.locations');

      Route::post('/store-location', 'BackEnd\Instrument\LocationController@store')->name('admin.equipment_booking.settings.store_location');

      Route::post('/update-location', 'BackEnd\Instrument\LocationController@update')->name('admin.equipment_booking.settings.update_location');

      Route::post('/delete-location/{id}', 'BackEnd\Instrument\LocationController@destroy')->name('admin.equipment_booking.settings.delete_location');

      Route::post('/bulk-delete-location', 'BackEnd\Instrument\LocationController@bulkDestroy')->name('admin.equipment_booking.settings.bulk_delete_location');

      // guest checkout status route
      Route::get('/guest-checkout-status', 'BackEnd\BasicSettings\BasicController@checkoutStatus')->name('admin.equipment_booking.settings.guest_checkout_status');

      Route::post('/update-guest-checkout-status', 'BackEnd\BasicSettings\BasicController@updateCheckoutStatus')->name('admin.equipment_booking.settings.update_guest_checkout_status');


      // section customization
      Route::get('/commission', 'BackEnd\BasicSettings\CommissionController@index')->name('admin.basic_setting.commission');

      Route::post('/update-commission', 'BackEnd\BasicSettings\CommissionController@update')->name('admin.basic_setting.update_commission');
    });

    // booking route
    Route::get('/bookings', 'BackEnd\Instrument\BookingController@bookings')->name('admin.equipment_booking.bookings');
    
    Route::get('/bookings/create', 'BackEnd\Instrument\BookingController@create')->name('admin.equipment_booking.create');
    Route::post('/bookings/store', 'BackEnd\Instrument\BookingController@indexb')->name('admin.equipment_booking.store');
    Route::get('/bookings/equipment', 'BackEnd\Instrument\BookingController@get_equipment')->name('admin.equipment_booking.get_equipment');
    Route::get('/bookings/{id}/edit', 'BackEnd\Instrument\BookingController@edit')->name('admin.equipment_booking.edit');
    Route::post('/bookings/{id}/update', 'BackEnd\Instrument\BookingController@update')->name('admin.equipment_booking.update');
    Route::get('/bookings/get_cards', 'BackEnd\Instrument\BookingController@get_cards')->name('admin.equipment_booking.get_cards');
    Route::get('/bookings/get_user_data', 'BackEnd\Instrument\BookingController@get_user_data')->name('admin.equipment_booking.get_user_data');

    Route::post('/{id}/update-payment-status', 'BackEnd\Instrument\BookingController@updatePaymentStatus')->name('admin.equipment_booking.update_payment_status');

    Route::post('/{id}/update-shipping-status', 'BackEnd\Instrument\BookingController@updateShippingStatus')->name('admin.equipment_booking.update_shipping_status');

    Route::post('update-return-status', 'BackEnd\Instrument\BookingController@updateReturnStatus')->name('admin.equipment_booking.update_return_status');

    Route::post('update-return-status-2', 'BackEnd\Instrument\BookingController@updateReturnStatus2')->name('admin.equipment_booking.update_return_status2');

    Route::get('/{id}/details', 'BackEnd\Instrument\BookingController@show')->name('admin.equipment_booking.details');

    Route::post('/{id}/delete', 'BackEnd\Instrument\BookingController@destroy')->name('admin.equipment_booking.delete');

    Route::post('/bulk-delete', 'BackEnd\Instrument\BookingController@bulkDestroy')->name('admin.equipment_booking.bulk_delete');

    // report route
    Route::get('/report', 'BackEnd\Instrument\BookingController@report')->name('admin.equipment_booking.report');

    Route::get('/export-report', 'BackEnd\Instrument\BookingController@exportReport')->name('admin.equipment_booking.export_report');
  });
  // equipment-booking route end

  //refund routes are goes here
  Route::get('security-deposit-refunds',  'BackEnd\Instrument\SecurityDepositController@refund')->name('admin.security-deposit-refunds');

  Route::get('dispute-request',  'BackEnd\Instrument\SecurityDepositController@dispute_request')->name('admin.security-deposit.dispute-request');

  Route::post('refund-status/update',  'BackEnd\Instrument\SecurityDepositController@update_status')->name('admin.equipment_booking.update_refund_status');

  Route::post('security-deposit-request/delete',  'BackEnd\Instrument\SecurityDepositController@delete')->name('admin.security-deposit.request.delete');

  Route::post('security-deposit/refund-status',  'BackEnd\Instrument\SecurityDepositController@refund_status')->name('admin.security-deposit.refund-status');

  Route::post('security-deposit/refund-status2',  'BackEnd\Instrument\SecurityDepositController@refund_status2')->name('admin.security-deposit.refund-status2');

  Route::post('security-deposit-request/bulk-delete',  'BackEnd\Instrument\SecurityDepositController@bulk_delete')->name('admin.security-deposit.request.bulk_delete');


  // shop route start
  // Route::prefix('/shop-management')->middleware('permission:Shop Management')->group(function () {
  //   // tax route
  //   Route::get('/tax-amount', 'BackEnd\BasicSettings\BasicController@productTaxAmount')->name('admin.shop_management.tax_amount');

  //   Route::post('/update-tax-amount', 'BackEnd\BasicSettings\BasicController@updateProductTaxAmount')->name('admin.shop_management.update_tax_amount');

  //   // shipping charge route
  //   Route::get('/shipping-charges', 'BackEnd\Shop\ShippingChargeController@index')->name('admin.shop_management.shipping_charges');

  //   Route::post('/store-charge', 'BackEnd\Shop\ShippingChargeController@store')->name('admin.shop_management.store_charge');

  //   Route::post('/update-charge', 'BackEnd\Shop\ShippingChargeController@update')->name('admin.shop_management.update_charge');

  //   Route::post('/delete-charge/{id}', 'BackEnd\Shop\ShippingChargeController@destroy')->name('admin.shop_management.delete_charge');

  //   // coupon route
  //   Route::get('/coupons', 'BackEnd\Shop\CouponController@index')->name('admin.shop_management.coupons');

  //   Route::post('/store-coupon', 'BackEnd\Shop\CouponController@store')->name('admin.shop_management.store_coupon');

  //   Route::post('/update-coupon', 'BackEnd\Shop\CouponController@update')->name('admin.shop_management.update_coupon');

  //   Route::post('/delete-coupon/{id}', 'BackEnd\Shop\CouponController@destroy')->name('admin.shop_management.delete_coupon');

  //   // product category route
  //   Route::prefix('/product')->group(function () {
  //     Route::get('/categories', 'BackEnd\Shop\CategoryController@index')->name('admin.shop_management.product.categories');

  //     Route::post('/store-category', 'BackEnd\Shop\CategoryController@store')->name('admin.shop_management.product.store_category');

  //     Route::post('/update-category', 'BackEnd\Shop\CategoryController@update')->name('admin.shop_management.product.update_category');

  //     Route::post('/delete-category/{id}', 'BackEnd\Shop\CategoryController@destroy')->name('admin.shop_management.product.delete_category');

  //     Route::post('/bulk-delete-category', 'BackEnd\Shop\CategoryController@bulkDestroy')->name('admin.shop_management.product.bulk_delete_category');
  //   });

  //   // product route
  //   Route::get('/products', 'BackEnd\Shop\ProductController@index')->name('admin.shop_management.products');

  //   Route::get('/select-product-type', 'BackEnd\Shop\ProductController@productType')->name('admin.shop_management.select_product_type');

  //   Route::get('/create-product/{type}', 'BackEnd\Shop\ProductController@create')->name('admin.shop_management.create_product');

  //   Route::post('/upload-slider-image', 'BackEnd\Shop\ProductController@uploadImage')->name('admin.shop_management.upload_slider_image');

  //   Route::post('/remove-slider-image', 'BackEnd\Shop\ProductController@removeImage')->name('admin.shop_management.remove_slider_image');

  //   Route::post('/store-product', 'BackEnd\Shop\ProductController@store')->name('admin.shop_management.store_product');

  //   Route::post('/product/{id}/update-featured-status', 'BackEnd\Shop\ProductController@updateFeaturedStatus')->name('admin.shop_management.product.update_featured_status');

  //   Route::get('/edit-product/{id}/{type}', 'BackEnd\Shop\ProductController@edit')->name('admin.shop_management.edit_product');

  //   Route::post('/detach-slider-image', 'BackEnd\Shop\ProductController@detachImage')->name('admin.shop_management.detach_slider_image');

  //   Route::post('/update-product/{id}', 'BackEnd\Shop\ProductController@update')->name('admin.shop_management.update_product');

  //   Route::post('/delete-product/{id}', 'BackEnd\Shop\ProductController@destroy')->name('admin.shop_management.delete_product');

  //   Route::post('/bulk-delete-product', 'BackEnd\Shop\ProductController@bulkDestroy')->name('admin.shop_management.bulk_delete_product');

  //   // order route
  //   Route::get('/orders', 'BackEnd\Shop\OrderController@orders')->name('admin.shop_management.orders');

  //   Route::prefix('/order/{id}')->group(function () {
  //     Route::post('/update-payment-status', 'BackEnd\Shop\OrderController@updatePaymentStatus')->name('admin.shop_management.order.update_payment_status');

  //     Route::post('/update-order-status', 'BackEnd\Shop\OrderController@updateOrderStatus')->name('admin.shop_management.order.update_order_status');

  //     Route::get('/details', 'BackEnd\Shop\OrderController@show')->name('admin.shop_management.order.details');

  //     Route::post('/delete', 'BackEnd\Shop\OrderController@destroy')->name('admin.shop_management.order.delete');
  //   });

  //   Route::post('/bulk-delete-order', 'BackEnd\Shop\OrderController@bulkDestroy')->name('admin.shop_management.bulk_delete_order');

  //   // report route
  //   Route::get('/report', 'BackEnd\Shop\OrderController@report')->name('admin.shop_management.report');

  //   Route::get('/export-report', 'BackEnd\Shop\OrderController@exportReport')->name('admin.shop_management.export_report');
  // });
  // shop route end


  // blog route start
  // Route::prefix('/blog-management')->middleware('permission:Blog Management')->group(function () {
  //   // blog category route
  //   Route::get('/categories', 'BackEnd\Journal\CategoryController@index')->name('admin.blog_management.categories');

  //   Route::post('/store-category', 'BackEnd\Journal\CategoryController@store')->name('admin.blog_management.store_category');

  //   Route::post('/update-category', 'BackEnd\Journal\CategoryController@update')->name('admin.blog_management.update_category');

  //   Route::post('/delete-category/{id}', 'BackEnd\Journal\CategoryController@destroy')->name('admin.blog_management.delete_category');

  //   Route::post('/bulk-delete-category', 'BackEnd\Journal\CategoryController@bulkDestroy')->name('admin.blog_management.bulk_delete_category');

  //   // blog route
  //   Route::get('/blogs', 'BackEnd\Journal\BlogController@index')->name('admin.blog_management.blogs');

  //   Route::get('/create-blog', 'BackEnd\Journal\BlogController@create')->name('admin.blog_management.create_blog');

  //   Route::post('/store-blog', 'BackEnd\Journal\BlogController@store')->name('admin.blog_management.store_blog');

  //   Route::get('/edit-blog/{id}', 'BackEnd\Journal\BlogController@edit')->name('admin.blog_management.edit_blog');

  //   Route::post('/update-blog/{id}', 'BackEnd\Journal\BlogController@update')->name('admin.blog_management.update_blog');

  //   Route::post('/delete-blog/{id}', 'BackEnd\Journal\BlogController@destroy')->name('admin.blog_management.delete_blog');

  //   Route::post('/bulk-delete-blog', 'BackEnd\Journal\BlogController@bulkDestroy')->name('admin.blog_management.bulk_delete_blog');
  // });
  // blog route end


  // faq route start
  Route::prefix('/faq-management')->middleware('permission:FAQ Management')->group(function () {
    Route::get('', 'BackEnd\FaqController@index')->name('admin.faq_management');

    Route::post('/store-faq', 'BackEnd\FaqController@store')->name('admin.faq_management.store_faq');

    Route::post('/update-faq', 'BackEnd\FaqController@update')->name('admin.faq_management.update_faq');

    Route::post('/delete-faq/{id}', 'BackEnd\FaqController@destroy')->name('admin.faq_management.delete_faq');

    Route::post('/bulk-delete-faq', 'BackEnd\FaqController@bulkDestroy')->name('admin.faq_management.bulk_delete_faq');
  });
  // faq route end


  // custom-pages route start
  Route::prefix('/custom-pages')->middleware('permission:Custom Pages')->group(function () {
    Route::get('', 'BackEnd\CustomPageController@index')->name('admin.custom_pages');

    Route::get('/create-page', 'BackEnd\CustomPageController@create')->name('admin.custom_pages.create_page');

    Route::post('/store-page', 'BackEnd\CustomPageController@store')->name('admin.custom_pages.store_page');

    Route::get('/edit-page/{id}', 'BackEnd\CustomPageController@edit')->name('admin.custom_pages.edit_page');

    Route::post('/update-page/{id}', 'BackEnd\CustomPageController@update')->name('admin.custom_pages.update_page');

    Route::post('/delete-page/{id}', 'BackEnd\CustomPageController@destroy')->name('admin.custom_pages.delete_page');

    Route::post('/bulk-delete-page', 'BackEnd\CustomPageController@bulkDestroy')->name('admin.custom_pages.bulk_delete_page');
  });
  // custom-pages route end


  // advertise route start
  Route::prefix('/advertise')->middleware('permission:Advertise')->group(function () {
    Route::get('/settings', 'BackEnd\AdvertisementController@advertiseSettings')->name('admin.advertise.settings');

    Route::post('/update-settings', 'BackEnd\AdvertisementController@updateAdvertiseSettings')->name('admin.advertise.update_settings');

    Route::get('/all-advertisement', 'BackEnd\AdvertisementController@index')->name('admin.advertise.all_advertisement');

    Route::post('/store-advertisement', 'BackEnd\AdvertisementController@store')->name('admin.advertise.store_advertisement');

    Route::post('/update-advertisement', 'BackEnd\AdvertisementController@update')->name('admin.advertise.update_advertisement');

    Route::post('/delete-advertisement/{id}', 'BackEnd\AdvertisementController@destroy')->name('admin.advertise.delete_advertisement');

    Route::post('/bulk-delete-advertisement', 'BackEnd\AdvertisementController@bulkDestroy')->name('admin.advertise.bulk_delete_advertisement');
  });
  // advertise route end


  // footer route start
  Route::prefix('/footer')->middleware('permission:Footer')->group(function () {
    // logo & image route
    Route::get('/logo-and-image', 'BackEnd\Footer\ImageController@index')->name('admin.footer.logo_and_image');

    Route::post('/update-logo', 'BackEnd\Footer\ImageController@updateLogo')->name('admin.footer.update_logo');

    Route::post('/update-background-image', 'BackEnd\Footer\ImageController@updateImage')->name('admin.footer.update_background_image');

    // content route
    Route::get('/content', 'BackEnd\Footer\ContentController@index')->name('admin.footer.content');

    Route::post('/update-content', 'BackEnd\Footer\ContentController@update')->name('admin.footer.update_content');

    // quick link route
    Route::get('/quick-links', 'BackEnd\Footer\QuickLinkController@index')->name('admin.footer.quick_links');

    Route::post('/store-quick-link', 'BackEnd\Footer\QuickLinkController@store')->name('admin.footer.store_quick_link');

    Route::post('/update-quick-link', 'BackEnd\Footer\QuickLinkController@update')->name('admin.footer.update_quick_link');

    Route::post('/delete-quick-link/{id}', 'BackEnd\Footer\QuickLinkController@destroy')->name('admin.footer.delete_quick_link');
  });
  // footer route end


  // user management route start
  Route::prefix('/user-management')->middleware('permission:User Management')->group(function () {
    // registered user route
    Route::get('/registered-users', 'BackEnd\User\UserController@index')->name('admin.user_management.registered_users');
    Route::get('/create-users', 'BackEnd\User\UserController@create')->name('admin.user_management.create');
    Route::post('/store', 'BackEnd\User\UserController@store')->name('admin.user_management.store');
    Route::get('/change_account_type/{id}', 'BackEnd\User\UserController@change_account_type')->name('admin.user_management.change_account_type');
    Route::post('/account_type/{id}', 'BackEnd\User\UserController@update_account_type')->name('admin.user_management.update_account_type');
    Route::get('/assign_to_vendor/{id}', 'BackEnd\User\UserController@assign_to_vendor')->name('admin.user_management.assign_to_vendor');
    Route::post('/assign_to_vendor/{id}', 'BackEnd\User\UserController@update_assign_to_vendor')->name('admin.user_management.update_assign_to_vendor');

    Route::prefix('/user/{id}')->group(function () {
      Route::post('/update-email-status', 'BackEnd\User\UserController@updateEmailStatus')->name('admin.user_management.user.update_email_status');

      Route::post('/update-account-status', 'BackEnd\User\UserController@updateAccountStatus')->name('admin.user_management.user.update_account_status');

      Route::get('/details', 'BackEnd\User\UserController@show')->name('admin.user_management.user.details');

      Route::get('/change-password', 'BackEnd\User\UserController@changePassword')->name('admin.user_management.user.change_password');

      Route::post('/update-password', 'BackEnd\User\UserController@updatePassword')->name('admin.user_management.user.update_password');

      Route::post('/delete', 'BackEnd\User\UserController@destroy')->name('admin.user_management.user.delete');

      Route::get('/secret-login', 'BackEnd\User\UserController@secret_login')->name('admin.user_management.user.secret_login');
      
    });
      Route::get('/corporate-accounts', 'BackEnd\User\UserController@corporate_accounts')->name('admin.user_management.corporate_accounts');
      Route::get('/branches', 'BackEnd\User\UserController@branches')->name('admin.user_management.branches');
      Route::get('/managers', 'BackEnd\User\UserController@managers')->name('admin.user_management.managers');

    Route::post('/bulk-delete-user', 'BackEnd\User\UserController@bulkDestroy')->name('admin.user_management.bulk_delete_user');

    // subscriber route

    // Route::get('/subscribers', 'BackEnd\User\SubscriberController@index')->name('admin.user_management.subscribers');
    // Route::post('/subscriber/{id}/delete', 'BackEnd\User\SubscriberController@destroy')->name('admin.user_management.subscriber.delete');
    // Route::post('/bulk-delete-subscriber', 'BackEnd\User\SubscriberController@bulkDestroy')->name('admin.user_management.bulk_delete_subscriber');
    // Route::get('/mail-for-subscribers', 'BackEnd\User\SubscriberController@writeEmail')->name('admin.user_management.mail_for_subscribers');
    // Route::post('/subscribers/send-email', 'BackEnd\User\SubscriberController@prepareEmail')->name('admin.user_management.subscribers.send_email');

    // push notification route
    // Route::prefix('/push-notification')->group(function () {
    //   Route::get('/settings', 'BackEnd\User\PushNotificationController@settings')->name('admin.user_management.push_notification.settings');

    //   Route::post('/update-settings', 'BackEnd\User\PushNotificationController@updateSettings')->name('admin.user_management.push_notification.update_settings');

    //   Route::get('/notification-for-visitors', 'BackEnd\User\PushNotificationController@writeNotification')->name('admin.user_management.push_notification.notification_for_visitors');

    //   Route::post('/send', 'BackEnd\User\PushNotificationController@sendNotification')->name('admin.user_management.push_notification.send');
    // });

  });
  // user management route end

  // vendor management route start
  Route::prefix('/vendor-management')->middleware('permission:User Management')->group(function () {
    Route::get('/settings', 'BackEnd\VendorManagementController@settings')->name('admin.vendor_management.settings');
    Route::post('/settings/update', 'BackEnd\VendorManagementController@update_setting')->name('admin.vendor_management.setting.update');

    Route::get('/add-vendor', 'BackEnd\VendorManagementController@add')->name('admin.vendor_management.add_vendor');
    Route::post('/save-vendor', 'BackEnd\VendorManagementController@create')->name('admin.vendor_management.save-vendor');

    Route::get('/registered-vendors', 'BackEnd\VendorManagementController@index')->name('admin.vendor_management.registered_vendor');


	// code by AG start
	Route::get('/import-vendors', 'BackEnd\VendorManagementController@import_vendor_ag');

    // drivers management routes start
    Route::get('/drivers', 'BackEnd\Custom\DriverController@index')->name('admin.vendor_management.drivers');
    Route::get('/add-driver', 'BackEnd\Custom\DriverController@add')->name('admin.vendor_management.add_driver');
    Route::post('/save-driver', 'BackEnd\Custom\DriverController@create')->name('admin.vendor_management.save-driver');
    Route::post('/bulk-delete-driver', 'BackEnd\Custom\DriverController@bulkDestroy')->name('admin.vendor_management.bulk_delete_driver');
	
    Route::prefix('/driver/{id}')->group(function () {

        //Route::post('/update-account-status', 'BackEnd\VendorManagementController@updateAccountStatus')->name('admin.vendor_management.vendor.update_account_status');
  
        //Route::post('/update-email-status', 'BackEnd\VendorManagementController@updateEmailStatus')->name('admin.vendor_management.vendor.update_email_status');
  
        Route::get('/details', 'BackEnd\Custom\DriverController@show')->name('admin.vendor_management.driver_details');
  
        Route::get('/edit', 'BackEnd\Custom\DriverController@edit')->name('admin.edit_management.driver_edit');
        
        Route::post('/update', 'BackEnd\Custom\DriverController@update')->name('admin.vendor_management.driver.update_driver');
  
        //Route::post('/update/vendor/balance', 'BackEnd\VendorManagementController@update_vendor_balance')->name('admin.vendor_management.update_vendor_balance');
  
        Route::get('/change-password', 'BackEnd\Custom\DriverController@changePassword')->name('admin.vendor_management.driver.change_password');
  
        Route::post('/update-password', 'BackEnd\Custom\DriverController@updatePassword')->name('admin.vendor_management.driver.update_password');
  
        Route::post('/delete', 'BackEnd\Custom\DriverController@destroy')->name('admin.vendor_management.driver.delete');

        //Route::get('/secret-login', 'BackEnd\VendorManagementController@secret_login')->name('admin.vendor_management.vendor.secret_login');
        
    });
    // drivers management route end

    Route::get('voximplant/add_log', 'Custom\VoximplantController@add_voximplant_calllog')->name('add_voximplant_calllog.ajax');
    
    Route::get('/add-lead', 'BackEnd\Custom\LeadController@add')->name('admin.vendor_management.add_lead');
    Route::post('/save-lead', 'BackEnd\Custom\LeadController@create')->name('admin.vendor_management.save-lead');
    Route::post('/add-lead-remark', 'BackEnd\Custom\LeadController@add_remark')->name('admin.vendor_management.add-lead-remark');

    Route::get('/leads', 'BackEnd\Custom\LeadController@index')->name('admin.vendor_management.leads');
    Route::post('/bulk-delete-lead', 'BackEnd\Custom\LeadController@bulkDestroy')->name('admin.vendor_management.bulk_delete_lead');
	
    Route::prefix('/lead/{id}')->group(function () {

        //Route::post('/update-account-status', 'BackEnd\VendorManagementController@updateAccountStatus')->name('admin.vendor_management.vendor.update_account_status');
  
        //Route::post('/update-email-status', 'BackEnd\VendorManagementController@updateEmailStatus')->name('admin.vendor_management.vendor.update_email_status');
  
        Route::get('/details', 'BackEnd\Custom\LeadController@show')->name('admin.vendor_management.lead_details');
  
        Route::get('/edit', 'BackEnd\Custom\LeadController@edit')->name('admin.edit_management.lead_edit');
        
        Route::get('/convert-to-vendor', 'BackEnd\Custom\LeadController@convert_to_vendor')->name('admin.vendor_management.lead_convert_to_vendor');

        Route::post('/update', 'BackEnd\Custom\LeadController@update')->name('admin.vendor_management.lead.update_lead');
  
        //Route::post('/update/vendor/balance', 'BackEnd\VendorManagementController@update_vendor_balance')->name('admin.vendor_management.update_vendor_balance');
  
        //Route::get('/change-password', 'BackEnd\VendorManagementController@changePassword')->name('admin.vendor_management.vendor.change_password');
  
        //Route::post('/update-password', 'BackEnd\VendorManagementController@updatePassword')->name('admin.vendor_management.vendor.update_password');
  
        Route::post('/delete', 'BackEnd\Custom\LeadController@destroy')->name('admin.vendor_management.lead.delete');

        //Route::get('/secret-login', 'BackEnd\VendorManagementController@secret_login')->name('admin.vendor_management.vendor.secret_login');

        Route::get('/calling', 'BackEnd\Custom\LeadController@calling')->name('admin.vendor_management.lead.calling');
        Route::get('/chat', 'BackEnd\Custom\LeadController@sms_communication')->name('admin.vendor_management.lead.chat');
    });

    Route::get('vendorsms/sms_history', 'Custom\SmsController@history_sms_ajax')->name('vendorchat.history');
    Route::post('vendorsms/sms_send', 'Custom\SmsController@send_sms_ajax')->name('vendorchat.send');
    // code by AG end
	
    Route::prefix('/vendor/{id}')->group(function () {

      Route::post('/update-account-status', 'BackEnd\VendorManagementController@updateAccountStatus')->name('admin.vendor_management.vendor.update_account_status');

      Route::post('/update-email-status', 'BackEnd\VendorManagementController@updateEmailStatus')->name('admin.vendor_management.vendor.update_email_status');

      Route::get('/details', 'BackEnd\VendorManagementController@show')->name('admin.vendor_management.vendor_details');

      Route::get('/edit', 'BackEnd\VendorManagementController@edit')->name('admin.edit_management.vendor_edit');

      Route::post('/update', 'BackEnd\VendorManagementController@update')->name('admin.vendor_management.vendor.update_vendor');

      Route::post('/update/vendor/balance', 'BackEnd\VendorManagementController@update_vendor_balance')->name('admin.vendor_management.update_vendor_balance');

      Route::get('/change-password', 'BackEnd\VendorManagementController@changePassword')->name('admin.vendor_management.vendor.change_password');

      Route::post('/update-password', 'BackEnd\VendorManagementController@updatePassword')->name('admin.vendor_management.vendor.update_password');

      Route::post('/delete', 'BackEnd\VendorManagementController@destroy')->name('admin.vendor_management.vendor.delete');

      Route::get('/secret-login', 'BackEnd\VendorManagementController@secret_login')->name('admin.vendor_management.vendor.secret_login');
        
      // code by AG start
      Route::get('/calling', 'BackEnd\VendorManagementController@calling')->name('admin.vendor_management.vendor.calling');
      Route::get('/chat', 'BackEnd\VendorManagementController@sms_communication')->name('admin.vendor_management.vendor.chat');
        
        
      // code by AG start
      
      
    
    });

    Route::post('/bulk-delete-vendor', 'BackEnd\VendorManagementController@bulkDestroy')->name('admin.vendor_management.bulk_delete_vendor');
      Route::get('/vendor-plans', 'BackEnd\VendorManagementController@vendor_plans')->name('admin.vendor_management.vendor_plans');
      Route::get('/vendor-plans/{id}/detail', 'BackEnd\VendorManagementController@vendor_plan_detail')->name('admin.vendor_management.vendor_plan_detail');
      Route::get('/plans/assign', 'BackEnd\VendorManagementController@plan_assign')->name('admin.vendor_management.plan.assign');
      Route::post('/plans/assign', 'BackEnd\VendorManagementController@assign_store')->name('admin.vendor_management.plan.assign.store');
      Route::get('/get_vendors_ajax', 'BackEnd\VendorManagementController@get_vendors_ajax')->name('admin.vendor_management.plan.get_vendors_ajax');
  });
  // vendor management route start
  Route::get('dialer', 'Custom\VoximplantController@admin_dialer')->name('admin_dialer.call');

  // Route::prefix('withdraw')->group(function () {
  //   Route::get('/payment-methods', 'BackEnd\WithdrawPaymentMethodController@index')->name('admin.withdraw.payment_method');
  //   Route::post('/payment-methods/store', 'BackEnd\WithdrawPaymentMethodController@store')->name('admin.withdraw_payment_method.store');
  //   Route::put('/payment-methods/update', 'BackEnd\WithdrawPaymentMethodController@update')->name('admin.withdraw_payment_method.update');
  //   Route::post('/payment-methods/delete/{id}', 'BackEnd\WithdrawPaymentMethodController@destroy')->name('admin.withdraw_payment_method.delete');

  //   Route::get('/payment-method/input', 'BackEnd\WithdrawPaymentMethodInputController@index')->name('admin.withdraw_payment_method.mange_input');
  //   Route::post('/payment-method/input-store', 'BackEnd\WithdrawPaymentMethodInputController@store')->name('admin.withdraw_payment_method.store_input');
  //   Route::get('/payment-method/input-edit/{id}', 'BackEnd\WithdrawPaymentMethodInputController@edit')->name('admin.withdraw_payment_method.edit_input');
  //   Route::post('/payment-method/input-update', 'BackEnd\WithdrawPaymentMethodInputController@update')->name('admin.withdraw_payment_method.update_input');
  //   Route::post('/payment-method/order-update', 'BackEnd\WithdrawPaymentMethodInputController@order_update')->name('admin.withdraw_payment_method.order_update');
  //   Route::get('/payment-method/input-option/{id}', 'BackEnd\WithdrawPaymentMethodInputController@get_options')->name('admin.withdraw_payment_method.options');
  //   Route::post('/payment-method/input-delete', 'BackEnd\WithdrawPaymentMethodInputController@delete')->name('admin.withdraw_payment_method.options_delete');

  //   Route::get('/withdraw-request', 'BackEnd\WithdrawController@index')->name('admin.withdraw.withdraw_request');
  //   Route::post('/withdraw-request/delete', 'BackEnd\WithdrawController@delete')->name('admin.witdraw.delete_withdraw');
  //   Route::get('/withdraw-request/approve/{id}', 'BackEnd\WithdrawController@approve')->name('admin.witdraw.approve_withdraw');


  //   Route::get('/withdraw-request/decline/{id}', 'BackEnd\WithdrawController@decline')->name('admin.witdraw.decline_withdraw');
  // });

  Route::get('/transcation', 'BackEnd\AdminController@transcation')->name('admin.transcation');
  Route::post('/transcation/delete', 'BackEnd\AdminController@destroy')->name('admin.transcation.delete');
  Route::post('/transcation/bulk-delete', 'BackEnd\AdminController@bulk_destroy')->name('admin.transcation.bulk_delete');

  #====support tickets ============
  Route::get('ticket/setting', 'BackEnd\SupportTicketController@setting')->name('admin.support_ticket.setting');
  Route::post('ticket/setting/update', 'BackEnd\SupportTicketController@update_setting')->name('admin.support_ticket.update_setting');
  Route::get('support/tickets', 'BackEnd\SupportTicketController@index')->name('admin.support_tickets');
  Route::get('support/message/{id}', 'BackEnd\SupportTicketController@message')->name('admin.support_tickets.message');
  Route::post(
    'support-ticket/zip-upload',
    'BackEnd\SupportTicketController@zip_file_upload'
  )->name('admin.support_ticket.zip_file.upload');
  Route::post(
    'support-ticket/reply/{id}',
    'BackEnd\SupportTicketController@ticketreply'
  )->name('admin.support_ticket.reply');
  Route::post(
    'support-ticket/closed/{id}',
    'BackEnd\SupportTicketController@ticket_closed'
  )->name('admin.support_ticket.close');
  Route::post('support-ticket/assign-stuff/{id}', 'BackEnd\SupportTicketController@assign_stuff')->name('assign_stuff.supoort.ticket');

  Route::post('support-ticket/delete/{id}', 'BackEnd\SupportTicketController@delete')->name('admin.support_tickets.delete');

  Route::post('support-ticket/bulk-delete/', 'BackEnd\SupportTicketController@bulk_delete')->name('admin.support_tickets.bulk_delete');




  // upload image in summernote route
  Route::prefix('/summernote')->group(function () {
    Route::post('/upload-image', 'BackEnd\SummernoteController@upload');

    Route::post('/remove-image', 'BackEnd\SummernoteController@remove');
  });
  
  Route::get('/invoice', function(){
      return view('backend.invoice-embed');
  });
  
});
