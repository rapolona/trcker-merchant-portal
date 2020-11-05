<?php
namespace App\Repositories;

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
            return $this->trackerApi('get', $this->api . 'branches', []);
        } catch(\Exception $e) {
            return false;
        }
    }

    public function get($id)
    {
        try {
            return $this->trackerApi('get', $this->api . 'branch/find_one/' .$id, []);
        } catch(\Exception $e) {
            return false;
        }
    }

    public function create($data)
    {
        try {
            return $this->trackerApi('post', $this->api . 'branch/', $data);
        } catch(\Exception $e) {
            return false;
        }
    }

    public function update($data)
    {
        try {
            return $this->trackerApi('put', $this->api . 'branch/', $data);
        } catch(\Exception $e) {
            return false;
        }
    }

    public function delete($data)
    {
        try {
            return $this->trackerApi('delete', $this->api . 'branch/', $data);
        } catch(\Exception $e) {
            return false;
        }
    }
}
