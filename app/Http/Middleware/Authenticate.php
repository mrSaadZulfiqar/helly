<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Route;

class Authenticate extends Middleware
{
  /**
   * Get the path the user should be redirected to when they are not authenticated.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return string|null
   */
  protected function redirectTo($request)
  {
    if (!$request->expectsJson()) {
      if (Route::is('admin.*')) {
        return route('admin.login');
      }

      if (Route::is('user.*')) {
        return route('user.login');
      }
      if (Route::is('vendor.*')) {
        return route('vendor.login');
      }

      // code by AG start
      if (Route::is('driver.*')) {
        return route('driver.login');
      }
      // code by AG end
    }
  }
}
