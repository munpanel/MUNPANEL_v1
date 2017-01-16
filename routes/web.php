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

// TODO: 判定 - 代表 or 学团 or 组委？
Route::get('/assignments', 'HomeController@assignmentsList');
Route::get('/assignment/{id}/{action?}', 'HomeController@assignment');
Route::post('/assignment/{id}/upload', 'HomeController@uploadAssignment');

// TODO: 判定 - 代表 or 学团 or 组委？
Route::get('/documents', 'HomeController@documentsList');
Route::get('/document/{id}/{action?}', 'HomeController@document');
Route::get('/documentDetails.modal/{id}', 'HomeController@documentDetailsModal');

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
Route::get('/ot/userDetails.modal/{id}', ['middleware' => ['permission:edit-users'], 'uses' => 'HomeController@userDetailsModal']);
Route::get('/ot/schoolDetails.modal/{id}', ['middleware' => ['permission:edit-schools'], 'uses' => 'HomeController@schoolDetailsModal']);
Route::get('/ot/committeeDetails.modal/{id}', ['middleware' => ['permission:edit-committees'], 'uses' => 'HomeController@committeeDetailsModal']);

Route::post('/saveRegDel', 'UserController@regSaveDel');
Route::post('/saveRegVol', 'UserController@regSaveVol');
Route::post('/saveRegObs', 'UserController@regSaveObs');


Route::get('/roleAlloc', function() {
    return view('notavailable');
});

Route::get('/assignmentManage', function() {
    return view('notavailable');
});

Route::get('/regManage', 'HomeController@regManage');
Route::get('/regManage/imexport.modal', 'HomeController@imexportRegistrations');
Route::get('/regManage/export/{flag?}', 'ExcelController@exportRegistrations');
Route::post('/regManage/import', 'ExcelController@importRegistrations');
Route::get('/userManage', ['middleware' => ['permission:edit-users'], 'uses' => 'HomeController@userManage']);
Route::get('/schoolManage', ['middleware' => ['permission:edit-schools'], 'uses' => 'HomeController@schoolManage']);
Route::get('/committeeManage', ['middleware' => ['permission:edit-committees'], 'uses' => 'HomeController@committeeManage']);
Route::get('/nationManage', ['middleware' => ['permission:edit-nations'], 'uses' => 'HomeController@nationManage']);

Route::get('/school/verify/{id}', 'UserController@schoolVerify');
Route::get('/school/unverify/{id}', 'UserController@schoolUnverify');
Route::get('/ot/verify/{id}/{status}', 'UserController@setStatus');
Route::post('/ot/update/user/{id}', ['middleware' => ['permission:edit-users'], 'uses' => 'UserController@updateUser']);
Route::post('/ot/update/school/{id}', ['middleware' => ['permission:edit-schools'], 'uses' => 'UserController@updateSchool']);
Route::post('/ot/update/committee/{id}', ['middleware' => ['permission:edit-committees'], 'uses' => 'UserController@updateCommittee']);

Route::get('/ot/delete/user/{id}', ['middleware' => ['permission:edit-users'], 'uses' => 'UserController@deleteUser']);
Route::get('/ot/delete/school/{id}', ['middleware' => ['permission:edit-schools'], 'uses' => 'UserController@deleteSchool']);
Route::get('/ot/delete/committee/{id}', ['middleware' => ['permission:edit-committees'], 'uses' => 'UserController@deleteCommittee']);


//Route::get('/dais/assignments', 'HomeController@assignment');

//Route::get('/regschools', 'UserController@regSchool');
//Route::get('/test', 'UserController@test');
//Route::get('/createPermissions', 'UserController@createPermissions');

Route::get('/school/payment', 'HomeController@schoolPay');
Route::get('/school/pay/change/{method}', 'HomeController@changeSchoolPaymentMethod');
Route::post('/pay/info', 'PayController@payInfo');
Route::get('/pay/invoice', 'HomeController@invoice');
Route::get('/pay/checkout.modal', 'HomeController@checkout');

Route::get('/store', 'StoreController@home');
Route::get('/store/cart', 'StoreController@displayCart');
Route::post('/store/cart/add/{id}', 'StoreController@addCart');
Route::get('/store/cart/remove/{id}', 'StoreController@removeCart');
Route::get('/store/cart/empty', 'StoreController@emptyCart');
Route::get('/store/order/{id}', 'StoreController@displayOrder');
Route::get('/store/checkout', 'StoreController@checkout');
Route::post('/store/doCheckout', 'StoreController@doCheckout');
Route::get('/goodimg/{id}', 'StoreController@goodImage');


Route::get('/ajax/registrations', 'DatatablesController@registrations');
Route::get('/ajax/users', ['middleware' => ['permission:edit-users'], 'uses' => 'DatatablesController@users']);
Route::get('/ajax/schools', ['middleware' => ['permission:edit-schools'], 'uses' => 'DatatablesController@schools']);
Route::get('/ajax/committees', ['middleware' => ['permission:edit-committees'], 'uses' => 'DatatablesController@committees']);
Route::get('/ajax/nations', ['middleware' => ['permission:edit-nations'], 'uses' => 'DatatablesController@nations']);
Route::get('/ajax/assignments', 'DatatablesController@assignments');
Route::get('/ajax/store', 'DatatablesController@goods');
Route::get('/ajax/documents', 'DatatablesController@documents');
