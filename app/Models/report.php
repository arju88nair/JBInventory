<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use View;

class report extends Model
{
    //


    public static function summaryReport($input)
    {
        $query = "
                    select b.id ,b.name,nvl(count(distinct orderid),0) no_of_pos,nvl(sum(ordered_quantity),0) order_qnty,nvl(sum(quantity_recieved),0) rec_qnty
                    from opac.batch b left join memp.batch_vendor_po bvp on b.id=bvp.po_id
                    left join opac.batch_po_invoice_details bpid on b.id=bpid.batch_id
                    group by b.name,b.id";

        $response=DB::select($query);
        $array=[];
        foreach ($response as $item) {

            array_push($array,$item);
        }

        return $array;
    }


    public static function detailedReport()
    {
        $id=$_GET['id'];
        $query="select b.id,b.name,invoice,isbn,quantity_recieved from
                    opac.batch b left join opac.batch_po_invoice_details bpid on b.id=bpid.batch_id
                    where b.id=$id";


        $response=DB::select($query);
        $array=[];
        foreach ($response as $item) {

            array_push($array,$item);
        }
        $array=json_encode($array);

        return View::make('detailedSummary')->with('array',$array);


    }

}


