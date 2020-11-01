<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Log;
use Config;

class TaskRepository
{

    private $api;

    public function __construct()
    {
        $this->api = Config::get('trckr.backend_url') . "merchant/task/";
    }

    public function create()
    {
        try {
            return Http::withToken(Config::get('gbl_profile')->token)->post($this->api, []);
        } catch(\Exception $e) {
            return false;
        }
    }

    public function update()
    {
        try {
            return Http::withToken(Config::get('gbl_profile')->token)->put($this->api, []);
        } catch(\Exception $e) {
            return false;
        }
    }
}
