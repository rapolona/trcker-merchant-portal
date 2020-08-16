<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

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
      
      //$login_url = 
      $login = Http::post('https://run.mocky.io/v3/41ca8b84-277c-491a-85eb-5f9783a35af2',  [
        "username" => $email,
        "password" => $password
      ]);
      
      if ($login->status() !== 200)
      {
        //redirect to login
      }

      //store user session

      //redirect to home
      return redirect('/main');


    }
}