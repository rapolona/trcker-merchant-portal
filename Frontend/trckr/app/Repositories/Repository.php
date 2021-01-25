<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Config;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;

Class Repository
{
    public function sessionExpired($e = null)
    {
        if($e){
            Log::info('error');
            Log::info($e->getMessage());
        }
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

        Log::info('RESULT HTTP CODE :: ' . $response->getStatusCode());

        $validationHttpCodes = [500, 422, 413, 404];
        if (in_array($response->getStatusCode(), $validationHttpCodes))
        {
            $message = json_decode($response->getBody());
            Log::info('WARNING :: ' . json_encode($message->message));
            $message = "Error. {$message->message}";
            $msg =[
                'type' => 'warning',
                'message' => $message
            ];
            Redirect::back()->with('formMessage', $msg)->send();
        }

        Log::info('----------- Log END --------');

        return $response;
    }

    public function token()
    {
        return Config::get('gbl_profile')->token;
    }

    public function logUserDetail()
    {
        $ip = request()->ip();
        $log = [
            'IP' => $ip,
        ];
        if(isset(Config::get('gbl_profile')->merchant)){
            $details = Config::get('gbl_profile')->merchant;
            $merchant = [
                'merchant_id' => $details->merchant_id,
                'name' => $details->name,
                'email' => $details->email_address
            ];
            $log['merchant'] = $merchant;
        }

        Log::info("USER DETAILS");
        Log::info($log);
    }

    public function trackerApi($method, $url, $data, $useToken=true, $validate=true)
    {
        Log::info('----------- Log start --------');

        $this->logUserDetail();

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

        if($validate){
            $this->validateResponse($response);
        }

        $content = $response->getBody();
        $code = $response->getStatusCode();
        if($code > 300){
            $content->error = $code;
        }
        
        //print_r(json_decode($response->getStatusCode())); exit();
        return json_decode($content);
    }
}
