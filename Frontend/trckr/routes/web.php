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
Route::get('/login', 'AuthController@login_get')->name('login');
Route::post('/login', 'AuthController@login_post');
Route::get('/logout', 'AuthController@logout');

// AUTH
Route::group(["middleware" => ["merchantAuth"]], function() {
    Route::get('/dashboard', 'MainController@index');

    // MERCHANT
    Route::group(['prefix' => 'merchant'], function() {
        Route::get('/debug', 'MerchantController@debug');
        Route::get('/', 'MerchantController@view_profile');
        Route::get('/view_profile', 'MerchantController@view_profile');

        //Ajax for Save Details
        Route::post('/modify_profile', 'MerchantController@modify_profile');

        Route::get('/product', 'ProductController@product');
        Route::get('/product/add', 'ProductController@add_product_get');
        Route::post('/product/add', 'ProductController@add_product_post');
        Route::get('/product/edit/{id}', 'ProductController@edit_product_get');
        Route::post('/product/edit/{id}', 'ProductController@edit_product_post');
        Route::get('/product/delete/{id}', 'ProductController@delete_product');

        //Ajax for Uploader
        Route::post('/product/upload', 'ProductController@upload_product');

        Route::get('/branch', 'BranchController@branch');
        Route::get('/branch/add', 'BranchController@add_branch_get');
        Route::post('/branch/add', 'BranchController@add_branch_post');
        Route::get('/branch/edit', 'BranchController@edit_branch_get');
        Route::post('/branch/edit', 'BranchController@edit_branch_post');
        Route::post('/branch/delete', 'BranchController@delete_branch');

        //Ajax for Uploader
        Route::post('/branch/upload', 'BranchController@upload_branch');

        Route::get('/users', 'UsersController@users');

        //Ajax for Uploader
        Route::post('/users/upload', 'MerchantController@upload_users');

        Route::get('/rewards', 'MerchantController@rewards');
    });

    // CAMPAIGN
    Route::group(['prefix' => 'campaign'], function() {
        Route::get('/view ', 'CampaignController@view');
        Route::get('/view_campaign ', 'CampaignController@view_campaign');
        Route::get('/create ', 'CampaignController@create');
        Route::get('/edit ', 'CampaignController@edit');
        //Ajax for Campaign Creation
        Route::post('/edit_campaign ', 'CampaignController@edit_campaign');
        Route::post('/create_campaign ', 'CampaignController@create_campaign');
        Route::get('/campaign_type/task ', 'CampaignController@campaign_type');
        Route::post('/delete ', 'CampaignController@delete_campaign');
    });

    // TASK
    Route::group(['prefix' => 'task'], function() {
        Route::get('/view ', 'TaskController@view');
        Route::get('/view_task ', 'TaskController@view_task');
        Route::get('/create ', 'TaskController@create_task_get');
        Route::post('/create ', 'TaskController@create_task_post');
        Route::post('/delete', 'TaskController@delete_task');
        Route::get('/edit', 'TaskController@edit_task_get');
        Route::post('/edit', 'TaskController@edit_task_post');
    });

    /*
    Route::get('/file', 'FileController@index');
    Route::post('/file/store', 'FileController@store');
    */

    // TICKET
    Route::group(['prefix' => 'ticket'], function() {
        Route::get('/view', 'TicketController@view');
        Route::get('/view_ticket', 'TicketController@view_ticket');
        Route::get('/create', 'TicketController@create');
        Route::post('/approve_ticket', 'TicketController@approve_ticket');
        Route::post('/reject_ticket', 'TicketController@reject_ticket');
        //Ajax for Save Details
        Route::get('/export_csv', 'TicketController@export_csv');
    });


});
