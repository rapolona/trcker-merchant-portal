<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class MainController extends Controller
{
    public function index()
    {
        $data = array();
        $users = array('Jet');

        //return view('main', [user: $user]);
        return view('main',
            ['users' => 'Victoria']);
    }
}
