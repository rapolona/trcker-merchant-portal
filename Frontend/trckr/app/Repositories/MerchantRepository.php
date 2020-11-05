<?php
namespace App\Repositories;

use Config;

class MerchantRepository extends Repository
{

    private $api;

    public function __construct()
    {
        $this->api = Config::get('trckr.backend_url') . "merchant/";
    }

    public function get($request)
    {
        try {
            return $this->trackerApi('get', $this->api . 'profile', []);
        } catch(\Exception $e) {
            return false;
        }
    }

    public function update($request)
    {
        try {
            return $this->trackerApi('put', $this->api . 'profile', []);
        } catch(\Exception $e) {
            return false;
        }
    }

    public function campaign($request)
    {
        try {
            return $this->trackerApi('get', $this->api . 'campaign', []);
        } catch(\Exception $e) {
            return false;
        }
    }

    public function login($data)
    {
        try {
            return $this->trackerApi('post', $this->api . 'auth', $data);
        } catch(\Exception $e) {
            return false;
        }
    }

    public function logout($request)
    {
        try {
            return $this->trackerApi('post', $this->api . 'logout', []);
        } catch(\Exception $e) {
            return false;
        }
    }
}
