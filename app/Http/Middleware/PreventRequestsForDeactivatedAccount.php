<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PreventRequestsForDeactivatedAccount
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle(Request $request, Closure $next)
  {
    $userInfo = Auth::guard('web')->user();

    $secret_login = Session::get('secret_login');
    if ($secret_login != true) {
      if ($userInfo->status == 0) {
        Auth::guard('web')->logout();
        Session::flash('error', 'Sorry, your account has been deactivated!');

        return redirect()->route('user.login');
      }
    }
    return $next($request);
  }
}
