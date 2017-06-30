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

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:api');

Route::group(['domain' => config('munpanel.payDomain')], function() {
    Route::any('/notify', 'PayController@payNotify')->name('payNotify');
});

Route::group(['domain' => 'sms.munpanel.com'], function() {
    Route::get('/sms_received', 'SmsController@autoReplySMS');
});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->get('getUser/{id}', 'App\Http\Controllers\ApiController@getUser');
    $api->get('getCard/{id}', 'App\Http\Controllers\ApiController@getCard');
    $api->get('getSpecific/{id}', 'App\Http\Controllers\ApiController@getSpecific');
    $api->get('getSchool/{id}', 'App\Http\Controllers\ApiController@getSchool');
    $api->get('getCommittee/{id}', 'App\Http\Controllers\ApiController@getCommittee');
    $api->get('test', function () {
        return 'It is ok';
    });
});
