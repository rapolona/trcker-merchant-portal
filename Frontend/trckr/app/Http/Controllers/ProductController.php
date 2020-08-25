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

class ProductController extends Controller
{
    public function upload_product(Request $request)
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

            //header
            $header = explode(";", $content[0]);
            #product_price to be changed by description later on - done
            $default_headers = array('product_name', 'product_description');
            foreach($header as $h)
            {
                if ( ! in_array($h, $default_headers)){
                    return Response()->json([
                        "success" => false,
                        "message" => "CSV Invalid Header {$h}"
                    ], 422);
                }
            }
            unset($content[0]);

            $products = array();
            $count = 1;
            foreach ($content as $c) {
                $temp = explode(";", $c);

                if ( count($temp) != count($default_headers)){
                    return Response()->json([
                        "success" => false,
                        "message" => "Invalid Input on Item {$count}"
                    ]);
                }

                $products[] = array(
                    $header[0] => $temp[0], 
                    $header[1] => $temp[1]
                );
            }

            $api_endpoint = Config::get('trckr.backend_url') . "merchant/product";
            $session = $request->session()->get('session_merchant');
            $token = $session->token;
            
            $count = 1;
            $debug = array();
            foreach($products as $p)
            {
                $response = Http::withToken($token)->post($api_endpoint, $p);
                $debug[] = $response;

   
                if ($response->status() !== 200)
                {
                    //provide handling for failed merchant profile modification
                    return Response()->json([
                        "success" => false,
                        "message" => "Failed Adding Product {$count} with error: [{$response->status()}] {$response->body()}",
                        "file" => json_encode($response),
                        "data" => json_encode($p)
                    ], 422);
                }
                $count+=1;
            }

            return Response()->json([
                "success" => true,
                "message" => "Uploaded file successfully", //. $response->body(),
                "file" => $products
            ]);
            
        }
  
        return Response()->json([
            "success" => false,
            "message" => "Failed to Upload the file",
            "file" => ''
        ]);
    }

    public function product(Request $request)
    {
        //api call for products
        //http://localhost:6001/merchant/products

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/products";

        $session = $request->session()->get('session_merchant');
        $token = $session->token;

        $response = Http::withToken($token)->get($api_endpoint, []);
        
        if ($response->status() !== 200)
        {
            if ($response->status() === 403) {
                $validator = Validator::make($request->all(), []);
                $validator->getMessageBag()->add('email', "Session Expired. Please login again. {$response->body()}");
            
                return redirect('/')
                    ->withErrors($validator)
                    ->withInput();      
            }

            //provide handling for failed product retrieval
            return redirect('/dashboard');
        }

        $products = json_decode($response);

        $count = 0;

        foreach ($products as &$p)
        {
            $p->no = $count+1;
            //hotfix - removed
            //$p->description = ( ! empty($p->description)) ? $p->description : $p->product_price;
            $count+=1;
        } 

        return view('merchant.products', ['products' => $products]);
    }

    public function add_product_get()
    {
        return view('product.add', []);
    }

    public function add_product_post(Request $request)
    {
        $data = (array) $request->all();

        $validator = Validator::make($request->all(), [
            'product_name' => 'required|max:64',
            'product_description' => 'required|max:255'
        ]);

        if ($validator->fails())
        {
            $error_string = "<b>Fields with Errors</b><br/>";
            foreach ($validator->errors()->messages() as $k => $v)
            {
                $error_string .= "{$k}: <br/>";
                foreach ($v as $l)
                    $error_string .= "{$l}<br/>";
            }

            return Response()->json([
                "success" => false,
                "message" => $error_string,
                "file" => $data,
            ], 422);
        }

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/product";

        $session = $request->session()->get('session_merchant');
        $token = $session->token;
        
        $response = Http::withToken($token)->post($api_endpoint, $data);
        
        if ($response->status() !== 200)
        {
            //provide handling for failed merchant profile modification
            return Response()->json([
                "success" => false,
                "message" => "Failed to Add Product with error:" . $response->body(),
                "file" => $data,
            ], 422);
        }

        return Response()->json([
            "success" => true,
            "message" => "Add Product successfully saved!", //. $response->body(),
            "file" => $data,
        ]);
    }

    public function edit_product_get(Request $request)
    {
        $product_id = $request->query('product_id');

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/product";

        $session = $request->session()->get('session_merchant');
        $token = $session->token;

        $response = Http::withToken($token)->get($api_endpoint, []);
        
        if ($response->status() !== 200)
        {
            //provide handling for failed merchant profile retrieval
            return redirect('/dashboard');
        }

        $productes = json_decode($response->body());

        $edit_product = array();
        foreach($productes as $b)
        {
            if ($b->product_id == $product_id) {
                $edit_product = $b;
            }
        }

        return view('product.edit', ['product' => $edit_product, 'product_id' => $product_id]);
    }

    public function edit_product_post(Request $request)
    {
        $product_id = $request->query('product_id');

        //TODO: Field validation, will throw error on hit as response
        $data = (array) $request->all();
        //$data['id'] = $product_id;
        //$data['product_id'] = $product_id;

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/product";

        $session = $request->session()->get('session_merchant');
        $token = $session->token;

        $response = Http::withToken($token)->put($api_endpoint, $data);

        if ($response->status() !== 200)
        {
            //provide handling for failed Edit product
            return Response()->json([
                "success" => false,
                "message" => "Failed to Edit Product with error:" . $response->body(),
                "file" => $data,
            ], 422);
        }

        return Response()->json([
            "success" => true,
            "message" => "Edit Product successfully saved!" . $response->body(),
            "file" => $data,
        ]);
    }

    public function delete_product(Request $request)
    {
        $data = (array) $request->all();

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/product";
        $session = $request->session()->get('session_merchant');
        $token = $session->token;
        
        $count = 1;
        $debug = array();

        $products = explode(",", $data['products']);
        foreach($products as $p) {
            $response = Http::withToken($token)->delete($api_endpoint, ["product_id" => $p]);
            $debug[] = $response;

            if ($response->status() !== 200)
            {
                //provide handling for failed merchant profile modification
                return Response()->json([
                    "success" => false,
                    "message" => "Failed Deleting Product {$count} with error: [{$response->status()}] {$response->body()}",
                    "file" => json_encode($response),
                    "data" => json_encode($p)
                ], 422);
            }
            $count+=1;
        }

        return Response()->json([
            "success" => true,
            "message" => "Deleted Products successfully", //. $response->body(),
            "file" => $data['products']
        ]);
    }
}
