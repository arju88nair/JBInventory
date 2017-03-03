<?php

namespace App\Models;

use DB;
use View;
use PDF;
use Illuminate\Database\Eloquent\Model;

class PO extends Model
{
    //

    public static function getBatches($input)
    {

        $query = "select  id,name,to_char(from_date,'DD-MM-YYYY') from_date,to_char(to_date,'DD-MM-YYYY') to_date,status,to_char(created_at,'DD-MM-YYYY') created_at from opac.batch where active = 1";
        $response = DB::select(DB::raw($query));
        $array = [];
        foreach ($response as $row) {
            array_push($array, $row);
        }
        return json_encode($array);
    }

//

    public static function getPOBatch($input)
    {
        $id = $_GET['id'];

        $sql = "select isbn_13,title_id,title,sum(quantity) copies,amount,batch_id,sum(amount) total_amount from
                        opac.branch_order_batch_map bobm join opac.branch_orders bo on bobm.branch_order_id=bo.id
                        join jbprod.titles t on bo.title_id=t.titleid where bobm.batch_id=$id and bobm.active=1
                        group by title_id,amount,isbn_13,title,batch_id";
        $response = DB::select($sql);


        $a = 0;
        foreach ($response as $array) {
            $a += (int)$array->copies;
        }


        $sqlOrders = "select * from
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


        $response_query = DB::select($sqlOrders);
        return array("total" => $a, "response" => $response_query);
    }


    public static function getPOVendors($input)
    {


        $query = "select id,name,phone,city,discount,email from ams.suppliers";
        $response = DB::select($query);

        $array = [];
        foreach ($response as $row) {
            array_push($array, $row);
        }
        return json_encode($array);
    }


    public static function getPODetails($Input)
    {

        $vId = $_POST['vendor_name'];
        $id = $_POST['id'];
//        $getVID="select id from ams.suppliers where  name= '$vendor_name'";
//        $vId=DB::select($getVID)[0]->id;
        $query = " select  distinct title_id,a.isbn,quantity_required,title,branch_order_id,nvl(vendor_id,0) vendor_id,nvl(name,'N/A') name,nvl(quantity,0) quantity,nvl(currency,'N/A') currency,nvl(price,'N/A') price from
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
                (select vendor_id,s.name,isbn,quantity,nvl(currency,'N/A') currency,nvl(price,'N/A') price from ams.suppliers s join opac.vendor_stock_details vsd
                on s.id=vsd.vendor_id where vsd.vendor_id=$vId) b
                on a.isbn=b.isbn
                    ";

        return json_encode(DB::select($query));


    }


    public static function getIsbnVendors($input)

    {
        $isbn = $_POST['isbn'];
        $title = $_POST['title_id'];
        if($isbn == '' or empty($isbn)){
            $isbn=0;
        }
        $query = "select vendor_id,ISBN,currency,price,quantity,title from   opac.vendor_stock_details vsd   join   jbprod.titles jt on jt.isbn_13 = vsd.isbn where  titleid= $title or  isbn_13 = $isbn";
        $response = DB::select($query);
        return json_encode($response);

    }


    public static function savePoVendor($input)
    {
//        $name=$_POST['name'];
//        $id=$_p[]
    }


    public static function insertPO($input)
    {
        $vId = $_POST['vname'];

//        $getVID="select id from ams.suppliers where  name= '$vName'";
//        $vId=DB::select($getVID)[0]->id;
        $bId = $_POST['bid'];

        $table = $_POST['table'];

        foreach ($table as $cell) {
            $quantity = $cell[1];
            $titleid = $cell[0];
            $b_id = $cell[2];
            $isbn = $cell[3];
            $price = $cell[4]*$quantity;
            $bid = explode(",", $b_id);
            foreach ($bid as $branch) {
                $ibtrQUery = "update opac.ibtrs set state='InProgress',po_no=$bId where id=(select ibtr_id from opac.branch_orders where id=$branch)";
                DB::update($ibtrQUery);
                $query = "update opac.branch_order_batch_map set vendor_id=$vId where batch_id=$bId and branch_order_id=$branch";
                DB::update($query);
            }
            $po_id = $bId . "_" . $vId;
            if($quantity != 0)
            {

                $query = "insert into memp.BATCH_VENDOR_PO (po_id,vendor_id,title_id,ordered_quantity,ordered_date,ORDERID,isbn,price) VALUES ($bId,$vId,$titleid,$quantity,sysdate,'$po_id','$isbn',$price)";
                DB::insert($query);
            }

        }

        return response(array('code' => '0', "status" => "success", 'statusCode' => 200, 'message' => 'Successfully Saved'))->header('Content-Type', 'application/json');

    }


    public static function test($input)
    {
        $id = $_GET['id'];
        $vid = $_GET['vid'];
        $query = "select distinct isbn,title,ordered_quantity,nvl(author,'N/A') author,nvl(publisher,'N/A') publisher,nvl(price,0) price,discount,nvl(price-dis,0) net_price,nvl(ordered_quantity*(price-dis),0) total from
                    (select t.isbn,title,ordered_quantity,a.name author,p.name publisher,vsd.price,discount,(vsd.price*discount/100) dis from
                    memp.batch_vendor_po bvp join memp.titles t on bvp.title_id=t.id
                    left join ams.authors a on t.authorid=a.id
                    left join ams.publishers p on t.publisherid=p.id
                    left join opac.vendor_stock_details vsd on (t.isbn=vsd.isbn and vsd.vendor_id=bvp.vendor_id)
                    left join ams.suppliers s on s.id=bvp.vendor_id
                    where orderid='$id' ) a";
        $data = DB::select($query);
        $array = [];
        foreach ($data as $row) {
            array_push($array, $row);
        }
        $date = date('Y-m-d');

        $idQuery = "select name from ams.suppliers where id=$vid";
        $vendor = DB::select($idQuery);
        $vendor = $vendor[0]->name;

        $totalQuery="select sum(total) total from (select distinct isbn,title,ordered_quantity,nvl(author,'N/A') author,nvl(publisher,'N/A') publisher,nvl(price,0) price,discount,nvl(price-dis,0) net_price,nvl(ordered_quantity*(price-dis),0) total from
                    (select t.isbn,title,ordered_quantity,a.name author,p.name publisher,vsd.price,discount,(vsd.price*discount/100) dis from
                    memp.batch_vendor_po bvp join memp.titles t on bvp.title_id=t.id
                    left join ams.authors a on t.authorid=a.id
                    left join ams.publishers p on t.publisherid=p.id
                    left join opac.vendor_stock_details vsd on (t.isbn=vsd.isbn and vsd.vendor_id=bvp.vendor_id)
                    left join ams.suppliers s on s.id=bvp.vendor_id
                    where orderid='$id' ) a)";
        $totalRes=DB::select($totalQuery);
        $total=$totalRes[0]->total;

//        $data1=json_encode($data);
        $pdf = PDF::loadView('pdf', compact('array', 'vendor', 'id','date','total'));
        return $pdf->download('invoice.pdf');
    }


    public static function getPO()
    {
        $query = "select b.name,orderid,sum(ordered_quantity) quantity,vendor_id,po_id from opac.batch b join memp.batch_vendor_po bvp
    on b.id=bvp.po_id where orderid != '-1'  group by b.name,orderid,vendor_id,po_id     ";
        $array = [];
        $response = DB::select($query);
        foreach ($response as $arr) {
            array_push($array, $arr);
        }


        return json_encode($array);
    }


    public static function saveGR($input)
    {

        $batch = $_POST['batch'];
        $order = $_POST['order'];
        $isbn = $_POST['isbn'];
        $amount = $_POST['amount'];
        if (count($isbn) == 0 || empty($isbn)) {
            return response(array('code' => '1', "status" => "failure", 'statusCode' => 500, 'message' => 'No ISBNs Sent'))->header('Content-Type', 'application/json');
        }
        $vendor = $_POST['vendor'];
        $invoice = $_POST['invoice'];
        $array = array();
        foreach ($isbn as $item) {
            array_push($array, $item[0]);
        }
        $vals = array_count_values($array);
        $isbn_array = array();

        foreach ($vals as $index => $val) {
            array_push($isbn_array, array("isbn" => $index, "count" => $val));
        }
        foreach ($isbn_array as $item) {
            $isbn = $item['isbn'];
            $count = $item['count'];
            $query = "insert into opac.batch_po_invoice_details (batch_id,po_id,invoice,isbn,quantity_recieved,created_at,invoice_amount) values ($batch,'$order','$invoice','$isbn',$count,sysdate,'$amount')";
            $result = DB::insert($query);
        }

        if (!$result) {
            return response(array('code' => '1', "status" => "failure", 'statusCode' => 500, 'message' => 'Please Try Again'))->header('Content-Type', 'application/json');

        }

        $statusQuery = " 
                   select b.id,b.name,
nvl(invoice,'Invoice Not Available') 
invoice,nvl(bvp.isbn,'Not Recieved') 
isbn,ordered_quantity oq,nvl(quantity_recieved,0) qr,
                    case when ordered_quantity=nvl(quantity_recieved,0) then 'completed'
                    when ordered_quantity<nvl(quantity_recieved,0) then 'Recieved More'
                    when ordered_quantity>nvl(quantity_recieved,0) then 'Not completed'
                    end as final
                    from
                    opac.batch b left join memp.batch_vendor_po bvp on b.id=bvp.po_id
                    left join opac.batch_po_invoice_details bpid on (bvp.orderid=bpid.po_id and bvp.isbn=bpid.isbn)
                    where b.id=$batch";

        $response = DB::select($statusQuery);
        $statusArray = [];
        foreach ($response as $item) {
            array_push($statusArray, $item);

        }

        return $statusArray;
        return response(array('code' => '0', "status" => "success", 'statusCode' => 200, 'message' => 'Successfully Saved'))->header('Content-Type', 'application/json');


//        return json_encode($isbn.$batch.$order);
    }

}


