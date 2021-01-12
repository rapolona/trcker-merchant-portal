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

    private $defaultPerPage;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
        $this->defaultPerPage = Config::get('trckr.table_pagination');
    }

    public function listAjax(Request $request)
    {
        $data = [
            'count_per_page' => isset($request->per_page)? $request->per_page : $this->defaultPerPage,
            'page' => isset($request->per_page)? $request->page : 1
        ];
        
        $list = $this->productService->getAll($data);

        $list = [
            'data' => $list->rows,
            'per_page' => $data['count_per_page'],
            'current_page' => $list->current_page,
            'total_pages' => $list->total_pages
        ];

        return Response::json(['data' => $list, 'msg' => 'Success!' ], 200);
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
        return view('concrete.merchant.products', ['products' => $response]);
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
     * @return View
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
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $response = $this->productService->create($data);

        $msg = [
            "success" => true,
            "type" => "success",
            "message" => "Add Product successful!",
        ];

        return view('concrete.product.add', ['formMessage' => $msg ]);
    }

    /**
     * Edit forms controller instance
     *
     * @return View
     */
    public function edit_product_get($productId)
    {
        $product = $this->productService->get($productId);
        return view('concrete.product.edit', ['product' => $product, 'product_id' => $productId]);
    }

    /**
     * Update Product
     *
     * @return Json
     */
    public function edit_product_post($productId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|max:64',
            'product_description' => 'required|max:255'
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = (array) $request->all();
        $response = $this->productService->update($data);

        $msg = [
            "success" => true,
            "type" => "success",
            "message" => "Product was successfully modified!",
        ];

        $product = $this->productService->get($productId);
        return view('concrete.product.edit', ['formMessage' => $msg, 'product' => $product, 'product_id' => $productId]);
    }

    /**
     * Delete Single Product
     *
     * @return Redirect
     */
    public function delete_product($product_id, Request $request)
    {
        $data = [
            "product_id" => $product_id
        ];

        $response = $this->productService->delete($data);

        $msg = [
            "success" => true,
            "type" => "success",
            "message" => "Product was successfully deleted!",
        ];

        return redirect('/m/product')
            ->with("formMessage", $msg);
    }

    /**
     * Delete Multiple Product
     *
     * @return Redirect
     */
    public function bulkDelete(Request $request)
    {

        $ids = json_decode($request->delete_ids);

        foreach($ids as $product_id){
            $data = [
                "product_id" => $product_id
            ];

            $response = $this->productService->delete($data);
        }

        $msg = [
            "success" => true,
            "type" => "success",
            "message" => "Products were successfully deleted!",
        ];

        return redirect('/m/product')
            ->with("formMessage", $msg);
    }
}
