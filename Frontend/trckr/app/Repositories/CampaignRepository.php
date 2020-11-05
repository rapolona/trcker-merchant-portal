<?php
namespace App\Repositories;

use Config;

class CampaignRepository extends Repository
{

    private $api;

    public function __construct()
    {
        $this->api = Config::get('trckr.backend_url') . "merchant/campaign/";
    }

    public function create($data)
    {
        try {
            return $this->trackerApi('post', $this->api . 'create', $data);
        } catch(\Exception $e) {
            return false;
        }
    }

    public function update($data)
    {
        try {
            return $this->trackerApi('put', $this->api . 'update', $data);
        } catch(\Exception $e) {
            return false;
        }
    }

    public function get($id)
    {
        try {
            return $this->trackerApi('get', $this->api . $id, []);
        } catch(\Exception $e) {
            return false;
        }
    }

    public function getAll()
    {
        try {
            return $this->trackerApi('get', $this->api, []);
        } catch(\Exception $e) {
            return false;
        }
    }
}
