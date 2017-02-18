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
//use Barryvdh\DomPDF\PDF;
use Auth;

Route::get('getBatch', 'HomeController@index');

Route::get('/',function(){

    if(!Auth::check())
    {
        return view('login');
    }



    return view('welcome');
});


Route::post('createBatch','HomeController@addBatch');

Route::post('deleteBatch','HomeController@deleteBatch');

Route::post('deleteBobm','HomeController@deleteBobm');

Route::post('batchExpand','HomeController@batchExpand');

Route::post('deleteBID','HomeController@deleteBID');

Route::get('viewBatch','HomeController@viewBatch');

Route::get('vendors',function(){
    if(!Auth::check())
    {
        return view('login');
    }



    return view('vendorSelect');


});


Route::post('addVendor','HomeController@addVendor');

Route::get('getVendors','HomeController@getVendors');



Route::get('batch',function ()
{
    if(!Auth::check())
    {
        return view('login');
    }


    return view('batch');
});

Route::get('purchaseOrders',function ()
{
    if(!Auth::check())
    {
        return view('login');
    }


    return view('purchaseOrders');
});


Route::get('getBatches','HomeController@getBatches');

Route::get('getPOBatch','HomeController@getPOBatch');

Route::get('getPOVendors','HomeController@getPOVendors');

Route::post('getPODetails','HomeController@getPODetails');

Route::post('getIsbnVendors','HomeController@getIsbnVendors');

Route::post('savePoVendor','HomeController@savePoVendor');

Route::get('scanner',function(){
    if(!Auth::check())
    {
        return view('login');
    }



    return view('scanner');
});


Route::post('insertPO','HomeController@insertPO');

Route::get('PDF','HomeController@testPDF');

Route::get('getPO',function()
{
    if(!Auth::check())
    {
        return view('login');
    }


    return view('poTotal');
});

Route::get('getPOs','HomeController@getPO');

Auth::routes();
//
//Route::get('/home', 'HomeController@index');

// route to show the login form
Route::get('login', array('uses' => 'HomeController@showLogin'));

// route to process the form
Route::post('login', array('uses' => 'HomeController@doLogin'));

Route::get('logout', array('uses' => 'HomeController@doLogout'));