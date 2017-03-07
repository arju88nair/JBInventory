<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use View;
use PDF;


class report extends Model
{
    //


    public static function summaryReport($input)
    {
        $query = " select id,name,no_of_pos,order_qnty,nvl(batch_id,0) batch_id,nvl(rec_qnty,0) rec_qnty from    
(select b.id ,b.name,nvl(count(distinct orderid),0) no_of_pos,nvl(sum(ordered_quantity),0) order_qnty
                  from opac.batch b left join memp.batch_vendor_po bvp on b.id=bvp.po_id where  procurement_type_id !=5 and procurement_type_id != 6
                  group by b.name,b.id) a
left join
(select batch_id,sum(quantity_recieved)rec_qnty from opac.batch_po_invoice_details group by batch_id) b
on a.id=b.batch_id";

        $response = DB::select($query);
        $array = [];
        foreach ($response as $item) {

            array_push($array, $item);
        }

        return $array;
    }


    public static function detailedReport()
    {
        $id = $_GET['id'];
        $query = "select b.id,b.name,nvl(invoice,'Not Available') invoice,nvl(bpid.isbn,'Not Available') isbn,nvl(quantity_recieved,0) quantity_recieved,mt.title title,mt.author_name author from
                    opac.batch b left join opac.batch_po_invoice_details bpid on b.id=bpid.batch_id join memp.titles mt on bpid.isbn=mt.isbn
                    where b.id=$id";


        $response = DB::select($query);
        $array = [];
        foreach ($response as $item) {

            array_push($array, $item);
        }
        $array = json_encode($array);

        return View::make('detailedSummary')->with('array', $array);


    }

    public static function getPoArray()
    {
        $id = $_GET['id'];
        $query = "select isbn from memp.batch_vendor_po where orderid='$id'";


        $response = DB::select($query);
        $array = [];
        foreach ($response as $item) {

            array_push($array, $item->isbn);
        }
        return $array;
    }


    public static function getInvoicePOs()
    {

        $id = $_GET['id'];
        $query = "select distinct invoice,invoice_amount,batch_id,po_id,created_at,total_price,quantity_recieved from opac.batch_po_invoice_details bpvd join (select orderid, sum(price) total_price from memp.batch_vendor_po where orderid='$id'
        group by orderid )a on bpvd.po_id = a.orderid";


        $response = DB::select($query);
        $array = [];
        foreach ($response as $item) {

            array_push($array, $item);
        }
        return $array;


    }


    public static function viewPOPDF()
    {
        $id = $_GET['id'];
        $query = "select distinct isbn,title,ordered_quantity quantity,nvl(author,'N/A') author,nvl(publisher,'N/A') publisher,nvl(price,0) price,discount,nvl(price-dis,0) net_price,nvl(ordered_quantity*(price-dis),0) total from
                   (select t.isbn,title,ordered_quantity,a.name author,p.name publisher,bvp.price,discount,(bvp.price*discount/100) dis from
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
        return $array;

    }


    public static function catalogueUpdate()
    {

        $vendor = $_GET['vendor'];
        $isbn = $_GET['isbn'];
        $bookNum = $_GET['bookNum'];
        $batch = $_GET['batch'];
        $updateSelectQuery = "select branch_id,branch_order_id,branchname from opac.branch_orders bo join opac.branch_order_batch_map bobm on bo.id=bobm.branch_order_id
            join memp.jb_titles jt on bo.title_id=jt.titleid join memp.jb_branches jb on branch_id=jb.id
            where isbn_13='$isbn' and bobm.batch_id=$batch and remaining_quantity!=0 order by branch_order_id asc";
        $updateSelectResponse = DB::select($updateSelectQuery);
        if (count($updateSelectResponse) == 0 || empty($updateSelectResponse)) {
            return array("message" => "ISBN NOT PRESENT", 'code' => 400);
        }
        $branch_order_id = $updateSelectResponse[0]->branch_order_id;
        $branch_idSelec = $updateSelectResponse[0]->branch_id;
        $branch_nameSelec= $updateSelectResponse[0]->branchname;


        $updateQuery = "update opac.branch_order_batch_map set remaining_quantity=REMAINING_QUANTITY-1 where BRANCH_ORDER_ID in($branch_order_id)";
        $updateResponse = DB::update($updateQuery);

        $isbnResponse = DB::select("select  title_id,title from opac.branch_orders bo join opac.branch_order_batch_map bobm on bo.id=bobm.branch_order_id
            join memp.jb_titles jt on bo.title_id=jt.titleid
            where isbn_13='$isbn' and bobm.batch_id=$batch  order by branch_order_id asc");
        if (count($isbnResponse) == 0 || empty($isbnResponse)) {
            return array("message" => "ISBN NOT PRESENT", 'code' => 500);
        }
        $title_id = $isbnResponse[0]->title_id;
        $ttile_name = $isbnResponse[0]->title;


        $insertQuery = "insert into memp.catalogue_details (isbn,title_id,batch_id,po_id,branch_order_id,book_num,created_at,branch_id) values('$isbn',$title_id,$batch,'$vendor',$branch_order_id,'$bookNum',sysdate,$branch_idSelec)";
        DB::insert($insertQuery);

        $branch_query = "select jb.id,jb.branchname from opac.branch_orders bo join opac.branch_order_batch_map bobm on bo.id=bobm.branch_order_id
                join memp.jb_titles jt on bo.title_id=jt.titleid join memp.jb_branches jb on bo.branch_id=jb.id
                where isbn_13='$isbn' and bobm.batch_id=$batch  order by branch_order_id asc";
        $branchResponse = DB::select($branch_query);
        $branch_id = [];
        $branch_name = [];
        foreach ($branchResponse as $item) {
            array_push($branch_id, $item->id);
            array_push($branch_name, $item->branchname);
        }


        return array("message" => "Successfully Added", 'code' => 200, 'isbn' => $isbn, 'batch' => $batch, 'branch_order' => $branch_order_id, 'branch_id' => $branch_id, 'branch_name' => $branch_name, 'title' => $ttile_name, 'selectedBranch' => $branch_idSelec,"selectedBranchName"=>$branch_nameSelec);


    }


    public static function catalogueNewUpdate()
    {
        $vendor = $_GET['order'];
        $isbn = $_GET['isbn'];
        $branchOrder = $_GET['branchOrder'];
        $batch = $_GET['batch'];
        $newBranch = $_GET['newBranch'];
        $bookNum = $_GET['bookNum'];


        $oldUpdateQuery = "update opac.branch_order_batch_map set remaining_quantity=REMAINING_QUANTITY+1 where BRANCH_ORDER_ID in($branchOrder)";

        DB::update($oldUpdateQuery);

        $isbnResponse = DB::select("select title_id,title from opac.branch_orders bo join opac.branch_order_batch_map bobm on bo.id=bobm.branch_order_id
            join memp.jb_titles jt on bo.title_id=jt.titleid
            where isbn_13='$isbn' and bobm.batch_id=$batch and remaining_quantity!=0 order by branch_order_id asc");

        if (count($isbnResponse) == 0 || empty($isbnResponse)) {
            return array("message" => "Already Added", 'code' => 500);
        }
        $title_id = $isbnResponse[0]->title_id;
        $ttile_name = $isbnResponse[0]->title;


        $newBranch_orderQuery = "select branch_order_id from opac.branch_orders bo join opac.branch_order_batch_map bobm on bo.id=bobm.branch_order_id
                join memp.jb_titles jt on bo.title_id=jt.titleid join memp.jb_branches jb on bo.branch_id=jb.id
                where isbn_13='$isbn' and bobm.batch_id=$batch and  jb.id =$newBranch and remaining_quantity!=0 order by branch_order_id asc";

        $newBranch_orderResponse = DB::select($newBranch_orderQuery);

        if (count($newBranch_orderResponse) == 0 || empty($newBranch_orderResponse)) {
            return array("message" => "Already Added", 'code' => 400);
        }
        $branch_order_id = $newBranch_orderResponse[0]->branch_order_id;

        $updateQuery = "update opac.branch_order_batch_map set remaining_quantity=REMAINING_QUANTITY-1 where BRANCH_ORDER_ID in($branch_order_id)";
        $updateResponse = DB::update($updateQuery);


        $deleteQuery = "delete from memp.catalogue_details where isbn = '$isbn' and title_id= $title_id and po_id='$vendor' and branch_order_id = $branchOrder";
        DB::delete($deleteQuery);

        $insertQuery = "insert into memp.catalogue_details (isbn,title_id,batch_id,po_id,branch_order_id,book_num,created_at,branch_id) values('$isbn',$title_id,$batch,'$vendor',$branch_order_id,'$bookNum',sysdate,$newBranch)";
        DB::insert($insertQuery);


        return "success";
    }


    public static function catalogueGetBatch()
    {

        $id = $_GET['id'];
        $query = "select distinct branch_id,branchname from opac.branch_orders bo join opac.branch_order_batch_map bobm on bo.id=bobm.branch_order_id join memp.jb_branches jb on branch_id=jb.id where bobm.batch_id=$id";
        $response = DB::select($query);
        $array = [];
        foreach ($response as $item) {
            array_push($array, $item);
        }

        return $array;

    }


    public static function catalogueTable()
    {


        $id = $_GET['batch'];
        $branch = $_GET['branch'];

        $query = "select cd.isbn,title,price,count(*) quantity,branchname,branchaddress,(price*count(*)) total,author_name from
            memp.catalogue_details cd join memp.jb_branches jb on cd.branch_id=jb.id
            join memp.titles t on cd.title_id=t.id
            join memp.batch_vendor_po bvp on (cd.po_id=bvp.orderid and cd.isbn=bvp.isbn) where cd.batch_id=$id and cd.branch_id =$branch
            group by cd.isbn,title,price ,branchname,branchaddress,author_name ";


        $response = DB::select($query);

        $array = [];
        foreach ($response as $item) {
            array_push($array, $item);
        }

        return $array;

    }


    public static function cataloguePDF()

    {
        $id = $_GET['batch'];
        $branch = $_GET['branch'];


        $query = "select cd.isbn,title,price,count(*) quantity,branchname,branchaddress,(price*count(*)) total from
            memp.catalogue_details cd join memp.jb_branches jb on cd.branch_id=jb.id
            join memp.titles t on cd.title_id=t.id
            join memp.batch_vendor_po bvp on (cd.po_id=bvp.orderid and cd.isbn=bvp.isbn) where cd.batch_id=$id and cd.branch_id =$branch
            group by cd.isbn,title,price ,branchname,branchaddress ";


        $response = DB::select($query);
if($response==[]||empty($response))
{
    return "No Items available";
}
        $array = [];
        foreach ($response as $item) {
            array_push($array, $item);
        }
        $branch_name = $array[0]->branchname;
        $branch_address = $array[0]->branchaddress;

        $myArray = explode(',', $branch_address);


        $date = date('Y-m-d');


        $sum = "select sum(total)  total,sum(quantity) sum from 
            (select cd.isbn,title,price,count(*) quantity,branchname,branchaddress,(price*count(*)) total from
            memp.catalogue_details cd join memp.jb_branches jb on cd.branch_id=jb.id
            join memp.titles t on cd.title_id=t.id
            join memp.batch_vendor_po bvp on (cd.po_id=bvp.orderid and cd.isbn=bvp.isbn) where cd.batch_id=$id and cd.branch_id =$branch
            group by cd.isbn,title,price ,branchname,branchaddress )
            ";


        $sumres = DB::select($sum);
        $sumres1 = $sumres[0]->total;
        $totalQuantity = $sumres[0]->sum;
        $processing = (int)$totalQuantity * 18;
        $discounted = ((int)$sumres1 * 35) / 100;
        $final = (int)$sumres1 - (int)$discounted;
        $finalProce = (int)$final + (int)$processing;
        $inWords = self::toWords((int)$finalProce);

//        return View::make('invoicePDF')->with('array',$array)->with('branch_name',$branch_name)->with('myArray',$myArray)->with('date',$date)->with('sumres1',$sumres1)->with('final',$final)->with('discounted',$discounted)->with('totalQuantity',$totalQuantity)->with('processing',$processing)->with('finalProce',$finalProce)->with('inWords',$inWords);
        $pdf = PDF::loadView('invoicePDF', compact('array', 'branch_name', 'myArray', 'date', 'sumres1', 'final', 'discounted', 'totalQuantity', 'processing', 'finalProce', 'inWords'));
        return $pdf->download('invoicePDF.pdf');

    }


    public static function getGiftBatch()
    {
        $query = "select b.id,b.name,pt.name pname,nvl(description,'N/A') description,to_char(created_at,'DD-MM-YYYY') created_at,status,procurement_type_id from opac.batch b join opac.procurement_type pt on pt.id 
=b.procurement_type_id where procurement_type_id=5 and active =1 or  procurement_type_id=6 order by CREATED_AT desc


";
        $response = DB::select($query);
        $array = [];
        foreach ($response as $item) {
            array_push($array, $item);
        }
        return $array;

    }


    public static function getGiftBranch()
    {
        $query = "select id,branchname from memp.jb_branches";
        $response = DB::select($query);
        $array = [];
        foreach ($response as $item) {
            array_push($array, $item);
        }
        return $array;

    }


    public static function insertGift()
    {

        $pType = $_POST['pType'];


        $batch = $_POST['batch'];
        $branch = $_POST['branch'];
        $isbns = $_POST['isbn'];
        if ($pType === '5') {


            $array = array();
            foreach ($isbns as $item) {
                array_push($array, $item);
            }
            $countArray = array();
            foreach ($isbns as $item) {
                array_push($countArray, $item[0]);
            }
            $vals = array_count_values($countArray);
            $isbn_array = array();

            foreach ($vals as $index => $val) {
                array_push($isbn_array, array("isbn" => $index, "count" => $val));
            }

            foreach ($array as $value) {
                $isbn = $value[0];
                $num = $value[1];
                $isbnQuery = "select id from memp.titles where isbn='$isbn' ";
                $responseISBN = DB::select($isbnQuery);
                if ($responseISBN == [] || empty($responseISBN)) {
                    $title_id = -1;
                } else {
                    $title_id = $responseISBN[0]->id;

                }

                $insertQuery = "insert into memp.catalogue_details (isbn,title_id,batch_id,po_id,branch_order_id,book_num,created_at,branch_id) 
            values ('$isbn',$title_id,$batch,'-1',-1,'$num',sysdate,$branch) ";

                $response = DB::insert($insertQuery);

            }
            foreach ($isbn_array as $item) {
                $isbn = $item['isbn'];
                $count = $item['count'];
                $poQuery = " insert into memp.batch_vendor_po (po_id,vendor_id,title_id,isbn,ordered_quantity,ordered_date,orderid,recieved_quantity,gr,price) values
                ($batch,$branch,$title_id,'$isbn',$count,sysdate,'-1',$count,0,'0')";
                $poResponse = DB::insert($poQuery);

            }
            return "success";
        } else {

            $invoice = $_POST['invoice'];

            $array = array();
            $invoiceAmount = 0;
            foreach ($isbns as $item) {
                $invoiceAmount += (int)$item[2];
                array_push($array, $item);
            }
            $countArray = array();
            foreach ($isbns as $item) {
                array_push($countArray, $item[0]);
            }
            $isbn_array = array();
            $vals = array_count_values($countArray);
            $isbn_array = array();

            foreach ($vals as $index => $val) {
                array_push($isbn_array, array("isbn" => $index, "count" => $val));
            }

            foreach ($array as $value) {
                $isbn = $value[0];
                $num = $value[1];
                $price = $value[2];
                $isbnQuery = "select id from memp.titles where isbn='$isbn' ";
                $responseISBN = DB::select($isbnQuery);
                if ($responseISBN == [] || empty($responseISBN)) {
                    $title_id = -1;
                } else {
                    $title_id = $responseISBN[0]->id;

                }


                $insertQuery = "insert into memp.catalogue_details (isbn,title_id,batch_id,po_id,branch_order_id,book_num,created_at,branch_id) 
                values ('$isbn',$title_id,$batch,'-1',-1,'$num',sysdate,$branch) ";

                $response = DB::insert($insertQuery);

            }
            foreach ($isbn_array as $item) {
                $isbn = $item['isbn'];
                $count = $item['count'];
                foreach ($array as $value) {
                    if (in_array($isbn, $value, true)) {
                        $price = $value[2];
                    } else {
                        $price = 0;
                    }

                    $price = (int)$price * $count;

                }
                $poQuery = " insert into memp.batch_vendor_po (po_id,vendor_id,title_id,isbn,ordered_quantity,ordered_date,orderid,recieved_quantity,gr,price) values
                ($batch,$branch,$title_id,'$isbn',$count,sysdate,'-1',$count,0,'$price')";
                $poResponse = DB::insert($poQuery);
                $query = "insert into opac.batch_po_invoice_details (batch_id,po_id,invoice,isbn,quantity_recieved,created_at,invoice_amount) values ($batch,'-1','$invoice','$isbn',$count,sysdate,'$invoiceAmount')";
                $result = DB::insert($query);
            }
            return "success";

        }

    }


    public static function toWords($number)
    {
        $no = round($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'one', '2' => 'two',
            '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
            '7' => 'seven', '8' => 'eight', '9' => 'nine',
            '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
            '13' => 'thirteen', '14' => 'fourteen',
            '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
            '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
            '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
            '60' => 'sixty', '70' => 'seventy',
            '80' => 'eighty', '90' => 'ninety');
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred
                    :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
            } else $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ?
            "." . $words[$point / 10] . " " .
            $words[$point = $point % 10] : '';
        $rupee = $result . "Rupees  " . $points . " Paise " . "only";
        if ($point != "" || !empty($points)) {
            return ucfirst($result . "Rupees  " . $points . " Paise " . "only");
        }
        return ucfirst($result . "Rupees  " . "only");
    }


    public static function getGiftBatches()
    {

        $query = "select b.id,b.name,nvl(description,'Not Available') description,
                from_date,to_date,created_at,procurement_type_id,status,p.name p_name from opac.batch b join opac.procurement_type p on b.procurement_type_id=p.id
                where active = 1 and procurement_type_id =5 or procurement_type_id = 6 order by created_at desc";


        $response = DB::select($query);
        $array = [];
        foreach ($response as $item) {

            array_push($array, $item);
        }

        return $array;

    }


    public static function isbnValidate()
    {

        $batch = $_GET['batch'];
        $query = "select distinct isbn from memp.catalogue_details cd where cd.batch_id=112 and title_id=-1";
        $response = DB::select($query);
        $array = [];
        foreach ($response as $item) {

            array_push($array, $item->isbn);
        }

        return $array;


    }


    public static function updateisbn()
    {
        $isbn = $_POST['isbn'];
        if (count($isbn) == 0 || empty($isbn)) {
            return response(array('code' => '1', "status" => "failure", 'statusCode' => 500, 'message' => 'No ISBNs Sent'))->header('Content-Type', 'application/json');
        }


        $array = array();
        foreach ($isbn as $item) {
            array_push($array, $item[0]);
        }


        $invalids = [];
        foreach ($array as $item) {

            $query = "select titleid id from memp.jb_titles where isbn_10 ='$item' or isbn_13='$item'";
            $responseISBN = DB::select($query);
            if ($responseISBN == [] || empty($responseISBN)) {
                array_push($invalids, $item);
            } else {
                $title_id = $responseISBN[0]->id;

            }


            $insertQueryCD = "insert into memp.catalogue_details (title_id) values($title_id where isbn='$item')";
            $cdResponse = DB::insert($insertQueryCD);
            $insertQuerybvp = "insert into memp.batch_vendor_po (title_id) values($title_id where isbn='$item')";
            $bvpResponse = DB::insert($insertQueryCD);


        }


        return "success";

    }


    public static function generatecsv()
    {
        $batch = $_GET['id'];
        $query = "select distinct isbn from memp.catalogue_details cd where cd.batch_id=$batch and title_id=-1";
        $response = DB::select($query);
        $array = [];
        foreach ($response as $item) {

            array_push($array, $item->isbn);
        }

        if (count($array) != 0 || !empty($array) || $array != []) {
            return View::make('invalidISBN')->with('invalidISBN', $array);

        }

        $contentQuery = "select isbn,title_id,title,count(*) quantity ,book_num,branchname from memp.catalogue_details cd join memp.jb_titles jt on jt.titleid=cd.title_id join memp.jb_branches jb  on cd.branch_id=jb.id where cd.batch_id=$batch

        group by isbn,title,title_id,book_num,branchname";
        $contentRes = DB::select($contentQuery);
        $contArray = [];
        foreach ($contentRes as $item) {

            array_push($contArray, $item);
        }

        return view::make('giftInvoice')->with('data', $contArray);


    }

}


