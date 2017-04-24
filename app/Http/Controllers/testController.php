<?php

namespace App\Http\Controllers;
use View;
use Auth;
use Illuminate\Support\Facades\Input;
use App\Models\PO;
use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\Vendor;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class testController extends Controller
{
    //


    public function index(Request $request)
    {
        return Batch::index($request->all);
    }


   }
