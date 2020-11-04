<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Log;
use Config;

class TaskRepository extends Repository
{

    private $api;

    public function __construct()
    {
        $this->api = Config::get('trckr.backend_url') . "merchant/task/";
    }

    public function create($data)
    {
        try {
            return $this->validateResponse(Http::withToken($this->token())->post($this->api, $data));
        } catch(\Exception $e) {
            return false;
        }
    }

    public function update($data)
    {
        try {
            return $this->validateResponse(Http::withToken($this->token())->put($this->api, $data));
        } catch(\Exception $e) {
            return false;
        }
    }

    public function get($data)
    {
        try {
            return $this->validateResponse(Http::withToken($this->token())->get($this->api , $data));
        } catch(\Exception $e) {
            return false;
        }
    }

    public function getAll()
    {
        try {
            return $this->validateResponse(Http::withToken($this->token())->get($this->api , []));
        } catch(\Exception $e) {
            return false;
        }
    }

    public function getTaskByCurrentMerchant()
    {
        try {
            $data = ['task_type'=>'Merchandising'];
            return $this->validateResponse(Http::withToken($this->token())->get($this->api , $data));
        } catch(\Exception $e) {
            return false;
        }
    }

    public function getTaskActionClassification()
    {
        try {
            return $this->validateResponse(Http::withToken($this->token())->get( Config::get('trckr.backend_url') . 'api/task_action_classification' , []));
        } catch(\Exception $e) {
            return false;
        }
    }


}
