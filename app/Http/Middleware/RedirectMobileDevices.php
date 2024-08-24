<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectMobileDevices
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
        // Check if the request is coming from a mobile device
        if ($this->isMobileDevice($request)) {
            // Redirect to m.catdump.com if it's a mobile device
            return redirect('https://m.catdump.com/');
        }

        // If not a mobile device, continue with the request
        return $next($request);
    }

    /**
     * Check if the request is coming from a mobile device.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    private function isMobileDevice($request)
    {
        // Use a regular expression to detect common mobile device user agents
        $mobileAgents = "/(android|iphone|ipad|ipod|blackberry|windows phone|opera mini|opera mobi|iemobile|mobile)/i";

        return preg_match($mobileAgents, $request->header('User-Agent'));
    }
}
