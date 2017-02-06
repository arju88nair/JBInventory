

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
      <script>

         $(document).ready(
             function () {
                 $( "#datepicker_start" ).datepicker({
                     changeMonth: true,//this option for allowing user to select month
                     changeYear: true,//this option for allowing user to select from year range
                     dateFormat: 'yy-mm-dd'
                 });
                 $( "#datepicker_end" ).datepicker({
                     changeMonth: true,//this option for allowing user to select month
                     changeYear: true,//this option for allowing user to select from year range
                     dateFormat: 'yy-mm-dd'
                 });
             }
         );
      </script>
   </head>
   <script>
      $(document).ready(function () {
         $.ajax({ 
             type: 'GET',
             url: 'http://localhost:8081/getBatch',
             data: { get_param: 'value' }, 
             success: function (data) {
                 console.log(data);
               populateBatch(data);
               
             },
             error:function(err)
             {
               console.log(err);
             }
         });
      });
      
      function populateBatch(jsonVal)
      {


       var jsonObj=jsonVal;
       //var htmlToAppend='';
       for(var i=0;i<jsonObj.length;i++){
      
           $('#dev-table tbody').append('<tr><td>'+jsonObj[i].id+'</td><td>'+jsonObj[i].name+'</td><td>'+jsonObj[i].description+'</td><td>'+jsonObj[i].from_date+'</td><td>'+jsonObj[i].to_date+'</td><td>'+jsonObj[i].created_at+'</td><td>'+jsonObj[i].status+'</td><td><span class=\'glyphicon glyphicon-trash\' onclick=\'deleteID(this,'+jsonObj[i].id+');\'></span></td></tr>');
       }
       
      
      }



      function deleteID(row,id)
      {
          var answer = confirm ("Are you sure you want to delete from the database?");
          if (answer)
          {

              $.ajax({
                  type: "POST",
                  url: "deleteBatch",
                  data: {'id': id},
                  async: true,
                  dataType: 'json',
                  enctype: 'multipart/form-data',
                  cache: false,
                  success: function (data) {
                      console.log(data);
                      var i = row.parentNode.parentNode.rowIndex;
                      document.getElementById("dev-table").deleteRow(i);

                  },
                  error: function (err) {

                      console.log(err);

                  }
              });


      }

      }
      
      
   </script>
   <body>
      <nav class="navbar navbar-inverse">
         <div class="container-fluid">
            <div class="navbar-header">
               <a class="navbar-brand" href="#">Just Books</a>
            </div>
            <ul class="nav navbar-nav">
               <li class="active"><a href="#">Batches</a></li>
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
                     <h3 class="panel-title">Total Drivers</h3>
                     <div class="pull-right">
                        <span class="clickable filter" data-toggle="tooltip" title="Toggle table filter" data-container="body">
                        <i class="glyphicon glyphicon-filter"></i>
                        </span>
                     </div>
                  </div>
                  <div class="panel-body">
                     <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Filter Batches" />
                  </div>
                  <table class="table table-hover" id="dev-table">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>Batch Name</th>
                           <th>Description</th>
                           <th>From</th>
                           <th>To</th>
                           <th>Created_at</th>
                           <th>Status</th>
                           <th>Remove</th>
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
   <script>
      (function(){
          'use strict';
        var $ = jQuery;
        $.fn.extend({
          filterTable: function(){
            return this.each(function(){
              $(this).on('keyup', function(e){
                $('.filterTable_no_results').remove();
                var $this = $(this), 
                              search = $this.val().toLowerCase(), 
                              target = $this.attr('data-filters'), 
                              $target = $(target), 
                              $rows = $target.find('tbody tr');
                              
                if(search == '') {
                  $rows.show(); 
                } else {
                  $rows.each(function(){
                    var $this = $(this);
                    $this.text().toLowerCase().indexOf(search) === -1 ? $this.hide() : $this.show();
                  })
                  if($target.find('tbody tr:visible').size() === 0) {
                    var col_count = $target.find('tr').first().find('td').size();
                    var no_results = $('<tr class="filterTable_no_results"><td colspan="'+col_count+'">No results found</td></tr>')
                    $target.find('tbody').append(no_results);
                  }
                }
              });
            });
          }
        });
        $('[data-action="filter"]').filterTable();
      })(jQuery);
      
      $(function(){
          // attach table filter plugin to inputs
        $('[data-action="filter"]').filterTable();
        
        $('.container').on('click', '.panel-heading span.filter', function(e){
          var $this = $(this), 
            $panel = $this.parents('.panel');
          
          $panel.find('.panel-body').slideToggle();
          if($this.css('display') != 'none') {
            $panel.find('.panel-body input').focus();
          }
        });
        $('[data-toggle="tooltip"]').tooltip();
      })
   </script>
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
   </style>
</html>

