<?php

use Illuminate\Http\Request;

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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

/** @var Dingo\Api\Routing\Router $oApi */
$oApi = app('api.router');

$oApi->version('v1', function(Dingo\Api\Routing\Router $oApi) {

    // Commented out for the sake of simplicity
    // $oApi->group(['middelware' => 'auth'], function(Dingo\Api\Routing\Router $oApi) {

        $oApi->get('campaigns/{id}/csv', \App\Http\Controllers\CampaignController::class . '@csv')->name('campaigns.csv');
        $oApi->resource('campaigns',    \App\Http\Controllers\CampaignController::class);

        $oApi->resource('customers',    \App\Http\Controllers\CustomerController::class);
        $oApi->resource('users',        \App\Http\Controllers\UserController::class);

    //});
});