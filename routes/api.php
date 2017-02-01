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

Route::post('/payNotify', 'PayController@payNotify');

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->get('users/{id}', 'App\Http\Controllers\ApiController@getUser');
    $api->get('cards/{id}', 'App\Http\Controllers\ApiController@getCard');
    $api->get('test', function () {
        return 'It is ok';
    });
});
