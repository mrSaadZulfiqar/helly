<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vendor;

class RedirectIfAuthenticated
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @param  string|null  ...$guards
   * @return mixed
   */
  public function handle(Request $request, Closure $next, ...$guards)
  {
    $guards = empty($guards) ? [null] : $guards;

    // Get user memebrship plans and feature

    foreach ($guards as $guard) {
      if ($guard == 'admin' && Auth::guard($guard)->check()) {
        return redirect()->route('admin.dashboard');
      }

      if ($guard == 'vendor' && Auth::guard($guard)->check()) {
        return redirect()->route('vendor.dashboard');
      }

      // code by AG start
      if ($guard == 'driver' && Auth::guard($guard)->check()) {
        return redirect()->route('driver.dashboard');
      }
      // code by AG end

      if ($guard == 'web' && Auth::guard($guard)->check()) {
        return redirect()->route('user.dashboard');
      }
    }

    return $next($request);
  }
}
