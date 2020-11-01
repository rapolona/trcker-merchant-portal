<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Log;
use Config;

class AdminRepository
{

    private $api;

    public function __construct()
    {
        $this->api = Config::get('trckr.backend_url') . "admin/";
    }

    public function create($request)
    {
        try {
            return Http::withToken(Config::get('gbl_profile')->token)->post($this->api . 'create', []);
        } catch(\Exception $e) {
            return false;
        }
    }
}
