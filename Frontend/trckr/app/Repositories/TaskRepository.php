<?php
namespace App\Repositories;

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
            return $this->trackerApi('post', $this->api, $data);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function update($data)
    {
        try {
            return $this->trackerApi('put', $this->api, $data);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function get($data)
    {
        try {
            return $this->trackerApi('get', $this->api. '?task_id=' . $data['task_id'] , $data);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function getAll()
    {
        try {
            return $this->trackerApi('get', $this->api , []);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function getTaskByCurrentMerchant()
    {
        try {
            $data = ['task_type'=>'Merchandising'];
            return $this->trackerApi('get', $this->api, $data);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function getTaskActionClassification()
    {
        try {
            return $this->trackerApi('get', Config::get('trckr.backend_url') . 'api/task_action_classification' , []);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }


}
