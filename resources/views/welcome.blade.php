

<!DOCTYPE html>
<html lang="en">
   <head>
      <title>Just Books</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <script
         src="https://code.jquery.com/jquery-3.1.1.js"
         ></script>  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
      <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
      <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
      <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.css">
      <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.js"></script>
      <script type="text/javascript" src="{!! asset('script/main.js') !!}"></script>
      <script type="text/javascript" src="{!! asset('script/table.js') !!}"></script>
<style>
   .ui-tooltip-content{
      display: none;
   }

   .panel-heading{
      color: #fff;
      background-color: #2A3F54;
      border-color: #337ab7;
   }
   .navbar-inverse{
      background-color: #2A3F54;
   }
   @font-face {
      font-family: product;
      src: url('{{ public_path('fonts/Product sans.ttf') }}');
   }

   html *{
      font-family: product;

   }
   body{
      background-color: #EDEDED;

   }
   #dev-table td {text-align:center; vertical-align:middle;}
   #dev-table th {text-align:center; vertical-align:middle;}



</style>
   </head>
   <script>

      
   </script>
   <body>
   <nav class="navbar navbar-inverse">
      <div class="container-fluid">
         <div class="navbar-header">
            <a class="brand" href="#">
               <!-- UNCOMMENT THE CSS VALUES TO TEST OTHER DIMENTIONS -->
               <!-- <img src="http://placehold.it/150x80&text=Logo" alt=""> -->
               <img style= "width: 171px;" src="{{URL::asset('/img/jb.png')}}" alt="">
            </a>
         </div>
         &nbsp;
         <ul class="nav navbar-nav" style="margin-left: 3%;">
            <li class="active"><a href="/">Batches</a></li>
            <li ><a href="materials">Material PO</a></li>
            <li><a href="vendors">Vendors</a></li>
            <li><a href="getPO">Purchase Orders</a></li>
            <li ><a href="scanner">GR</a></li>
            <li ><a href="reports">Reports</a></li>
             <li ><a href="catalogue">Catalogue</a></li>
            <li ><a href="giftCatalogue">Dontaion/Gift</a></li>
            <li ><a href="branchInvoice">Branch Invoice</a></li>

         </ul>
         <ul class="nav navbar-nav navbar-right">
            <li><a href="logout"><span class="glyphicon glyphicon-log-in"></span> Log Out</a></li>
         </ul>
      </div>
   </nav>
      <div class="container">

         @if (session('status'))

            <div class="alert alert-success">
               <strong>Success!</strong>  {{ session('status') }}
            </div>
         @endif

             <div class="spinner"  style='display: none'>
                 <div class="double-bounce1"></div>
                 <div class="double-bounce2"></div>
             </div>
         <div id="data"></div>
         <div class="row">
            <div class="col-md-12" id="create" style="padding-bottom: 15px">
               <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#Batch">Create Batch</button>
            </div>


            <!-- Modal -->
            <div id="Batch" class="modal fade" role="dialog">
               <div class="modal-dialog">
                  <!-- Modal content-->
                  <div class="modal-content">
                     <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Create a new batch</h4>
                     </div>
                     <div class="modal-body">
                        <form role="form" method="post" action="{{ action('HomeController@addBatch') }}"
                              enctype="multipart/form-data"
                              accept-charset="UTF-8">
                           <div class="form-group">
                              <label for="sel1">Select Procurement Type:</label>
                              <select style="background-color: lightgrey" class="form-control" id="sel1" name="select" onchange="triggered()">
                                 <option value="1">New Arrivals</option>
                                 <option value="2">New Branch</option>
                                 <option value="3">IBT</option>
                                 <option value="4">Warehouse Procurement</option>
                                 <option value="5">Donation/Gift</option>
                                 <option value="6">Branch Procurement</option>


                              </select>
                           </div>
                           <div class="form-group">
                              <label for="name">Batch Name:</label>
                              <input type="text" class="form-control" id="name" name="name" required maxlength="20">
                           </div>
                           <div class="form-group">
                              <label for="from" >From Date:</label>
                              <input type="date" class="form-control" id="datepicker_start" name="start" required >
                           </div>
                           <div class="form-group">
                              <label for="from" >End Date:</label>
                              <input type="date" class="form-control" id="datepicker_end" name="end" required >
                           </div>

                            <div class="form-group">
                                <label for="name">Upload File:</label>
                                <input id="fileSelect" type="file" name="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                            </div>
                           <div class="form-group">
                              <label for="comment">Description:</label>
                              <textarea class="form-control" rows="5" id="description" maxlength="255" name="description"></textarea>
                           </div>
                           <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                     </div>
                  </div>
               </div>
            </div>



            <div class="col-md-12">
               <div class="panel panel-primary">
                  <div class="panel-heading" style="background-color: #2A3F54">
                     <h3 class="panel-title">Total Batches</h3>
                     <div class="pull-right">
                        <span class="clickable filter" data-toggle="tooltip" title="Toggle table filter" data-container="body">
                        <i class="glyphicon glyphicon-filter"></i>
                        </span>
                     </div>
                  </div>
                  <div class="panel-body">
                     <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Filter Batches" />
                  </div>
                  <table class="table table-hover table-striped" id="dev-table">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>Batch Name</th>
                           <th>Description</th>
                           <th>From</th>
                           <th>To</th>
                           <th>Created_at</th>
                           <th>Procurement_type</th>
                           <th>Status</th>
                           <th></th>
                        </tr>
                     </thead>
                     <tbody class="populate">
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>

   </body>

   <style>.row{
      margin-top:40px;
      padding: 0 10px;
      }
      .clickable{
      cursor: pointer;   
      }
      .panel-heading div {
      margin-top: -18px;
      font-size: 15px;
      }
      .panel-heading div span{
      margin-left:5px;
      }
      .panel-body{
      display: none;
      }

      #dev-table_length{
         margin-left: 1%;
         padding-top: 1%;
      }
      #dev-table_info{
         margin-left: 1%;
         padding-top: 2%;
      }
      #dev-table_paginate{
         padding-top: 1%;
         padding-bottom: 1%;
         padding-right: 1%;
      }
   </style>
   <style>
   .spinner {
   /*width: 40px;*/
   /*height: 40px;*/

   /*position: relative;*/
   margin: 100px auto;
   position: fixed;
   z-index: 999;
   height: 2em;
   width: 2em;
   overflow: show;
   margin: auto;
   top: 0;
   left: 0;
   bottom: 0;
   right: 0;
   }
   .spinner:before {
   content: '';
   display: block;
   position: fixed;
   top: 0;
   left: 0;
   width: 100%;
   height: 100%;
   background-color: rgba(0,0,0,0.3);
   }

   .double-bounce1, .double-bounce2 {
   width: 100%;
   height: 100%;
   border-radius: 50%;
   background-color: #333;
   opacity: 0.6;
   position: absolute;
   top: 0;
   left: 0;

   -webkit-animation: sk-bounce 2.0s infinite ease-in-out;
   animation: sk-bounce 2.0s infinite ease-in-out;
   }

   .double-bounce2 {
   -webkit-animation-delay: -1.0s;
   animation-delay: -1.0s;
   }

   @-webkit-keyframes sk-bounce {
   0%, 100% { -webkit-transform: scale(0.0) }
   50% { -webkit-transform: scale(1.0) }
   }

   @keyframes sk-bounce {
   0%, 100% {
   transform: scale(0.0);
   -webkit-transform: scale(0.0);
   } 50% {
   transform: scale(1.0);
   -webkit-transform: scale(1.0);
   }
   }
   </style>
</html>

