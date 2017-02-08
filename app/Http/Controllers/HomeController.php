<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Batch;

class HomeController extends Controller
{
    //


    public function index(Request $request)
    {
        return Batch::index($request->all);
    }


    public function addBatch(Request $request)
    {
        return Batch::addBatch($request->all());

    }



    public function deleteBatch(Request $request)
    {
        return Batch::deleteBatch($request->all());
    }




    public function deleteBobm(Request $request)
    {
        return Batch::deleteBobm($request->all());
    }




    public function batchExpand(Request $request)
    {
        return Batch::batchExpand($request->all());
    }



    public function deleteBID(Request $request)
    {
        return Batch::deleteBID($request->all());
    }


    public function viewBatch(Request $request)
    {
        return Batch::viewBatch($request->all());
    }


    public function test(Request $request)
    {
        return Batch::test($request->all());
    }

}
