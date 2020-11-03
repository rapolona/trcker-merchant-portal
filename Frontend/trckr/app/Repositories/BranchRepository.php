<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Log;
use Config;

class BranchRepository extends Repository
{

    private $api;

    public function __construct()
    {
        $this->api = Config::get('trckr.backend_url') . "merchant/";
    }

    public function getAll()
    {
        try {
            return $this->validateResponse(Http::withToken($this->token())->get($this->api . 'branches' , []));
        } catch(\Exception $e) {
            return false;
        }
    }

    public function get($id)
    {
        try {
            return $this->validateResponse(Http::withToken($this->token())->get($this->api . 'branch/' . $id , []));
        } catch(\Exception $e) {
            return false;
        }
    }

    public function create($data)
    {
        try {
            return $this->validateResponse(Http::withToken($this->token())->post($this->api. 'branch/' , $data));
        } catch(\Exception $e) {
            return false;
        }
    }

    public function update($data)
    {
        try {
            return $this->validateResponse(Http::withToken($this->token())->put($this->api. 'branch/' , $data));
        } catch(\Exception $e) {
            return false;
        }
    }

    public function delete($data)
    {
        try {
            return $this->validateResponse(Http::withToken($this->token())->delete($this->api. 'branch/' , $data));
        } catch(\Exception $e) {
            return false;
        }
    }
}
