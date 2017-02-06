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

Route::get('batch',function ()
{
    return view('batch');
});


