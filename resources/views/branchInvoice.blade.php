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
    <script type="text/javascript" src="{!! asset('script/table.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('script/catalogueInvoice.js') !!}"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <style>

        #invoice_length {
            margin-left: 1%;
            padding-top: 1%;
        }

        #invoice_info {
            margin-left: 1%;
            padding-top: 2%;
        }

        #invoice_paginate {
            padding-top: 1%;
            padding-bottom: 1%;
            padding-right: 1%;
        }

        .dataTables_filter {
            margin-top: 8px;
            margin-right: 1%;
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
        .panel-heading {
            color: #fff;
            background-color: #2A3F54;
            border-color: #337ab7;
        }

        .navbar-inverse {
            background-color: #2A3F54;
        }

        @font-face {
            font-family: product;
            src: url('{{ public_path('fonts/Product sans.ttf') }}');
        }

        html * {
            font-family: product;
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
            height: 300px;
        }

        #fileSelect {
            padding-left: 86px;
        }
    </style>


</head>
<script>


</script>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="brand" href="#">
                <!-- UNCOMMENT THE CSS VALUES TO TEST OTHER DIMENTIONS -->
                <!-- <img src="http://placehold.it/150x80&text=Logo" alt=""> -->
                <img style="width: 171px;" src="{{URL::asset('/img/jb.png')}}" alt="">
            </a>
        </div>
        &nbsp;
        <ul class="nav navbar-nav" style="margin-left: 3%;">
            <li><a href="/">Batches</a></li>
            <li ><a href="materials">Material PO</a></li>

            <li ><a href="vendors">Vendors</a></li>
            <li><a href="getPO">Purchase Orders</a></li>
            <li><a href="scanner">GR</a></li>
            <li><a href="reports">Reports</a></li>
            <li><a href="catalogue">Catalogue</a></li>
            <li ><a href="giftCatalogue">Dontaion/Gift</a></li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Others
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li class="active"><a href="branchInvoice">Branch Invoice</a></li>
                    <li><a href="invoice">Invoice</a></li>
                    <li ><a href="reports">Reports</a></li>
                    <li ><a href="debitCredit">Debit/Credit Notes</a></li>
                </ul>
            </li>



        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="logout"><span class="glyphicon glyphicon-log-in"></span> Log Out</a></li>
        </ul>
    </div>
</nav>
<div class="container" style="margin-top: 6%">


    @if (session('status'))

        <div class="alert alert-success">
            <strong>Success!</strong> {{ session('status') }}
        </div>
    @endif


    <div class="spinner" style='display: none'>
        <div class="double-bounce1"></div>
        <div class="double-bounce2"></div>
    </div>

        <div class="col-md-12" id="batchTable" style="display:none;">
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


        <form>
        <div class="col-xs-3" id="selectDiv" style="display: none">
            <label for="sel1">Select Branch:</label>
            <select class="form-control" id="sel1">

            </select>
            <br><br>
            <button type="submit" id = "but" class="btn btn-default" onclick="selectValues()"   >Select</button>


        </div>
        </form>


        <br><br>


        <div class="col-md-12" id="invoiceTable" style="display:none;">

            <button type="submit" id = "but" class="btn btn-default" onclick="cataloguePDF()"   >Generate PDF</button>

            <br><br>
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
                <table class="table table-hover table-striped" id="invoice">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>ISBN</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody class="populate">
                    </tbody>
                </table>
            </div>
        </div>

    {{--<d--}}

    {{--</div>--}}

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
        background-color: rgba(0, 0, 0, 0.3);
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
        0%, 100% {
            -webkit-transform: scale(0.0)
        }
        50% {
            -webkit-transform: scale(1.0)
        }
    }

    @keyframes sk-bounce {
        0%, 100% {
            transform: scale(0.0);
            -webkit-transform: scale(0.0);
        }
        50% {
            transform: scale(1.0);
            -webkit-transform: scale(1.0);
        }
    }
</style>
</html>

