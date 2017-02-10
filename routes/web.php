<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('getBatch', 'HomeController@index');

Route::get('/',function(){

    return view('welcome');
});


Route::post('createBatch','HomeController@addBatch');

Route::post('deleteBatch','HomeController@deleteBatch');

Route::post('deleteBobm','HomeController@deleteBobm');

Route::post('batchExpand','HomeController@batchExpand');

Route::post('deleteBID','HomeController@deleteBID');

Route::get('viewBatch','HomeController@viewBatch');

Route::get('vendors',function(){

   return view('vendorSelect');


});


Route::post('addVendor','HomeController@addVendor');

Route::get('getVendors','HomeController@getVendors');



Route::get('batch',function ()
{
    return view('batch');
});

Route::get('purchaseOrders',function ()
{
    return view('purchaseOrders');
});


Route::get('getBatches','HomeController@getBatches');

Route::get('getPOBatch','HomeController@getPOBatch');

Route::get('getPOVendors','HomeController@getPOVendors');

Route::post('getPODetails','HomeController@getPODetails');

Route::post('getIsbnVendors','HomeController@getIsbnVendors');

Route::post('savePoVendor','HomeController@savePoVendor');