<?php


namespace App\Models;
ini_set('max_execution_time', 5000);
use DB;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;
use View;

class Vendor extends Model
{
    //


    protected $table="vendors";


    public static  function addVendor($input)
    {

        $vendor= $_POST['vendors'];
        $query="Select vendor_id, column_isbn,column_cur,column_price,column_quantity from opac.vendor_parsing where UPPER(vendor)='".strtoupper($vendor)."' ";
        $response=DB::select($query);
        if(count($response)==0)
        {
            return response(array("message"=>"No entries found","code"=>201));
        }


        $isbn_header=$response[0]->column_isbn;
        $currency_header= $response[0]->column_cur;
        $price_header=$response[0]->column_price;
        $stock_header=$response[0]->column_quantity;
        $vendor_id=$response[0]->vendor_id;

        $deleteQuery="delete from opac.VENDOR_STOCK_DETAILS where vendor_id= $vendor_id ";
//        $deleteResponse=DB::delete($deleteQuery);


        Excel::filter('chunk')->load($_FILES["file"]["tmp_name"])->chunk(1000, function($results) use ($vendor_id,$isbn_header,$currency_header,$price_header,$stock_header)
        {
            $sql = 'INSERT INTO "VENDOR_STOCK_DETAILS" (vendor_id, isbn,currency,price,quantity) ';
            $values = '';

            foreach($results as $row)
            {


               $excel_isbn=$row[$isbn_header];
               $excel_currency=$row[$currency_header];
               $excel_price=$row[$price_header];
               $excel_quantity=$row[$stock_header];
                $select= "select $vendor_id,'$excel_isbn','$excel_currency','$excel_price',$excel_quantity from dual";
                if($values =='')
                {
                    $values =$select;
                }else
                {
                    $values = $values. " union all ".$select;
                }



            }
            DB::insert($sql. $values);
            $values="";

        });

        return View::make('vendorSelect');

    }




    public static function getVendors($input)
    {
        $query="select name from ams.suppliers";
        $response=DB::select($query);
        $array=[];
        foreach ($response as $row)
        {
            array_push($array,$row->name);
        }
        return json_encode($array);
    }

}
