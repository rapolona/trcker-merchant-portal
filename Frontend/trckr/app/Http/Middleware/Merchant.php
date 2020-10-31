<?php

namespace App\Http\Middleware;

use Closure;

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
            return $next($request);
        }
        return redirect()->route('login');
    }
}
