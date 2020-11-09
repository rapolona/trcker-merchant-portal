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
        $msg = Session::get('formMessage');
        Session::put('formMessage', '');
        Session::save();
        return view('concrete.auth.login', ['expiredMsg' => $msg]);
    }

    public function login_get()
    {
        $msg = Session::get('formMessage');
        Session::put('formMessage', '');
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
        $this->merchantService->logout();
        $request->session()->flush('session_merchant');
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

        $data = ['email_address' => $request->input('email')];

        $this->merchantService->forgotPassword($data);

        return "email sent"; //view('concrete.auth.passwords.forgot');

    }
}
