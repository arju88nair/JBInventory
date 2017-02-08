

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

   </head>
   <script>

      
   </script>
   <body>
      <nav class="navbar navbar-inverse">
         <div class="container-fluid">
            <div class="navbar-header">
               <a class="navbar-brand" href="#">Just Books</a>
            </div>
            <ul class="nav navbar-nav">
               <li class="active"><a href="/">Batches</a></li>
               <li><a href="#">PO</a></li>
            </ul>
         </div>
      </nav>
      <div class="container">

         @if (session('status'))

            <div class="alert alert-success">
               <strong>Success!</strong>  {{ session('status') }}
            </div>
         @endif


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
                              <label for="name">Batch Name:</label>
                              <input type="text" class="form-control" id="name" name="name" required maxlength="20">
                           </div>
                           <div class="form-group">
                              <label for="from" >From Date:</label>
                              <input type="date" class="form-control" id="datepicker_start" name="start" required>
                           </div>
                           <div class="form-group">
                              <label for="from" >End Date:</label>
                              <input type="date" class="form-control" id="datepicker_end" name="end" required>
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
                  <div class="panel-heading">
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
</html>

