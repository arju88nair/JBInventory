<?php

namespace App\Models;

use DB;
use View;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
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
        $model->name=$name;
        $model->description=$description;
        $model->from_date=$startDate;
        $model->to_date=$endDate;
        $model->status="onGOing";
        $model->active=1;
        $isSaved=$model->save();

        if($isSaved){
            $id= $model['id'];
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


}
