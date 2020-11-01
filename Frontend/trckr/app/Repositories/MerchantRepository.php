<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Log;
use Config;

class MerchantRepository
{

    private $api;

    public function __construct()
    {
        $this->api = Config::get('trckr.backend_url') . "merchant/";
    }

    public function get($request)
    {
        try {
            return Http::withToken(Config::get('gbl_profile')->token)->get($this->api . 'profile', []);
        } catch(\Exception $e) {
            return false;
        }
    }

    public function update($request)
    {
        try {
            return Http::withToken(Config::get('gbl_profile')->token)->put($this->api . 'profile', []);
        } catch(\Exception $e) {
            return false;
        }
    }

    public function campaign($request)
    {
        try {
            return Http::withToken(Config::get('gbl_profile')->token)->get($this->api .'campaign', []);
        } catch(\Exception $e) {
            return false;
        }
    }

    public function login($request)
    {
        try {
            return Http::withToken(Config::get('gbl_profile')->token)->post($this->api .'auth', []);
        } catch(\Exception $e) {
            return false;
        }
    }

    public function logout($request)
    {
        try {
            return Http::withToken(Config::get('gbl_profile')->token)->post($this->api .'logout', []);
        } catch(\Exception $e) {
            return false;
        }
    }
}
