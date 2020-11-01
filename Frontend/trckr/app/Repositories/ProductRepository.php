<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Log;
use Config;

class ProductRepository
{

    private $api;

    public function __construct()
    {
        $this->api = Config::get('trckr.backend_url') . "merchant/product/";
    }

    public function getAll()
    {
        try {
            return Http::withToken(Config::get('gbl_profile')->token)->get($this->api , []);
        } catch(\Exception $e) {
            return false;
        }
    }

    public function create($request)
    {
        try {
            return Http::withToken(Config::get('gbl_profile')->token)->post($this->api, []);
        } catch(\Exception $e) {
            return false;
        }
    }

    public function update($request)
    {
        try {
            return Http::withToken(Config::get('gbl_profile')->token)->put($this->api, []);
        } catch(\Exception $e) {
            return false;
        }
    }

    public function delete($request)
    {
        try {
            return Http::withToken(Config::get('gbl_profile')->token)->delete($this->api, []);
        } catch(\Exception $e) {
            return false;
        }
    }
}
