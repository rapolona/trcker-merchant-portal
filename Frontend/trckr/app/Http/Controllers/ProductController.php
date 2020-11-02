<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Validator,Redirect,File;
use Config, Session;
use App\Services\ProductService;

class ProductController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
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

                $temp_products = array(
                    $header[0] => $temp[0],
                    $header[1] => $temp[1]
                );

                $validator = Validator::make($temp_products, [
                    'product_name' => 'required|max:64',
                    'product_description' => 'required|max:255'
                ]);

                if ($validator->fails())
                {
                    $error_string = "<b>Row {$count}: Fields with Errors</b><br/>";
                    foreach ($validator->errors()->messages() as $k => $v)
                    {
                        $error_string .= "{$k}: <br/>";
                        foreach ($v as $l)
                            $error_string .= "{$l}<br/>";
                    }

                    return Response()->json([
                        "success" => false,
                        "message" => $error_string,
                        "file" => $temp_products,
                    ], 422);
                }

                $products[] = $temp_products;
                $count+=1;
            }

            $api_endpoint = Config::get('trckr.backend_url') . "merchant/product";
            $session = $request->session()->get('session_merchant');
            $token = ( ! empty($session->token)) ? $session->token : "";

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
                        "message" => "Failed Adding Product {$count}", // with error: [{$response->status()}] {$response->body()}",
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

    /**
     * List controller instance
     *
     * @return View
     */
    public function product(Request $request)
    {
        $response = $this->productService->getAll();
        return view('concrete.merchant.products', ['products' => json_decode($response)]);
    }

    /**
     * Create controller instance
     *
     * @return View
     */
    public function add_product_get()
    {
        return view('concrete.product.add', []);
    }

    /**
     * Create controller instance
     *
     * @return Json
     */
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
                "type" => "error",
                "file" => $data,
            ], 422);
        }

        $response = $this->productService->create($data);

        $msg = [
            "success" => true,
            "type" => "primary", // success
            "message" => "Add Product successful!",
        ];


        return view('concrete.product.add', ['formMessage' => $msg ]);

    }

    /**
     * Edit forms controller instance
     *
     * @return Json
     */
    public function edit_product_get($product_id)
    {
        $response = $this->productService->getAll();

        $products = json_decode($response->body());

        $edit_product = array();
        foreach($products as $b)
        {
            if ($b->product_id == $product_id) {
                $edit_product = $b;
            }
        }

        return view('concrete.product.edit', ['product' => $edit_product, 'product_id' => $product_id]);
    }

    /**
     * Update Product
     *
     * @return Json
     */
    public function edit_product_post($product_id, Request $request)
    {
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


        $data = (array) $request->all();
        $response = $this->productService->update($data);

        if ($response->status() !== 200)
        {
            //provide handling for failed Edit product
            $msg = [
                "success" => false,
                "message" => "Failed to Edit Product. {$data}",
                "type" => 'error',
            ];
        }else {
            $msg = [
                "success" => true,
                "type" => "primary", // success
                "message" => "Product was successfully modified!",
            ];


        }

        $response = $this->productService->getAll();

        $products = json_decode($response->body());

        $edit_product = array();
        foreach($products as $b)
        {
            if ($b->product_id == $product_id) {
                $edit_product = $b;
            }
        }

        return view('concrete.product.edit', ['formMessage' => $msg, 'product' => $edit_product, 'product_id' => $product_id]);
    }

    public function delete_product($product_id, Request $request)
    {
        $data = [
            "product_id" => $product_id
        ];

        $response = $this->productService->delete($data);

        if ($response->status() !== 200)
        {
            //provide handling for failed Edit product
            $msg = [
                "success" => false,
                "message" => "Failed to Delete Product.",
                "type" => 'error',
            ];
        }else {
            $msg = [
                "success" => true,
                "type" => "primary", // success
                "message" => "Product was successfully deleted!",
            ];
        }

        $request->session()->flash('formMessage', $msg);

        return redirect('/merchant/product')
            ->withInput();
    }
}
