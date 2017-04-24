<?php

namespace App\Http\Controllers;
use App\Models\report;
use View;
use Auth;
use Illuminate\Support\Facades\Input;
use App\Models\PO;
use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\Vendor;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

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

    public function insertPO(Request $request)
    {
        return PO::insertPO($request->all());
    }

    public function testPDF(Request $request)
    {
        return PO::test($request->all());
    }

    public function getPO (Request $request)
    {
        return PO::getPO($request->all());
    }

    public function showLogin()
    {
        // show the form
        return View::make('login');
    }

    public function doLogin()
    {
// validate the info, create rules for the inputs
        $rules = array(
            'email'    => 'required|email', // make sure the email is an actual email
            'password' => 'required|alphaNum|min:3' // password can only be alphanumeric and has to be greater than 3 characters
        );

// run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);

// if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::to('login')
                ->withErrors($validator) // send back all errors to the login form
                ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        } else {

            // create our user data for the authentication
            $userdata = array(
                'email'     => Input::get('email'),
                'password'  => Input::get('password')
            );

            // attempt to do the login
            if (Auth::attempt($userdata)) {

                // validation successful!
                // redirect them to the secure section or whatever
                // return Redirect::to('secure');
                // for now we'll just echo success (even though echoing in a controller is bad)
                return Redirect::to('/');

            } else {

                // validation not successful, send back to form
                return Redirect::to('login');

            }

        }
    }

    // app/controllers/HomeController.php
    public function doLogout()
    {
        Auth::logout(); // log the user out of our application
        return Redirect::to('login'); // redirect the user to the login screen
    }



    public function saveGR(Request $request)
    {
        return PO::saveGR($request->all());
    }


    public function summaryReport(Request $request)
    {
        return report::summaryReport($request->all());
    }


    public function detailedReport(Request $request)
    {
        return report::detailedReport($request->all());
    }

    public function createBatchPage(Request $request)
    {
        return Batch::createBatchPage($request->all());
    }

    public  function getPoArray(Request $request)
    {
        return report::getPoArray($request->all());
    }


    public function getInvoicePOs(Request $request)
    {
        return report::getInvoicePOs($request->all());
    }


    public function viewPOPDF(Request $request)
    {
        return report::viewPOPDF($request->all());
    }


    public function catalogueUpdate(Request $request)
    {
        return report::catalogueUpdate($request->all());
    }


    public function catalogueNewUpdate(Request $request)
    {
        return report::catalogueNewUpdate($request->all());
    }


    public function catalogueGetBatch(Request $request)
    {
        return report::catalogueGetBatch($request->all());
    }


    public function catalogueTable(Request $request)
    {
        return report::catalogueTable($request->all());
    }


    public function cataloguePDF(Request $request)
    {
        return report::cataloguePDF($request->all());
    }


    public function indISBNVal(Request $request)
    {
        return report::indISBNVal($request->all());
    }


    public function getGiftBatch(Request $request)
    {
        return report::getGiftBatch($request->all());
    }


    public function getGiftBranch(Request $request)
    {
        return report::getGiftBranch($request->all());
    }


    public function insertGift(Request $request)
    {
        return report::insertGift($request->all());
    }


    public function getGiftBatches(Request $request)
    {
        return report::getGiftBatches($request->all());
    }


    public function isbnValidate(Request $request)
    {
        return report::isbnValidate($request->all());
    }

    public function updateisbn(Request $request)
    {
        return report::updateisbn($request->all());
    }

    public function generatecsv(Request $request)
    {
        return report::generatecsv($request->all());
    }

    public function processDateReport(Request $request)
    {
        return report::processDateReport($request->all());
    }

    public function getDefaultReports(Request $request)
    {
        return report::getDefaultReports($request->all());
    }


    public function getDateReports(Request $request)
    {
        return report::getDateReports($request->all());
    }


    public function catReport(Request $request)
    {
        return report::catReport($request->all());
    }


    public function catalogue(Request $request)
    {
        return report::catalogue($request->all());
    }

    public function insertMaterials(Request $request)
    {
        return PO::insertMaterials($request->all());
    }


    public function materials(Request $request)
    {
        return PO::materials($request->all());
    }

    public function deleteMaterialPO(Request $request)
    {
        return PO::deleteMaterialPO($request->all);
    }

    public function updateMaterials(Request $request)
    {
        return PO::updateMaterials($request->all());
    }
    public function materialPDF(Request $request)
    {
        return report::materialPDF($request->all());
    }


    public function listMaterials(Request $request)
    {
        return PO::listMaterials($request->all());
    }

    public function eightyTwenty(Request $request)
    {
        return report::eightyTwenty($request->all());
    }

    public function invoice(Request $request)
    {
        return report::invoice($request->all());
    }


    public function invoicePDFDownload(Request $request)
    {
        return report::invoicePDFDownload($request->all());
    }


    public function debitCredit(Request $request)
    {
        return report::debitCredit($request->all());
    }

}


