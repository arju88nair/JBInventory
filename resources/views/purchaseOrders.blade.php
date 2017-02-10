

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Just Books</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="{!! asset('script/po.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('script/table.js') !!}"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        #dev-table td {text-align:center; vertical-align:middle;}
        th {width: 60px;}
        row{
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
        .ui-autocomplete {
            max-height: 100px;
            overflow-y: auto;
            /* prevent horizontal scrollbar */
            overflow-x: hidden;
        }
        /* IE 6 doesn't support max-height
         * we use height instead, but this forces the menu to always be this tall
         */
        * html .ui-autocomplete {
            height: 100px;
        }
        .card.text-center {
            margin: 0 auto;
            width: 30%;
            border: thin solid lightgray;
            margin-top: 10%;
            background-color: lightyellow;
            border-radius: 17px;
            height:300px;
        }
        #fileSelect {
            padding-left: 86px;
        }
        .tab {
            white-space: nowrap;
            overflow: hidden;
            width: 125px;
            height: 25px;
            padding-right: 30px;
        }
        #main_table {
            border-spacing: 10px;
        }
        .well-lg {

            width: 433px;
            margin-left: -10%;
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
        td {
            white-space: nowrap;
            text-wrap: normal;
            word-wrap: break-word;
        }
    </style>


</head>
<script>


</script>
<body>
<div>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Just Books</a>
        </div>
        <ul class="nav navbar-nav">
            <li ><a href="/">Batches</a></li>
            <li><a href="vendors">Vendors</a></li>
            <li class="active"><a href="purchaseOrders" >Purchase Orders</a></li>
        </ul>
    </div>
</nav>

<div class="container">
    <div class="spinner"  style='display: none'>
        <div class="double-bounce1"></div>
        <div class="double-bounce2"></div>
    </div>
    <div id="vendors" class="modal fade" role="dialog">
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
                                <th>Vendor Name</th>
                                <th>Quantity</th>
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


    <div class="container container-table">
        <div class="row vertical-center-row">
            <div class="text-center col-md-4 col-md-offset-4" >

                <p><b>Select a Batch</b></p>

                <input class="form-control tags" id="inputdefault" type="text" >
                <br>
                <div class="well well-lg" style="display:none">
                    <table id="main_table">
                        <tr>
                            <td class="tab"><b>Total Books </b></td>
                            <td class="tab"><b>Total PO Created </b></td>
                            <td class="tab"><b>Total PO Pending </b></td>
                        </tr>
                        <tr>
                            <td class="tab" id="Totalbooks"></td>
                            <td class="tab"  id="created">0</td>
                            <td class="tab" id="pending"></td>
                        </tr>
                    </table>


                </div>

            </div>
        </div>
    </div>
    <div class="text-center col-md-4 col-md-offset-4" >
    <label for="tags">Vendors: </label>
    <input id="tags" name="vendors">
    </div>


    <br><br>

    <div class="col-md-12" style="display: none;" id ="division">
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
                    <th>Book Name</th>
                    <th>Quantity</th>
                    <th>Quantity Required</th>
                    <th>Currency</th>
                    <th>Price</th>
                    <th>Save</th>
                    <th>Availability</th>

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

