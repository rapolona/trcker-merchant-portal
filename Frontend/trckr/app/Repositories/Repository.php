<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

Class Repository
{
    public function validateResponse($response)
    {

        if ($response->status() !== 200)
        {
            $message = json_decode($response->body());
            $message = "Session Expired. Please login again. {$message->message}";
            Session::put('login_msg', $message);
            Session::save();
            Redirect::to(url('/'))->send();
        }

        return $response;
    }
}
