<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Log;
use Config;

class CampaignRepository
{

    private $api;

    public function __construct()
    {
        $this->api = Config::get('trckr.backend_url') . "merchant/campaign/";
    }

    public function create($request)
    {
        try {
            return Http::withToken(Config::get('gbl_profile')->token)->post($this->api . 'create', []);
        } catch(\Exception $e) {
            return false;
        }
    }

    public function update($request)
    {
        try {
            return Http::withToken(Config::get('gbl_profile')->token)->put($this->api . 'update', []);
        } catch(\Exception $e) {
            return false;
        }
    }
}
