<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;


Use App\User;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Config, Session;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Validator;

class MainController extends Controller
{

    private $service;

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    public function index(request $request)
    {
        $data = array();

        $activeCampaigns = $this->service->getActivecampaign();

        if ($activeCampaigns->status() !== 200)
        {
            if ($activecampaigns->status() === 403) {
                $validator = Validator::make($request->all(), []);
                $validator->getMessageBag()->add('email', "Session Expired. Please login again. {$activecampaigns->body()}");

                return redirect('/')
                    ->withErrors($validator)
                    ->withInput();
            }

            if ($activeCampaigns->status() === 500) {
                $handler = json_decode($activecampaigns->body());

                if ($handler->message->name == "JsonWebTokenError")

                $validator = Validator::make($request->all(), []);
                $validator->getMessageBag()->add('email', "Session Expired. Please login again. {$activecampaigns->body()}");

                return redirect('/')
                    ->withErrors($validator)
                    ->withInput();
            }

            //general handling
            return redirect('/dashboard');
        }

        $data['activecampaigns'] = json_decode($activeCampaigns->body());

        $data['activecampaigns_count'] = ($data['activecampaigns'][0]->active_campaigns);

        $totalrespondents = $this->service->getTotalRespondents();

        if ($totalrespondents->status() !== 200)
        {
            if ($totalrespondents->status() === 403) {
                $validator = Validator::make($request->all(), []);
                $validator->getMessageBag()->add('email', "Session Expired. Please login again. {$totalrespondents->body()}");

                return redirect('/')
                    ->withErrors($validator)
                    ->withInput();
            }

            if ($totalrespondents->status() === 500) {
                $handler = json_decode($totalrespondents->body());

                if ($handler->message->name == "JsonWebTokenError")

                $validator = Validator::make($request->all(), []);
                $validator->getMessageBag()->add('email', "Session Expired. Please login again. {$totalrespondents->body()}");

                return redirect('/')
                    ->withErrors($validator)
                    ->withInput();
            }

            //general handling
            return redirect('/dashboard');
        }

        $data['totalrespondents'] = json_decode($totalrespondents->body());

        return view('concrete.dashboard.dashboard', $data);
    }
}
