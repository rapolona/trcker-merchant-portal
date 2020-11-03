<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Config;

Class Repository
{
    public function validateResponse($response)
    {
        $expiredHttpCodes = ['500', '403'];
        if (in_array($response->status(), $expiredHttpCodes))
        {
            $message = json_decode($response->body());
            $message = "Session Expired. Please login again. {$message->message}";
            Session::put('login_msg', $message);
            Session::save();
            Redirect::to(url('/'))->send();
        }

        return $response;
    }

    public function token()
    {
        return Config::get('gbl_profile')->token;
    }
}
