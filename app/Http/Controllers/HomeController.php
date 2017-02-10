<?php

namespace App\Http\Controllers;

use App\Models\PO;
use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\Vendor;

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

    public function addVendor(Request $request)
    {
        return Vendor::addVendor($request->all());
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


    public function getVendors(Request $request)
    {
        return Vendor::getVendors($request->all());
    }

    public function getBatches(Request $request)
    {
        return PO::getBatches($request->all());


    }
    public function getPOBatch(Request $request)
    {
        return PO::getPOBatch($request->all());
    }



    public function getPOVendors(Request $request)
    {


        return PO::getPOVendors($request->all());
    }


    public function getPODetails(Request $request)
    {
       return PO::getPODetails($request->all());
    }


    public function getIsbnVendors(Request $request)
    {

        return PO::getIsbnVendors($request->all());
    }

    public function savePoVendor(Request $request)
    {
        return PO::savePoVendor($request->all());
    }


}
