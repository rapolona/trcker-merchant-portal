<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Log;
use Config;

class ProductRepository extends Repository
{

    private $api;

    public function __construct()
    {
        $this->api = Config::get('trckr.backend_url') . "merchant/product";
    }

    public function getAll()
    {
        //try {
            return $this->validateResponse(Http::withToken(Config::get('gbl_profile')->token)->get($this->api . 's' , []));
       // } catch(\Exception $e) {
        //    return false;
        //}
    }

    public function create($data)
    {
        try {
            return $this->validateResponse(Http::withToken(Config::get('gbl_profile')->token)->post($this->api, $data));
        } catch(\Exception $e) {
            return false;
        }
    }

    public function update($data)
    {
        try {
            return $this->validateResponse(Http::withToken(Config::get('gbl_profile')->token)->put($this->api, $data));
        } catch(\Exception $e) {
            return false;
        }
    }

    public function delete($data)
    {
        try {
            return $this->validateResponse(Http::withToken(Config::get('gbl_profile')->token)->delete($this->api, $data));
        } catch(\Exception $e) {
            return false;
        }
    }
}
