

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
    <script type="text/javascript" src="{!! asset('script/script.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('script/table.js') !!}"></script>
    <script>

        $( document ).ready(function() {

            var val = '<?php echo $books ?>';
            val=JSON.parse(val);
            console.log(val);
            populateMain(val);


        });

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
            <li class="active"><a href="/">Batches</a></li>
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
                                    <th>ISBN</th>
                                    <th>Copies</th>
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
<a href="/" class="btn btn-primary btn-lg" role="button" style="float: right;margin-right: 6.5%">Complete Batch</a>
</div>
</body>
<script>

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
     #batch .modal-dialog {
        /* new custom width */
        width: 58%;
        /* must be half of the width, minus scrollbar on the left (30px) */

    }
</style>
</html>

