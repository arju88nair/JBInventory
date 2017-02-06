

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bootstrap Case</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script
            src="https://code.jquery.com/jquery-3.1.1.js"
    ></script>  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script>
        function getObjectFromJson(jsonObject,key)
        {
            getObjectFromJson(jsonObject,key,null);
        }


        function getObjectFromJson(jsonObject,key,defaultVal)
        {
            if(jsonObject.hasOwnProperty(key)){
                return jsonObject[key];
            }else
            {
                return (defaultVal==null ?"":defaultVal);
            }
        }




        $( document ).ready(function() {

            var val = '<?php echo $books ?>';
            val=JSON.parse(val);
            console.log(val);
            for(var i=0;i<val.length;i++){

                $('#dev-table tbody').append('<tr><td>'+getObjectFromJson(val[i],"title")+'</td><td>'
                    +getObjectFromJson(val[i],"isbn_13")+'</td><td>'
                    +getObjectFromJson(val[i],"copies")+'</td><td>'
                    +getObjectFromJson(val[i],"amount")+'</td><td>'+getObjectFromJson(val[i],"total_amount")+'</td><td><span class=\'glyphicon glyphicon-pencil\' data-toggle=\"modal\" data-target=\"#batch\" data-batch='+val[i].batch_id+' data-title='+val[i].title_id+'  ></span></td><td><span class=\'glyphicon glyphicon-trash\' onclick=\'deleteID(this,'+val[i].batch_id+','+val[i].title_id+');\'></span></td></tr>');
            }



            $('#batch').on('show.bs.modal', function (event) {

                var button = $(event.relatedTarget);
                // Button that triggered the modal
                var title = button.data('title');
                var batch = button.data('batch');

                $.ajax({
                    type: "POST",
                    url: "batchExpand",
                    data: {'batch_id': batch,'title_id':title},
                    async: true,
                    dataType: 'json',
                    enctype: 'multipart/form-data',
                    cache: false,
                    success: function (data) {
                        console.log(data);
                        var modal = $(this);
                        modal.find('.modal-title').text(data[0].title);
                        $('#expand-table tbody').empty();
                        populateBatch(data);



                    },
                    error: function (err) {

                        console.log(err.responseText);

                    }
                });
            });










        });


        function deleteID(row,batch_id,title_id)
        {

            var answer = confirm ("Are you sure you want to delete from the database?");
            if (answer)
            {

                $.ajax({
                    type: "POST",
                    url: "deleteBobm",
                    data: {'batch_id': batch_id,'title_id':title_id},
                    async: true,
                    dataType: 'json',
                    enctype: 'multipart/form-data',
                    cache: false,
                    success: function (data) {
                        console.log(data.status);
                        if(data.status==200) {
                            var i = row.parentNode.parentNode.rowIndex;
                            document.getElementById("dev-table").deleteRow(i);
                        }

                    },
                    error: function (err) {

                        console.log(err.responseText);

                    }
                });


            }

        }


        function populateBatch(jsonVal)
        {


            var jsonObj=jsonVal;
            //var htmlToAppend='';
            for(var i=0;i<jsonObj.length;i++){

                $('#expand-table tbody').append('<tr><td>'+getObjectFromJson(jsonObj[i],"title")+'</td><td>'+getObjectFromJson(jsonObj[i],"branchname")+'</td><td>'+getObjectFromJson(jsonObj[i],"branch_order_id")+'</td><td>'+getObjectFromJson(jsonObj[i],"amount")+'</td><td><span class=\'glyphicon glyphicon-trash\' onclick=\'deleteBID(this,'+jsonObj[i].branch_order_id+');\'></span></td></tr>');
            }


        }


        function deleteBID(row,BID)
        {

            var answer = confirm ("Are you sure you want to delete from the database?");
            if (answer)
            {

                $.ajax({
                    type: "POST",
                    url: "deleteBID",
                    data: {'id':BID },
                    async: true,
                    dataType: 'json',
                    enctype: 'multipart/form-data',
                    cache: false,
                    success: function (data) {
                        console.log(data.status);
                        if(data.status==200) {
                            var i = row.parentNode.parentNode.rowIndex;
                            document.getElementById("expand-table").deleteRow(i);
                        }

                    },
                    error: function (err) {

                        console.log(err.responseText);

                    }
                });


            }

        }





    </script>
</head>
<script></script>
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
    <div id="batch" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table" id="expand-table">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Branch Name</th>
                                    <th>Branch OrderID</th>
                                    <th>Cost</th>
                                    <th>Edit</th>

                                </tr>
                                </thead>
                                <tbody class="populateExpand">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="data"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Total Orders</h3>
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
                        <th>Title</th>
                        <th>ISBN</th>
                        <th>Quantity</th>
                        <th>Price/unit</th>
                        <th>Total amount</th>
                        <th>Edit</th>
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

