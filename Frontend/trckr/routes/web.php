<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'AuthController@index');
Route::get('/login', 'AuthController@login_get');
Route::post('/login', 'AuthController@login_post');
Route::get('/logout', 'AuthController@logout');


Route::get('/dashboard', 'MainController@index');



Route::get('/merchant/debug', 'MerchantController@debug');
Route::get('/merchant', 'MerchantController@view_profile');
Route::get('/merchant/view_profile', 'MerchantController@view_profile');
//Ajax for Save Details
Route::post('/merchant/modify_profile', 'MerchantController@modify_profile');

Route::get('/merchant/product', 'ProductController@product');
Route::get('/merchant/product/add', 'ProductController@add_product_get');
Route::post('/merchant/product/add', 'ProductController@add_product_post');
Route::get('/merchant/product/edit', 'ProductController@edit_product_get');
Route::post('/merchant/product/edit', 'ProductController@edit_product_post');
Route::post('/merchant/product/delete', 'ProductController@delete_product');

//Ajax for Uploader 
Route::post('/merchant/product/upload', 'ProductController@upload_product');

Route::get('/merchant/branch', 'BranchController@branch');
Route::get('/merchant/branch/add', 'BranchController@add_branch_get');
Route::post('/merchant/branch/add', 'BranchController@add_branch_post');
Route::get('/merchant/branch/edit', 'BranchController@edit_branch_get');
Route::post('/merchant/branch/edit', 'BranchController@edit_branch_post');
Route::post('/merchant/branch/delete', 'BranchController@delete_branch');
//Ajax for Uploader 
Route::post('/merchant/branch/upload', 'BranchController@upload_branch');

Route::get('/merchant/users', 'UsersController@users');
//Ajax for Uploader 
Route::post('/merchant/users/upload', 'MerchantController@upload_users');

Route::get('/merchant/rewards', 'MerchantController@rewards');

Route::get('/campaign/view ', 'CampaignController@view');
Route::get('/campaign/view_campaign ', 'CampaignController@view_campaign');
Route::get('/campaign/create ', 'CampaignController@create');
//Ajax for Campaign Creation
Route::post('/campaign/create_campaign ', 'CampaignController@create_campaign');
Route::get('/campaign/campaign_type/task ', 'CampaignController@campaign_type');


Route::get('/task/view ', 'TaskController@view');
Route::get('/task/view_task ', 'TaskController@view_task');
Route::get('/task/create ', 'TaskController@create_task_get');
Route::post('/task/create ', 'TaskController@create_task_post');
Route::post('/task/delete', 'TaskController@delete_task');
Route::get('/task/edit', 'TaskController@edit_task_get');
Route::post('/task/edit', 'TaskController@edit_task_post');
/*
Route::get('/file', 'FileController@index');
Route::post('/file/store', 'FileController@store');
*/

Route::get('/ticket/view', 'TicketController@view');
Route::get('/ticket/view_ticket', 'TicketController@view_ticket');
Route::get('/ticket/create', 'TicketController@create');
Route::post('/ticket/approve_ticket', 'TicketController@approve_ticket');
Route::post('/ticket/reject_ticket', 'TicketController@reject_ticket');
//Ajax for Save Details
Route::get('/ticket/create_ticket', 'TicketController@create_ticket');