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

Route::post('createBatchPage','HomeController@createBatchPage');

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

Route::post('savegr',array('uses' => 'HomeController@saveGR'));

Route::get('dashboard',function(){
    return \Illuminate\Support\Facades\Hash::make('adminUser');
    return view('pdf');
});


Route::get('reports',function()
{
    if(!Auth::check())
    {
        return view('login');
    }


    return view('reports');
});


Route::get('summaryReport','HomeController@summaryReport');

Route::get('detailedReport','HomeController@detailedReport');

Route::get('getPoArray','HomeController@getPoArray');

Route::get('invoiceView',function(){

    if(!Auth::check())
    {
        return view('login');
    }



    return view('invoiceReport');


});


Route::get('getInvoicePOs','HomeController@getInvoicePOs');


Route::get('catalogue',function(){

    if(!Auth::check())
    {
        return view('login');
    }



    return view('catalogue');


});


Route::get('viewPOPDF','HomeController@viewPOPDF');


Route::get('catalogueUpdate','HomeController@catalogueUpdate');

Route::get('catalogueNewUpdate','HomeController@catalogueNewUpdate');

Route::get('branchInvoice',function(){

    if(!Auth::check())
    {
        return view('login');
    }



    return view('branchInvoice');


});


Route::get('catalogueGetBatch','HomeController@catalogueGetBatch');


Route::get('catalogueTable','HomeController@catalogueTable');

Route::get('cataloguePDF','HomeController@cataloguePDF');

Route::get('giftCatalogue',function(){

    if(!Auth::check())
    {
        return view('login');
    }



    return view('giftCatalogue');


});


Route::get("getGiftBranch","HomeController@getGiftBranch");


Route::get('getGiftBatch','HomeController@getGiftBatch');


Route::post('insertGift','HomeController@insertGift');

