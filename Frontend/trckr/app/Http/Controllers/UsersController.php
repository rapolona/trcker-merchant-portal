<?php

namespace App\Http\Controllers;

Use App\User;
use App\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Validator,Redirect,File;
use Config, Session;

class UsersController extends Controller
{
    public function users()
    {
        //api call for users - no api endpoint yet
        //http://localhost:6001/?
        /*
        $api_endpoint = "http://localhost:6001/?";
        $users = Http::withToken($this->$_backend_token)->get($api_endpoint,  []);
        $users = json_decode($users);
        */

        $users = array(
            array(
                "no" => 1,
                'name' => 'Jethro Gutierrez',
                "email_address" => "jet.gutierrez@gmail.com",
                "type" => "Operations",
                "action" => 1,
            ),
            array(
                "no" => 2,
                'name' => 'Camille San Antonio',
                "email_address" => "camille.sanantonio@gmail.com",
                "type" => "Tracker",
                "action" => 0,
            ),
            array(
                "no" => 3,
                'name' => 'Celine Yap',
                "email_address" => "celine.yap@gmail.com",
                "type" => "Operations",
                "action" => 1,
            ),

        );
        return view('concrete.merchant.users', ['users' => $users]);
    }

    public function upload_users(Request $request)
    {
        request()->validate([
            'file'  => 'required|mimes:csv,txt|max:2048',
        ]);

        if ($files = $request->file('file')) {
            //store file
            $file = $request->file->store('public/documents');
            $path = $request->file('file')->getPathName();
            //get stored file
            $content = File::get($path);
            //parse
            $content = explode("\r\n", $content);
            //TODO: check header
            $header = explode(";", $content[0]);
            unset($content[0]);
            //TODO: check bramches
            $users = array();
            foreach ($content as $c) {
                $temp = explode(";", $c);
                $users[] = array(
                    $header[0] => $temp[0],
                    $header[1] => $temp[1],
                    $header[2] => $temp[2]
                );
            }

            //TODO: trigger API Call to Upload Users

            return Response()->json([
                "success" => true,
                "messaGE" => "Uploaded file successfully",
                "file" => $users
            ]);

        }

        return Response()->json([
            "success" => false,
            "message" => "Failed to Upload the file",
            "file" => ''
        ]);
    }
}
