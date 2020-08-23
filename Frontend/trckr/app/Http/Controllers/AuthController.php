<?php

namespace App\Http\Controllers;
Use App\User;

use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Config, Session;

class AuthController extends Controller
{
 
    public function index()
    {
        return view('auth.login');
    }  
 
    public function login_get()
    {      
        return view ('auth.login');
    }

    public function login_post(Request $request) {
        $email = $request->input('email');
        $password = $request->input('password');

        //hardcode credentials
        //$email = "cokeadmin";
        //$password = "password";

        //api call for login
        //http://localhost:6001/merchant/auth       
        $api_endpoint = Config::get('trckr.backend_url') . "merchant/auth";

        $response = Http::post($api_endpoint,  [
          "username" => $email,
          "password" => $password
        ]);

        if ($response->status() !== 200)
        {
            return redirect('/');
        }
        
        $response = json_decode($response);

        //setting up session variables
        $request->session()->put('session_merchant', $response);

        //redirect to home
        return redirect('/main');
    }
}