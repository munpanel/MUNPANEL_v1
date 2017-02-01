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

Route::get('/roleList', 'HomeController@roleList');
Route::get('/roleAlloc', 'HomeController@roleAlloc');

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

// TODO: 添加权限控制
Route::get('/dais/lockAlloc/{confirm?}', 'RoleAllocController@lockAlloc');
Route::get('/dais/removeSeat/{id}', 'RoleAllocController@removeDelegate');
Route::post('/dais/addSeat/{id}', 'RoleAllocController@addDelegate');
Route::get('/dais/freeNation/{id}', 'RoleAllocController@freeNation');
Route::get('/dais/nationDetails.modal/{id}', 'RoleAllocController@nationDetailsModal');
Route::post('/dais/update/nation/{id}', 'RoleAllocController@updateNation');
Route::get('/dais/delete/nation/{id}/{confirm?}', 'RoleAllocController@deleteNation');
Route::get('/dais/linkPartner/{id1}/{id2}', 'RoleAllocController@linkPartner');
Route::get('/dais/linkPartner.modal', 'RoleAllocController@linkPartnerModal');
Route::get('/delBizCard.modal/{id}', 'RoleAllocController@getDelegateBizcard');

//Route::get('/dais/assignments', 'HomeController@assignment');

Route::get('/regDais', 'UserController@regDais');
//Route::get('/regschools', 'UserController@regSchool');
Route::get('/test', 'UserController@test');
Route::get('/createPermissions', 'UserController@createPermissions');

Route::get('/school/payment', 'HomeController@schoolPay');
Route::get('/school/pay/change/{method}', 'HomeController@changeSchoolPaymentMethod');
Route::post('/pay/info', 'PayController@payInfo');
Route::get('/pay/invoice', 'HomeController@invoice');
Route::get('/pay/checkout.modal', 'HomeController@checkout');

Route::get('/ajax/registrations', 'DatatablesController@registrations');
Route::get('/ajax/users', ['middleware' => ['permission:edit-users'], 'uses' => 'DatatablesController@users']);
Route::get('/ajax/schools', ['middleware' => ['permission:edit-schools'], 'uses' => 'DatatablesController@schools']);
Route::get('/ajax/committees', ['middleware' => ['permission:edit-committees'], 'uses' => 'DatatablesController@committees']);
Route::get('/ajax/nations', 'DatatablesController@nations');
Route::get('/ajax/assignments', 'DatatablesController@assignments');
Route::get('/ajax/documents', 'DatatablesController@documents');
Route::get('/ot/generateBadge/{template}/{name}/{school}/{role}/{title}/{mode?}', 'ImageController@generateBadge');
Route::get('/ot/generateBadgeCommittee/{cid}', 'ImageController@committeeBadge');
Route::get('/ot/generateCardsDelegates', 'CardController@generateCardsDelegates');
Route::get('/ot/generateCardsDais', 'CardController@generateCardsDais');
Route::get('/ot/generateCardsVolunteers', 'CardController@generateCardsVolunteers');
Route::get('/ot/generateCardBadges', 'CardController@generateCardbadges');
Route::get('/ot/card/new/{template}/{uid}/{name}/{school}/{role}/{title}', 'CardController@newCard');
Route::get('/ot/importCards', 'CardController@importCards');
Route::get('/ajax/roleAllocNations', 'DatatablesController@roleAllocNations');
Route::get('/ajax/roleAllocDelegates', 'DatatablesController@roleAllocDelegates');
Route::get('/ajax/roleListByNation', 'DatatablesController@roleListByNation');
Route::get('/ajax/roleListByDelegate', 'DatatablesController@roleListByDelegate');
