<?php

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

Route::get('/', function () {
    return redirect('home');
});

Route::get('/signin', function () {
    return view('signin');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/assignments', function() {
    return view('notavailable');
});

Route::get('/documents', function() {
    return view('notavailable');
});

Route::get('/pages', function() {
    return view('notavailable');
});

Route::get('/chair', function() {
    return view('notavailable');
});

Route::get('/fb', function() {
    return view('notavailable');
});

Route::get('/ddltimer', function() {
    return view('notavailable');
});

Route::get('/reg.modal/{id?}', 'HomeController@regModal');
Route::post('/saveRegDel', 'UserController@regSaveDel');
Route::post('/saveRegVol', 'UserController@regSaveVol');
Route::post('/saveRegObs', 'UserController@regSaveObs');

Route::get('/regManage', 'HomeController@regManage');

Route::get('/school/verify/{id}', 'UserController@schoolVerify');
Route::get('/school/unverify/{id}', 'UserController@schoolUnverify');

Route::get('/regschools', 'UserController@regSchool');
Route::get('/test', 'UserController@test');

Route::post('/pay/info', 'PayController@payInfo');
Route::get('/pay/invoice', 'HomeController@invoice');
Route::get('/pay/checkout.modal', 'HomeController@checkout');

