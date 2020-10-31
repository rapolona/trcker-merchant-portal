<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class Merchant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $session = $request->session()->get('session_merchant');

        if( ! empty($session->token)){
            Config::set('gbl_profile', $session->adminDetails);
            return $next($request);
        }
        return redirect()->route('login');
    }
}
