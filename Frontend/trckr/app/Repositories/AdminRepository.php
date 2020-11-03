<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Log;
use Config;

class AdminRepository extends Repository
{

    private $api;

    public function __construct()
    {
        $this->api = Config::get('trckr.backend_url') . "admin/";
    }

    public function create($data)
    {
        try {
            return $this->validateResponse(Http::withToken($this->token())->post($this->api . 'create', $data));
        } catch(\Exception $e) {
            return false;
        }
    }
}
