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
    return redirect(secure_url('home'));
});

Route::get('/signin', function () {
    return view('signin');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/changePwd.modal', 'HomeController@changePwd');
Route::post('/changePwd', 'UserController@doChangePwd');

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
Route::get('/ot/userDetails.modal/{id}', 'HomeController@userDetailsModal');
Route::get('/ot/schoolDetails.modal/{id}', 'HomeController@schoolDetailsModal');
Route::get('/ot/committeeDetails.modal/{id}', 'HomeController@committeeDetailsModal');

Route::post('/saveRegDel', 'UserController@regSaveDel');
Route::post('/saveRegVol', 'UserController@regSaveVol');
Route::post('/saveRegObs', 'UserController@regSaveObs');

Route::get('/regManage', 'HomeController@regManage');
Route::get('/userManage', 'HomeController@userManage');
Route::get('/schoolManage', 'HomeController@schoolManage');
Route::get('/committeeManage', 'HomeController@committeeManage');

Route::get('/school/verify/{id}', 'UserController@schoolVerify');
Route::get('/school/unverify/{id}', 'UserController@schoolUnverify');
Route::get('/ot/verify/{id}/{status}', 'UserController@setStatus');
Route::post('/ot/update/user/{id}', 'UserController@updateUser');
Route::post('/ot/update/school/{id}', 'UserController@updateSchool');
Route::post('/ot/update/committee/{id}', 'UserController@updateCommittee');

Route::get('/ot/delete/user/{id}', 'UserController@deleteUser');
Route::get('/ot/delete/school/{id}', 'UserController@deleteSchool');
Route::get('/ot/delete/committee/{id}', 'UserController@deleteCommittee');



//Route::get('/regschools', 'UserController@regSchool');
//Route::get('/test', 'UserController@test');

Route::post('/pay/info', 'PayController@payInfo');
Route::get('/pay/invoice', 'HomeController@invoice');
Route::get('/pay/checkout.modal', 'HomeController@checkout');

Route::get('/ajax/registrations', 'DatatablesController@registrations');
Route::get('/ajax/users', 'DatatablesController@users');
Route::get('/ajax/schools', 'DatatablesController@schools');
Route::get('/ajax/committees', 'DatatablesController@committees');

