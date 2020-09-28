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
use Illuminate\Support\Facades\Validator;

class MainController extends Controller
{
    public function adminlte_image()
    {
        return 'https://picsum.photos/300/300';
    }

    public function adminlte_desc()
    {
        return 'That\'s a nice guy';
    }

    public function adminlte_profile_url()
    {
        return 'profile/username';
    }
    public function index(request $request)
    {
        $data = array();
        $users = array('Jet');


        $api_endpoint = Config::get('trckr.backend_url') . "merchant/dashboard/activecampaign";

        $session = $request->session()->get('session_merchant');
        $token = ( ! empty($session->token)) ? $session->token : "";

        $activecampaigns = Http::withToken($token)->get($api_endpoint, []);
        
        if ($activecampaigns->status() !== 200)
        {
            if ($activecampaigns->status() === 403) {
                $validator = Validator::make($request->all(), []);
                $validator->getMessageBag()->add('email', "Session Expired. Please login again. {$activecampaigns->body()}");
            
                return redirect('/')
                    ->withErrors($validator)
                    ->withInput();      
            }

            if ($activecampaigns->status() === 500) {
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

        $data['activecampaigns'] = json_decode($activecampaigns->body());

        $data['activecampaigns_count'] = ($data['activecampaigns'][0]->active_campaigns);

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/dashboard/totalrespondents";

        $session = $request->session()->get('session_merchant');
        $token = ( ! empty($session->token)) ? $session->token : "";

        $totalrespondents = Http::withToken($token)->get($api_endpoint, []);
        
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

        return view('main', $data);
    }
}
