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


Route::get('/main', 'MainController@index');



Route::get('/merchant', 'MerchantController@view_profile');
Route::get('/merchant/view_profile', 'MerchantController@view_profile');
//Ajax for Save Details
Route::post('/merchant/modify_profile', 'MerchantController@modify_profile');

Route::get('/merchant/products', 'MerchantController@products');
//Ajax for Uploader 
Route::post('/merchant/products/upload', 'MerchantController@upload_products');

Route::get('/merchant/branches', 'MerchantController@branches');
//Ajax for Uploader 
Route::post('/merchant/branches/upload', 'MerchantController@upload_branches');

Route::get('/merchant/users', 'MerchantController@users');
//Ajax for Uploader 
Route::post('/merchant/users/upload', 'MerchantController@upload_users');

Route::get('/merchant/rewards', 'MerchantController@rewards');

Route::get('/campaign/view ', 'CampaignController@view');
Route::get('/campaign/create ', 'CampaignController@create');
//Ajax for Campaign Creation
Route::post('/campaign/create_campaign ', 'CampaignController@create_campaign');


Route::get('/task/view ', 'TaskController@view');
Route::get('/task/create ', 'TaskController@create');

/*
Route::get('/file', 'FileController@index');
Route::post('/file/store', 'FileController@store');
*/

Route::get('/ticket/view', 'TicketController@view');
Route::get('/ticket/create', 'TicketController@create');
//Ajax for Save Details
Route::get('/ticket/create_ticket', 'TicketController@create_ticket');