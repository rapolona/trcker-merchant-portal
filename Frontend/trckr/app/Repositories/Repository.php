<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Config;
use GuzzleHttp\Client;

Class Repository
{
    public function validateResponse($response)
    {
        Log::info('RESULT HTTP CODE :: ' . $response->getStatusCode());
        $expiredHttpCodes = ['500', '403'];
        if (in_array($response->getStatusCode(), $expiredHttpCodes))
        {
            $message = json_decode($response->getBody());
            Log::info('RESULT MESSAGE :: ' . json_encode(json_decode($message)));
            $message = "Session Expired. Please login again. {$message->message}";
            Session::put('login_msg', $message);
            Session::save();
            Redirect::to(url('/'))->send();
        }

        // ADD HERE REDIRECT BACK IF VALIDATION ERROR

        return $response;
    }

    public function token()
    {
        return Config::get('gbl_profile')->token;
    }

    public function trackerApi($method, $url, $data)
    {
        $logMethod = strtoupper($method);
        Log::info("{$logMethod} {$url} ");
        Log::info('DATA :: ' .json_encode($data));
        $client = new Client();
        $credentials = $this->token();
        $response = $client->$method($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $credentials,
                'Accept'        => 'application/json',
            ],
            'form_params' => $data
        ]);

        $this->validateResponse($response);
        return json_decode($response->getBody());
    }
}
