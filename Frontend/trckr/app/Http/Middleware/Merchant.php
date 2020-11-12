<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;

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
            $admin = $session->adminDetails;
            $admin->token = $session->token;
            $admin->merchant = $session->merchant;
            Config::set('gbl_profile', $admin);
            return $next($request);
        }
        return redirect()->route('login');
    }
}
