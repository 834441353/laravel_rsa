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
Route::match(['get','post'],'login','Admin\IndexController@login');


Route::any('device','Device\DeviceauthorizeController@index');

Route::any('imgrecevice','Home\IndexController@imgRecevice');

Route::group(['middleware'=>'checkadminlogin'],function(){

//    Route::get('/','Home\IndexController@index');
    Route::get('/','Home\IndexController@tableshow');
    Route::get('/tableshow','Home\IndexController@tableshow');
    Route::get('/tableshow/company/{id}','Home\IndexController@showCompany');
    Route::get('/tableshow/company/editItem/{id}','Home\IndexController@edita');
    Route::post('/tableshow/company/editItem/','Home\IndexController@edita');

//    Route::get('/tableshow/addDevice','Home\IndexController@addDevice');
//    Route::post('/tableshow/addDevice','Home\IndexController@addDevice');
    Route::match(['get','post'],'/tableshow/addDevice','Home\IndexController@addDevice');


//    Route::get('/tableshow/addCompany','Home\IndexController@addCompany');
//    Route::post('/tableshow/addCompany','Home\IndexController@addCompany');
    Route::match(['get','post'],'/tableshow/addCompany','Home\IndexController@addCompany');

    Route::post('/tableshow/company/dela','Home\IndexController@dela');
    Route::get('/additem','Home\IndexController@additem');
    Route::get('/importexcel','Home\IndexController@importexcel');

    Route::get('logout','Admin\IndexController@logout');
});


