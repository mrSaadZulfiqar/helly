<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
  /**
   * The URIs that should be excluded from CSRF verification.
   *
   * @var array
   */
  protected $except = [
    '/shop/purchase-product/razorpay/notify',
    '/shop/purchase-product/flutterwave/notify',
    '/shop/purchase-product/paytm/notify',
    '/equipment/make-booking/razorpay/notify',
    '/equipment/make-booking/flutterwave/notify',
    '/equipment/make-booking/paytm/notify',
    '/admin/menu-builder/update-menus',
    '/push-notification/store-endpoint',
  ];
}
