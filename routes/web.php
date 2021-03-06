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

//Route::get('/', function () {
//    return view('welcome');
//});
Route::match(['get', 'post'], 'login', 'Admin\IndexController@login')->name('login');

Route::group(['middleware' => 'logvisitor'], function () {

    Route::any('device', 'Device\DeviceauthorizeController@index');

    Route::any('device2', 'Device\DeviceauthorizeController@index2');

//    Route::any('device/download', 'Device\DeviceauthorizeController@download');

//    Route::any('deviceic', 'Device\DeviceauthorizeController@deviceic');

    Route::any('imgrecevice', 'Home\IndexController@imgRecevice');

    Route::any('Stdevice', 'Home\StController@addStdevice');

    Route::any('imgreceviceSt', 'Home\StController@imgRecevice');



    Route::group(['middleware' => 'checkadminlogin'], function () {

//    Route::get('/','Home\IndexController@index');
//        Route::get('/', 'Home\IndexController@tableshow');
        Route::get('/tableshow', 'Home\IndexController@tableshow');
        Route::get('/tableshow/company/{id}', 'Home\IndexController@showCompany');
        Route::get('/tableshow/company/editItem/{id}', 'Home\IndexController@edita');
        Route::post('/tableshow/company/editItem/', 'Home\IndexController@edita');

        Route::match(['get', 'post'],'/tableshow/searchDevice', 'Home\IndexController@searchDevice');

//    Route::get('/tableshow/addDevice','Home\IndexController@addDevice');
//    Route::post('/tableshow/addDevice','Home\IndexController@addDevice');
        Route::match(['get', 'post'], '/tableshow/addDevice', 'Home\IndexController@addDevice');


//    Route::get('/tableshow/addCompany','Home\IndexController@addCompany');
//    Route::post('/tableshow/addCompany','Home\IndexController@addCompany');
        Route::match(['get', 'post'], '/tableshow/addCompany', 'Home\IndexController@addCompany');

        Route::match(['get', 'post'], '/tableshow/addDid', 'Home\IndexController@createDid');
        Route::get('/tableshow/showDid/{id}', 'Home\IndexController@showDid');

        Route::post('/tableshow/company/dela', 'Home\IndexController@dela');
        Route::get('/additem', 'Home\IndexController@additem');
        Route::get('/importexcel', 'Home\IndexController@importexcel');

        Route::get('/showStdevices', 'Home\StController@showStdevices');
        Route::get('/showStdevices/editStdevice/{id}', 'Home\StController@editStdevices');
        Route::post('/showStdevices/editStdevice/', 'Home\StController@editStdevices');

        Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

        Route::get('logout', 'Admin\IndexController@logout');
    });
});
Route::group(['middleware' => 'checkadminlogin'], function () {

//    Route::get('/','Home\IndexController@index');
    Route::get('/', 'Home\IndexController@tableshow');
});



