<?php
namespace App\Services;

use Illuminate\Log;
use App\Repositories\AdminRepository;

class AdminService
{
    protected $repository;

    public function __construct(AdminRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create($data)
    {
        return $this->dashboardRepository->create($data);
    }

}