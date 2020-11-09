<?php

namespace App\Http\Controllers;
Use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Services\MerchantService;

class AuthController extends Controller
{
    private $merchantService;

    public function __construct(MerchantService $merchantService)
    {
        $this->merchantService = $merchantService;
    }

    public function index()
    {
        $msg = Session::get('login_msg');
        Session::put('login_msg', '');
        Session::save();
        return view('concrete.auth.login', ['expiredMsg' => $msg]);
    }

    public function login_get()
    {
        $msg = Session::get('login_msg');
        Session::put('login_msg', '');
        Session::save();
        return view ('concrete.auth.login', ['expiredMsg' => $msg]);
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

        $response = $this->merchantService->login([
            "username" => $email,
            "password" => $password
        ]);

        // SESSION STARTS
        $request->session()->put('session_merchant', $response);

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

    public function forgot()
    {
        return view('concrete.auth.passwords.forgot');
    }

    public function forgot_post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|min:4'
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = ['email' => $request->input('email')];

        $this->merchantService->forgotPassword($data);
        echo "email sent";

    }
}
