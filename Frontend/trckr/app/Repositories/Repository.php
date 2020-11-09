<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Config;
use GuzzleHttp\Client;

Class Repository
{
    public function sessionExpired()
    {
        Log::info('SESSION EXPIRED');
        $message = "Session Expired. Please login again";
        Session::put('login_msg', $message);
        Session::save();
        Redirect::to(url('/'))->send();
    }
    public function validateResponse($response)
    {
        $expiredHttpCodes = [403, 401];
        if (in_array($response->getStatusCode(), $expiredHttpCodes))
        {
            $message = json_decode($response->getBody());
            Log::info('ERROR :: ' . json_encode(json_decode($message)));
            $message = "Session Expired. Please login again. {$message->message}";
            $msg =[
                'type' => 'danger',
                'message' => $message
            ];
            Session::put('formMessage', $msg);
            Session::save();
            Redirect::to(url('/'))->send();
        }

       /* $validationHttpCodes = [500, 422, 413];
        if (in_array($response->getStatusCode(), $validationHttpCodes))
        {
            $message = json_decode($response->getBody());
            Log::info('WARNING :: ' . json_encode($message->message));
            $message = "Error. {$message->message}";
            $msg =[
                'type' => 'warning',
                'message' => $message
            ];
            Session::put('formMessage', $msg);
            Session::save();
            Redirect::back()->send();
        }*/

        Log::info('RESULT HTTP CODE :: ' . $response->getStatusCode());

        return $response;
    }

    public function token()
    {
        return Config::get('gbl_profile')->token;
    }

    public function trackerApi($method, $url, $data, $useToken=true)
    {
        $logMethod = strtoupper($method);
        Log::info("{$logMethod} {$url} ");
        Log::info('DATA :: ' .json_encode($data));
        $client = new Client();
        $headers = [
            'Accept' => 'application/json',
        ];

        if($useToken){
            $credentials = $this->token();
            $headers['Authorization'] = 'Bearer ' . $credentials;
        }

        $response = $client->$method($url, [
            'decode_content' => false,
            'exceptions' => false,
            'http_errors' => false,
            'headers' => $headers,
            'form_params' => $data
        ]);

        $this->validateResponse($response);

        //print_r(json_decode($response->getStatusCode())); exit();
        return json_decode($response->getBody());
    }
}
