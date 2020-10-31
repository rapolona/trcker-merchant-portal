<?php

namespace App\Http\Controllers;
Use App\User;

use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Config, Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function index()
    {
        return view('concrete.auth.login');
    }

    public function login_get()
    {
        return view ('concrete.auth.login');
    }

    public function login_post(Request $request) {
        $email = $request->input('email');
        $password = $request->input('password');

        $validator = Validator::make($request->all(), [
            'email' => 'required|min:4',
            'password' => 'required'
        ]);

        if ($validator->fails())
            return redirect('/')
                ->withErrors($validator)
                ->withInput();

        //api call for login
        //http://localhost:6001/merchant/auth
        $api_endpoint = Config::get('trckr.backend_url') . "merchant/auth";

        $response = Http::post($api_endpoint,  [
          "username" => $email,
          "password" => $password
        ]);

        if ($response->status() !== 200)
        {
            //$validator->addFailure('email', 'Invalid user credentials.', 'email');
            $responseBody = json_decode($response->body());
            $validator->getMessageBag()->add('email', "Invalid User Credentials. {$responseBody->message}");

            return redirect('/')
                ->withErrors($validator)
                ->withInput();
        }

        $response = json_decode($response);

        //setting up session variables
        $request->session()->put('session_merchant', $response);

        //redirect to home
        return redirect('/dashboard');
    }

    public function logout(Request $request) {
        $api_endpoint = Config::get('trckr.backend_url') . "merchant/logout";

        $session = $request->session()->get('session_merchant');
        $token = ( ! empty($session->token)) ? $session->token : "";

        $response = Http::withToken($token)->post($api_endpoint);

        if ($response->status() !== 200)
        {
            //var_dump($response->status());exit;
            if ($response->status() === 403) {
                $validator = Validator::make($request->all(), []);
                $validator->getMessageBag()->add('email', "Session Expired. Please login again. {$response->body()}");

                return redirect('/')
                    ->withErrors($validator)
                    ->withInput();
            }

            if ($response->status() === 500) {
                $handler = json_decode($response->body());

                if ($handler->message->name == "JsonWebTokenError")

                $validator = Validator::make($request->all(), []);
                $validator->getMessageBag()->add('email', "Session Expired. Please login again. {$response->body()}");

                return redirect('/')
                    ->withErrors($validator)
                    ->withInput();
            }

            //general handling
            return redirect('/dashboard');
        }

        $request->session()->flush('session_merchant');

        $profile = json_decode($response);

        return redirect ('/login');
    }
}
