<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Validator,Redirect,Response,File;
use App\Document;
 
class FileController extends Controller
{
 
    public function index()
    {
        return view('file');
    }
 
    public function store(Request $request)
    {
       request()->validate([
         'file'  => 'required|mimes:doc,docx,pdf,txt|max:2048',
       ]);
  
        if ($files = $request->file('file')) {
             
            //store file into document folder
            $file = $request->file->store('public/documents');
 
            //store your file into database
            //$document = new Document();
            //$document->title = $file;
            //$document->save();
              
            return Response()->json([
                "success" => true,
                "file" => $file
            ]);
  
        }
  
        return Response()->json([
                "success" => false,
                "file" => ''
          ]);
  
    }
}