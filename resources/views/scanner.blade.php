

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
    <script type="text/javascript" src="{!! asset('script/table.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('script/scanner.js') !!}"></script>

    <style>
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


    </style>
    <style>td
        {
            height: 50px;
            width:50px;
        }

        #totalPO td
        {
            text-align:center;
            vertical-align:middle;
        }
        #totalPO th
        {
            text-align:center;
            vertical-align:middle;
        }
        #totalPO tbody tr:hover, tr.selected {
            background-color: orange;
            cursor: pointer;
        }</style>
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
                <img style= "width: 171px;" src="{{URL::asset('/img/jb.png')}}" alt="">
            </a>
        </div>
        &nbsp;
        <ul class="nav navbar-nav" style="margin-left: 3%;">
            <li ><a href="/">Batches</a></li>
            <li><a href="vendors">Vendors</a></li>
            <li><a href="getPO">Purchase Orders</a></li>
            <li class="active"><a href="scanner">GR</a></li>
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
<div class="container" style="margin-top: 6%">

    <div class="alert alert-danger" style="display: none;">
        <strong>Notice!</strong> Scanned book is not present in the selected Purchase Order
    </div>

    <div class="spinner"  style='display: none'>
        <div class="double-bounce1"></div>
        <div class="double-bounce2"></div>
    </div>

    <br><br><br>
    <div id="POTotalTableDiv" style="display: none;">
        <h3 style="text-align: center">Select A PO</h3>
        <br><br>
        <div class="col-md-12" id ="Totaldivision" >
            <div class="panel panel-primary">
                <div class="panel-heading" style="background-color: #2A3F54;">
                    <h3 class="panel-title">Total POs</h3>
                    <div class="pull-right">
                        <span class="clickable filter" data-toggle="tooltip" title="Toggle table filter" data-container="body">
                        <i class="glyphicon glyphicon-filter"></i>
                        </span>
                    </div>
                </div>
                <div class="panel-body">
                    <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Filter Batches" />
                </div>
                <table class="table table-hover table-striped" id="totalPO">
                    <thead>
                    <tr>

                        <th align="center">Batch Id</th>
                        <th align="center">Name</th>
                        <th align="center">PO Order ID</th>
                        <th align="center">Vendor ID</th>
                        <th align="center">Qauntity</th>


                    </tr>
                    </thead>
                    <tbody class="populatePOTable">
                    </tbody>
                </table>

            </div>
        </div>
        {{--<form class="form-inline">--}}

            {{--<div class="col-xs-3 form-group" id="inputDiv" style="display: none">--}}
                {{--<label for="usr">Enter Invoice Number:</label>--}}
                {{--<input type="text" class="form-control"  id="invoiceInput" oninput="changeFunciton()"  required>--}}
            {{--</div>--}}
            {{--<div class="col-xs-3 form-group " id="inputDivAmout" style="display: none">--}}
                {{--<label for="usr">Enter Invoice Total Amount:</label>--}}
                {{--<input type="text" class="form-control"  id="invoiceInput" oninput="changeFunciton()" required>--}}
            {{--</div>--}}


            {{----}}
        {{--</form>--}}

        <div class="col-xs-3" id="inputDiv" style="display: none">
            <label for="usr">Enter Invoice Number:</label>
            <input type="text" class="form-control"  id="invoiceInput" oninput="changeFunciton()"  required>
        </div>
        <div class="col-xs-3" id="inputDivAmout" style="display: none">
            <label for="usr">Enter Invoice Total Amount:</label>
            <input type="number" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control"  id="invoiceInputAmount" oninput="changeFuncitonInput()" required>
        </div>
        <br>
        <button type="button" class="btn btn-success btn-lg disabled" id="batchNext" style="float: right;" >Start Scanning</button>



    </div>
    <div id="statusDiv" style="display: none;">
        <h3 style="text-align: center">Status Of The Uploaded Data</h3>
        <br><br>
        <div class="col-md-12" id ="Totaldivision" >
            <div class="panel panel-primary">
                <div class="panel-heading" >
                    <h3 class="panel-title">Summary</h3>
                    <div class="pull-right">
                        <span class="clickable filter" data-toggle="tooltip" title="Toggle table filter" data-container="body">
                        <i class="glyphicon glyphicon-filter"></i>
                        </span>
                    </div>
                </div>
                <div class="panel-body">
                    <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Filter Batches" />
                </div>
                <table class="table table-hover table-striped" id="status">
                    <thead>
                    <tr>

                        <th align="center">Name</th>
                        <th align="center">Invoice</th>
                        <th align="center">ISBN</th>
                        <th align="center">Ordered Quantity</th>
                        <th align="center">Qauntity Received</th>
                        <th align="center">Status</th>


                    </tr>
                    </thead>
                    <tbody class="populateIsbnTable">
                    </tbody>
                </table>

            </div>
        </div>




    </div>



    <div id="isbnTable" style="display: none;">
        <h3 style="text-align: center">Start Scanning</h3>
        <h4 style="text-align: center" id="count"></h4>
        <br><br>
        <div class="col-md-12" id ="Totaldivision" >
            <div class="panel panel-primary">
                <div class="panel-heading" >
                    <h3 class="panel-title">Total Books</h3>
                    <div class="pull-right">
                        <span class="clickable filter" data-toggle="tooltip" title="Toggle table filter" data-container="body">
                        <i class="glyphicon glyphicon-filter"></i>
                        </span>
                    </div>
                </div>
                <div class="panel-body">
                    <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Filter Batches" />
                </div>
                <table class="table table-hover table-striped" id="isbn">
                    <thead>
                    <tr>

                        <th align="center">Scaned ISBN</th>
                        <th align="center">Qauntity</th>
                        <th align="center">Remove</th>


                    </tr>
                    </thead>
                    <tbody class="populateIsbnTable">
                    </tbody>
                </table>

            </div>
        </div>



        <button type="button" class="btn btn-success btn-lg " id="saveGR" style="float: right;"  onclick="saveisbn()">Submit</button>

    </div>

</div>
<br><br><br><br>
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
    .dataTables_filter{
        margin-top: 8px;
        margin-right: 1%;
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
    .hideC {
        display: none;
    }
    #totalPO_length{
        margin-left: 1%;
        padding-top: 1%;
    }
    #totalPO_info{
        margin-left: 1%;
        padding-top: 2%;
    }
    #totalPO_paginate{
        padding-top: 1%;
        padding-bottom: 1%;
        padding-right: 1%;
    }
</style>
</html>

