<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(["middleware" => ["merchantAuth"]], function() {

	// MERCHANT
    Route::group(['prefix' => 'merchant'], function() {
    	Route::get('/list', 'MerchantController@listAjax');
    });

    // BRANCH
    Route::group(['prefix' => 'branch'], function() {
    	Route::get('/list', 'BranchController@listAjax');
    });

    // TASK
    Route::group(['prefix' => 'task'], function() {
    	Route::get('/list', 'TaskController@listAjax');
    });

    // TICKET
    Route::group(['prefix' => 'ticket'], function() {
    	Route::get('/list', 'TicketController@listAjax');
    });

    // PAYOUT
    Route::group(['prefix' => 'payout'], function() {
    	Route::get('/list', 'PayoutController@listAjax');
    });

    // PAYOUT
    Route::group(['prefix' => 'respondent'], function() {
    	Route::get('/list', 'RespondentController@listAjax');
    });


});