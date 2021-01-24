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

Route::post('/forgot-password', 'AuthController@forgot_post');
Route::get('/forgot-password', 'AuthController@forgot');

// AUTH
Route::group(["middleware" => ["merchantAuth"]], function() {
    Route::get('/dashboard', 'MainController@index');

    // MERCHANT
    Route::group(['prefix' => 'm'], function() {
        Route::get('/debug', 'MerchantController@debug');
        Route::get('/', 'MerchantController@view_profile');
        Route::get('/profile', 'MerchantController@view_profile');
        Route::post('/profile', 'MerchantController@modify_profile');

        Route::get('/change-password', 'MerchantController@changePassword');
        Route::post('/change-password', 'MerchantController@changePasswordPost');

        Route::get('/product', 'ProductController@product');
        Route::get('/product/add', 'ProductController@add_product_get');
        Route::post('/product/add', 'ProductController@add_product_post');
        Route::get('/product/edit/{id}', 'ProductController@edit_product_get');
        Route::post('/product/edit/{id}', 'ProductController@edit_product_post');
        Route::get('/product/delete/{id}', 'ProductController@delete_product');
        Route::post('/product/bulkdelete', 'ProductController@bulkDelete');

        //Ajax for Uploader
        Route::post('/product/upload', 'ProductController@upload_product');

        Route::get('/branches', 'BranchController@branch');
        Route::get('/branch/add', 'BranchController@add_branch_get');
        Route::post('/branch/add', 'BranchController@add_branch_post');
        Route::get('/branch/edit/{id}', 'BranchController@edit_branch_get');
        Route::post('/branch/edit/{id}', 'BranchController@edit_branch_post');
        Route::get('/branch/delete/{id}', 'BranchController@delete_branch');

        Route::post('/branch/bulkdelete', 'BranchController@bulkDelete');

        //Ajax for Uploader
        Route::post('/branch/upload', 'BranchController@upload_branch');

        Route::get('/users', 'UsersController@users');

        //Ajax for Uploader
        Route::post('/users/upload', 'MerchantController@upload_users');

        Route::get('/rewards', 'MerchantController@rewards');
    });

    // CAMPAIGN
    Route::group(['prefix' => 'campaign'], function() {
        Route::get('/merchant_branch', 'CampaignController@merchant_branch'); //ajax for get branch
        Route::get('/view', 'CampaignController@view');
        Route::get('/view/{id}', 'CampaignController@view_campaign');
        Route::get('/create', 'CampaignController@create');
        Route::get('/edit/{id}', 'CampaignController@edit');
        Route::post('/edit/{id}', 'CampaignController@edit_campaign');
        Route::post('/create', 'CampaignController@create_campaign');
        Route::get('/campaign_type/task ', 'CampaignController@campaign_type');
        Route::get('/delete/{id}', 'CampaignController@delete_campaign');

        Route::get('/duplicate/{id}', 'CampaignController@duplicate_campaign');
        Route::get('/status/{status}/{id}', 'CampaignController@status_campaign');
        Route::post('/bulk-action', 'CampaignController@bulk_action');
        Route::get('/getCities', 'CampaignController@getCities' );
    });

    // TASK
    Route::group(['prefix' => 'task'], function() {
        Route::get('/view ', 'TaskController@view');
        Route::get('/view_task/{id}', 'TaskController@view_task');
        Route::get('/create ', 'TaskController@create_task_get');
        Route::post('/create ', 'TaskController@create_task_post');
        Route::post('/delete/{id}', 'TaskController@delete_task');
        Route::get('/edit/{id}', 'TaskController@edit_task_get');
        Route::post('/edit/{id}', 'TaskController@edit_task_post');
        Route::post('/bulk-action', 'TaskController@bulk_action');
    });

    /*
    Route::get('/file', 'FileController@index');
    Route::post('/file/store', 'FileController@store');
    */

    // TICKET
    Route::group(['prefix' => 'ticket'], function() {
        Route::get('/view', 'TicketController@view');
        Route::get('/view/{campaignId}/{ticketId}', 'TicketController@view_ticket');
        Route::get('/create', 'TicketController@create');
        Route::get('/approve_ticket/{campaignId}/{ticketId}', 'TicketController@approve_ticket');
        Route::get('/reject_ticket/{campaignId}/{ticketId}', 'TicketController@reject_ticket');
        Route::post('/bulk-action', 'TicketController@bulk_action');
        //Ajax for Save Details
        Route::get('/export_csv', 'TicketController@export_csv');
    });

    Route::group(['prefix' => 'respondent'], function() {
        Route::get('/', 'RespondentController@getAll');
        Route::get('/{id}', 'RespondentController@get');
        Route::get('/block/{id}', 'RespondentController@block');
        Route::get('/export_csv', 'RespondentController@exportList');
        Route::get('/export_csv/{id}', 'RespondentController@exportRespondentCsv');
    });

    Route::group(['prefix' => 'payout'], function() {
        Route::get('/', 'PayoutController@getAll');
        Route::get('/{id}', 'PayoutController@get');
        Route::post('/{id}/update', 'PayoutController@update');
        Route::get('/export_csv', 'PayoutController@exportList');
    });

});
