<?php

namespace App\Models;

use DB;
use View;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{

    protected static $_parallelImports = 50;
    protected static $_insertCache = array();
    //
    protected $table = "BATCH";

    public static function index($input)
    {



        $response =  self::where('active','=',1)->orderBy('created_at', 'desc')->get();
        if (empty($response) || count($response) == 0) {
            return response(array("message" => "No result found"));
        }

        return $response;


    }



    public static function addBatch($input)
    {

        $model=new self();
        $name=$_POST['name'];
        $startDate= $_POST['start'];
        $endDate=$_POST['end'];
        $description=$_POST['description'];
        $model->PROCUREMENT_TYPE_ID=$_POST['select'];
        $model->name=$name;
        $model->description=$description;
        $model->from_date=$startDate;
        $model->to_date=$endDate;
        $model->status="onGOing";
        $model->active=1;
        $isExcelUpload=false;

        $isSaved=$model->save();
        $id= $model['id'];

        if($_FILES['file']['name'])
        {
            $min_query="select max(id)+1 as id from opac.branch_orders";
            $min_row= DB::select($min_query);
            $min_row= $min_row[0]->id;
            $isExcelUpload=true;
            $excel=Excel::load($_FILES["file"]["tmp_name"], function($reader) {

            })->get();
//

            $counter=$min_row;
            $added = 0; $loopCounter=0;
            $sql = 'INSERT INTO "BRANCH_ORDERS" (id,title_id,order_type,quantity,branch_id,created_in,state,ibtr_id)  ';
            $values = '';

            foreach ($excel[0] as $inputItem) {
                $isbn=$inputItem["title"];
                $isbn_query="select titleid from jbprod.titles where isbn_10 = '$isbn' or isbn_13 = '$isbn' and rownum<=1";
                $resu=DB::select($isbn_query);
                $title_id= $resu[0]->titleid;

                $count=$inputItem["count"];

                $added ++;
                $ibtrsQuery="insert into opac.ibtrs (title_id,member_id,card_id,branch_id,created_at,order_type,state) values ($title_id,'16210','MWAREHOUSE1',901,sysdate,'BranchOrder','new' )";
                DB::insert($ibtrsQuery);

                $selectIbtrs="select max(id) id from opac.ibtrs where title_id= $title_id and member_id='16210' and card_id='MWAREHOUSE1' and branch_id =901 and order_type ='BranchOrder' and state = 'new'";
                $resSelect=DB::select($selectIbtrs);
                $ibId=$resSelect[0]->id;

                $query= "select $counter,$title_id,'F',$count,901,901,'new',$ibId from dual ";
                $counter++;
                if($values =='')
                {
                    $values =$query;
                }else
                {
                    $values = $values. " union all ". $query;
                }


                $loopCounter++;

                    $loopCounter=0;

//                    echo $sql . $values;
                    DB::insert($sql . $values);
                    $values="";

                 

            }
            if($values !="")
            {
                DB::raw($sql . $values);
                $values="";
            }

            $bobm_query="insert into OPAC.BRANCH_ORDER_BATCH_MAP (branch_order_id,active,batch_id,created_at,updated_at)
                          (select id,1,$id,sysdate,sysdate from opac.branch_orders where id>=$min_row and id<=$counter-1)";
             $result=DB::insert($bobm_query);
            if($result)
            {

                $batchQuery="select isbn_13,title_id,title,sum(quantity) copies,amount,batch_id,sum(amount) total_amount from
                        opac.branch_order_batch_map bobm join opac.branch_orders bo on bobm.branch_order_id=bo.id
                        join jbprod.titles t on bo.title_id=t.titleid where bobm.batch_id=$id and bobm.active=1
                        group by title_id,amount,isbn_13,title,batch_id";

                $batchResult=DB::select($batchQuery);
                $batchResult=json_encode($batchResult);

                return View::make('batch')->with('books',$batchResult)->with('batchID',$id);
            }
            return response(array('message'=>"Something went wrong,Try again"));
//            $max_query="select max(id) as id from opac.branch_orders";
//            $max_row= DB::select($max_query);
//            $max_row= $max_row[0]->id;
//            return $max_row;

        }





        if($isSaved){
            $id= $model['id'];

            if($isExcelUpload)
            {
                //call a function
            }

            $query="INSERT INTO opac.BRANCH_ORDER_BATCH_MAP (BATCH_ID,BRANCH_ORDER_ID,created_at) ( select $id,id,sysdate from branch_orders where trunc(created_at) >=to_date('$startDate','YYYY-MM-DD') and trunc(created_at) <= to_date('$endDate','YYYY-MM-DD') and state ='Assigned' )";
            $result= DB::insert($query);
            if($result)
            {

                $batchQuery="select isbn_13,title_id,title,sum(quantity) copies,amount,batch_id,sum(amount) total_amount from
                        opac.branch_order_batch_map bobm join opac.branch_orders bo on bobm.branch_order_id=bo.id
                        join jbprod.titles t on bo.title_id=t.titleid where bobm.batch_id=$id and bobm.active=1
                        group by title_id,amount,isbn_13,title,batch_id";

                $batchResult=DB::select($batchQuery);
                $batchResult=json_encode($batchResult);

                return View::make('batch')->with('books',$batchResult)->with('batchID',$id);
            }
            return response(array('message'=>"Something went wrong,Try again"));

        }

    }




    public static function deleteBatch($input)
    {
        $id=$_POST['id'];
        $entry=self::where('id','=',$id)->first();
        $entry->active=0;
        $isSaved=$entry->save();
        if($isSaved)
        {
            return array("message"=>"Added Successfully");
        }
        return response(array("message"=>"Try Again"));

    }



    public static function deleteBobm($input)
    {

        $batchId=$_POST['batch_id'];
        $title_id=$_POST['title_id'];
        $query=" update opac.branch_order_batch_map set active=0 where branch_order_id in
                (select bo.id from opac.branch_orders bo join opac.branch_order_batch_map bobm on bobm.branch_order_id=bo.id where title_id=$title_id
                 and batch_id=$batchId)";
        $result=DB::update($query);
        if($result)
        {

            return response(array('message'=>"Successfuly Deleted",'status'=>200));
        }
        return response(array("message"=>"Try again",'status'=>501));



    }


    public static function batchExpand($input)
    {
        $batchId=$_POST['batch_id'];
        $title_id=$_POST['title_id'];
        $query="select isbn_13,title_id,title,branch_order_id,branchname,quantity copies,amount from
                opac.branch_order_batch_map bobm join opac.branch_orders bo on bobm.branch_order_id=bo.id
                join jbprod.titles t on bo.title_id=t.titleid join memp.jb_branches jb on bo.branch_id=jb.id
                where bobm.batch_id=$batchId and title_id=$title_id and bobm.active=1
                group by title_id,amount,isbn_13,title,branchname,branch_order_id,quantity,amount";
        $result=DB::select($query);

        if($result)
        {
            return json_encode($result);
        }
        return response(array("message"=>"Try again",'status'=>501));

    }



        public static function deleteBID($Input)
        {
            $id=$_POST['id'];
            $query="update opac.branch_order_batch_map set active=0 where branch_order_id =$id";
            $result=DB::update($query);
            if($result)
            {
                return response(array('message'=>"Successfuly Deleted",'status'=>200));
            }
            return response(array("message"=>"Try again",'status'=>501));


        }





        public static function viewBatch($input)
        {
            $id=$_GET['batch'];
            $batchQuery="select isbn_13,title_id,title,sum(quantity) copies,amount,batch_id,sum(amount) total_amount from
                        opac.branch_order_batch_map bobm join opac.branch_orders bo on bobm.branch_order_id=bo.id
                        join jbprod.titles t on bo.title_id=t.titleid where bobm.batch_id=$id and bobm.active=1
                        group by title_id,amount,isbn_13,title,batch_id";


            $batchResult=DB::select($batchQuery);
            $batchResult=json_encode($batchResult);
            return View::make('batch')->with('books',$batchResult)->with('batchID',$id);
        }





//        public static function test($input)
//        {
//
//
//        }




}
