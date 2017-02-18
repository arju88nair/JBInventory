<?php

namespace App\Models;
use DB;
use PDF;
use Illuminate\Database\Eloquent\Model;

class PO extends Model
{
    //

    public static function getBatches($input)
    {

        $query="select  id,name,to_char(from_date,'DD-MM-YYYY') from_date,to_char(to_date,'DD-MM-YYYY') to_date,status,to_char(created_at,'DD-MM-YYYY') created_at from opac.batch where active = 1";
        $response=DB::select(DB::raw($query));
        $array=[];
        foreach ($response as $row)
        {
            array_push($array,$row);
        }
        return json_encode($array);
    }

//

    public static function  getPOBatch($input)
    {
        $id=$_GET['id'];

        $sql="select isbn_13,title_id,title,sum(quantity) copies,amount,batch_id,sum(amount) total_amount from
                        opac.branch_order_batch_map bobm join opac.branch_orders bo on bobm.branch_order_id=bo.id
                        join jbprod.titles t on bo.title_id=t.titleid where bobm.batch_id=$id and bobm.active=1
                        group by title_id,amount,isbn_13,title,batch_id";
        $response=DB::select($sql);


        $a=0;
        foreach ($response as $array)
        {
            $a+=(int)$array->copies;
        }



        $sqlOrders="select * from
                    (select z.title_id,isbn,quantity_required from
                    (select title_id,count(*) quantity_required from opac.branch_order_batch_map bobm left join opac.branch_orders bo
                    on bobm.branch_order_id=bo.id
                    where batch_id=$id and active=1
                    group by title_id) z
                    join memp.titles t on z.title_id=t.id ) a
                    left join
                    (select vendor_id,s.name,isbn,quantity,currency,price from ams.suppliers s join opac.vendor_stock_details vsd
                    on s.id=vsd.vendor_id ) b
                    on a.isbn=b.isbn";


        $response_query=DB::select($sqlOrders);
        return array("total"=>$a,"response"=>$response_query);
    }


    public static function getPOVendors($input)
    {


        $query="select id,name,phone,city,discount,email from ams.suppliers";
        $response=DB::select($query);

        $array=[];
        foreach ($response as $row)
        {
            array_push($array,$row);
        }
        return json_encode($array);
    }




    public static function getPODetails($Input)
    {

        $vId=$_POST['vendor_name'];
        $id=$_POST['id'];
//        $getVID="select id from ams.suppliers where  name= '$vendor_name'";
//        $vId=DB::select($getVID)[0]->id;
        $query=" select * from
                (select z.title_id,isbn,quantity_required,title,branch_order_id from
                (select a.title_id,quantity_required-nvl(ordered_qnty,0) as quantity_required,branch_order_id from
                (select title_id,count(*) quantity_required,TAB_TO_STRING(cast(collect(obo.branch_order_id) as t_varchar2_tab)) as branch_order_id
                from opac.branch_order_batch_map bobm left join opac.branch_orders bo
                on bobm.branch_order_id=bo.id join opac.branchorder obo on bobm.branch_order_id=obo.branch_order_id
                where batch_id=$id and active=1
                group by title_id) a
                left join
                (select title_id,sum(ORDERED_QUANTITY) ordered_qnty from memp.batch_vendor_po where po_id=$id group by title_id) b
                on a.title_id=b.title_id) z
                join memp.titles t on z.title_id=t.id ) a
                left join
                (select vendor_id,s.name,isbn,quantity,currency,price from ams.suppliers s join opac.vendor_stock_details vsd
                on s.id=vsd.vendor_id where vsd.vendor_id=$vId) b
                on a.isbn=b.isbn
                    ";

        return json_encode(DB::select($query));



    }


    public  static  function getIsbnVendors($input)

    {
        $isbn=$_POST['isbn'];
        $title=$_POST['title_id'];
        $query="select vendor_id,ISBN,currency,price,quantity,title from   opac.vendor_stock_details vsd   join   jbprod.titles jt on jt.isbn_13 = vsd.isbn where  titleid= $title and  isbn_13 = $isbn";
        $response=DB::select($query);
        return json_encode($response);

    }



    public static function savePoVendor($input)
    {
//        $name=$_POST['name'];
//        $id=$_p[]
    }


    public static function insertPO($input)
    {
        $vId=$_POST['vName'];
//        $getVID="select id from ams.suppliers where  name= '$vName'";
//        $vId=DB::select($getVID)[0]->id;
        $bId=$_POST['bId'];

        $table=$_POST['table'];
        foreach ($table as $cell)
        {
            $quantity= $cell[5];
            $titleid=$cell[1];
            $b_id=$cell[8];
            $bid=explode(",",$b_id);
            foreach ($bid as $branch)
            {
               $query= "update opac.branch_order_batch_map set vendor_id=$vId where batch_id=$bId and branch_order_id=$branch";
               DB::update($query);
            }
            $po_id=$bId."_".$vId;
            $query="insert into memp.BATCH_VENDOR_PO (po_id,vendor_id,title_id,ordered_quantity,ordered_date,ORDERID) VALUES ($bId,$vId,$titleid,$quantity,sysdate,'$po_id')";
            DB::insert($query);
        }

        return "success";

    }


    public static function test($input)
    {
        $data="select * from ams.suppliers";
        $data= DB::select($data);
        $array=[];
        foreach ($data as $row)
        {
            array_push($array,$row);
        }


//        $data1=json_encode($data);
        $pdf = PDF::loadView('pdf',compact('array'));
        return $pdf->download('invoice.pdf');
    }


    public static function getPO()
    {
        $query="select po_id,title,batch_id ,vendor_id,ordered_quantity,ordered_date from memp.titles t join memp.batch_vendor_po bvp on bvp.title_id=t.id              
";
        $array=[];
        $response=DB::select($query);
        foreach ($response as $arr)
        {
            array_push($array,$arr);
        }


        return json_encode($array);
    }


}


