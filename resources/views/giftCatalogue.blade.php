<!DOCTYPE html>
<html lang="en">
<head>
    <title>Just Books</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.1.1.js" ></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="{!! asset('script/table.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('script/giftCatalogue.js') !!}"></script>

    <style>
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

        body {
            background-color: #EDEDED;

        }


    </style>
    <style>td {
            height: 50px;
            width: 50px;
        }

        #totalPO tr {
            line-height: 14px;
        }

        #totalPO td {
            text-align: center;
            vertical-align: middle;
        }

        #totalPO th {
            text-align: center;
            vertical-align: middle;
        }

        #totalPO tbody tr:hover, tr.selected {
            background-color: orange;
            cursor: pointer;
        }

        #totalPO td {
            text-align: center;
            vertical-align: middle;
        }

        #totalPO th {
            text-align: center;
            vertical-align: middle;
        }</style>
</head>
<script>


</script>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="brand" href="#">

                <img style="width: 171px;" src="{{URL::asset('/img/jb.png')}}" alt="">
            </a>
        </div>
        &nbsp;
        <ul class="nav navbar-nav" style="margin-left: 3%;">
            <li><a href="/">Batches</a></li>
            <li ><a href="materials">Material PO</a></li>

            <li><a href="vendors">Vendors</a></li>
            <li><a href="getPO">Purchase Orders</a></li>
            <li><a href="scanner">GR</a></li>
            <li><a href="catalogue">Catalogue</a></li>
            <li class="active"><a href="giftCatalogue">Donation/Gift</a></li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Others
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li ><a href="branchInvoice">Branch Invoice</a></li>
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
    <div class="alert alert-danger alert-dismissable" id="alertDiv" style="display: none">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Alert!</strong> ISBN Not Present.
    </div>

    <h3 style="text-align: center">Donation/Branch Procurement Catalogue</h3>
    <br><br>

    <div class="container" id="formField" style="display: none;">

        <form role="form" class="form-inline" method="post" action="{{ action('HomeController@processDateReport') }}" enctype="multipart/form-data"
              accept-charset="UTF-8">
            <div class="form-group">
                <label for="sel1">Select Procurement:</label>
                <select class="form-control" id="selProc" name="proc">
                    <option value="0">All</option>
                    <option value="1">New Arrivals</option>
                    <option value="2">New Branch</option>
                    <option value="3">IBT</option>
                    <option value="4">Warehouse Procurement</option>
                    <option value="5">Donation/Gift</option>
                    <option value="5">Branch Procurement</option>

                </select>
            </div>
            &nbsp;&nbsp;
            <div class="form-group">
                <label for="sel1">Select Branch:</label>
                <select class="form-control" id="selBran" style="width: 32%;" name="branch">
                    <option value="0">Any</option>

                </select>
            </div>
            &nbsp;&nbsp;
            <div class="form-group" style="margin-left: -22%;">
                <label for="from">From Date:</label>
                <input type="date" class="form-control" id="datepicker_start" name="start" required>
            </div>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <div class="form-group" style="margin-top: 2%;margin-left: 64%;">
                <label for="from">To Date:</label>
                <input type="date" class="form-control" id="datepicker_end" name="end" required>
            </div>

            <button type="submit" class="btn btn-info" style="margin-top: 2%;margin-left: 2%;">Submit</button>
        </form>


    </div>
    <br><br>

    <div class="spinner" style='display: none'>
        <div class="double-bounce1"></div>
        <div class="double-bounce2"></div>
    </div>
    <div id="POTotalTableDiv" style="display: none;margin-top: -4%;">
        <h2 style="text-align: center">Select a Batch</h2>
        <br><br>
        <div class="col-md-12" id="Totaldivision">
            <div class="panel panel-primary">
                <div class="panel-heading" style="background-color: #2A3F54;">
                    <h3 class="panel-title">Total Batches</h3>
                    <div class="pull-right">
                        <span class="clickable filter" data-toggle="tooltip" title="Toggle table filter"
                              data-container="body">
                        <i class="glyphicon glyphicon-filter"></i>
                        </span>
                    </div>
                </div>
                <div class="panel-body">
                    <input type="text" class="form-control" id="dev-table-filter" data-action="filter"
                           data-filters="#dev-table" placeholder="Filter Batches"/>
                </div>
                <table class="table table-hover table-striped" id="totalPO">
                    <thead>
                    <tr>

                        <th align="center">Batch Id</th>
                        <th align="center">Name</th>
                        <th align="center">Description</th>
                        <th align="center">Created At</th>
                        <th align="center">Procurement_type</th>
                        <th align="center">Status</th>
                        <th align="center"></th>
                        <th align="center"></th>
                        <th align="center"></th>


                    </tr>
                    </thead>
                    <tbody class="populatePOTable">
                    </tbody>
                </table>

            </div>
        </div>

        <br>


    </div>
    <br><br><br>
    <div class="catalogueDiv" style="display:none;margin-left: 18%;margin-top: -6%;">

        {{--<div class="row" style="margin-left: 10px;float: none; margin-left: auto;margin-right: auto;">--}}
            {{--<form id="form" action="/invoiceView/" role="form" method="POST" class="form-horizontal">--}}
                {{--<label for="from">From :&nbsp;&nbsp;</label>--}}
                {{--<input type="text" id="from" readonly required="required" name="from">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--}}
                {{--<label for="to">To :&nbsp;&nbsp;</label>--}}
                {{--<input type="text" id="to" readonly required="required" name="to">--}}
                {{--&nbsp;&nbsp;--}}
                {{--<input type="submit" class="btn btn-primary" value="submit" name="submit"/>--}}
            {{--</form>--}}
        {{--</div>--}}
        <div class="row" style="width: 103%;margin-left: 13%">
            <div class="col-xs-3" id="selectDiv" style="">
                <label for="sel1">Select Branch:</label>
                <select class="form-control" id="selectDrop">
                    <option value=0></option>
                </select>
            </div>

            <br><br>
            <br><br>
            <div class="col-xs-3" id="inputDiv" style="margin-left: 32%;margin-top: -8%;">
                <label for="sel1" style="margin-left: 17%;">Enter Invoice:</label>
                <input type="text" class="form-control" id="invoiceIn">
            </div>
        </div>
        <br><br><br>
<div class="row">
        <form id= "secondForm" class="form-inline " style="margin-left: -21%;margin-top: -8%;">
            <div class="form-group">
                <label for="sel1" style=>Select Vendor:</label>
                <select class="form-control" id="selectDropVendor" style="width: 132px">
                </select>
            </div>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <div class="form-group">
                <label for="email">ISBN:</label>
                <input type="text"style="width: 132px" class="form-control" id="isbn" name="isbn" autofocus required>
            </div>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <div class="form-group">
                <label for="pwd">Book Number:</label>
                <input type="text" style="width: 132px"class="form-control" id="num" name="bookNum" required>
            </div>
            &nbsp;
            <div class="form-group" style="display:none;" id="price">
                <label for="pwd"> Price:</label>
                <input type="text" style="width: 132px"class="form-control" id="priceIn" name="bookNum" value="0">
            </div>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <button type="submit" class="btn btn-default" onclick="appendTable()">Submit</button>
        </form>
</div>

        <br><br><br>
        <div id="summaryDiv" style="display: none;margin-left: -18%;margin-top: -4%">
            <h3 style="text-align: center;">Summary</h3>

            <br><br>
            <div class="col-md-12" id="Totaldivision">
                <div class="panel panel-primary">
                    <div class="panel-heading" style="background-color: #2A3F54;">
                        <h3 class="panel-title">Total POs</h3>
                        <div class="pull-right">
                        <span class="clickable filter" data-toggle="tooltip" title="Toggle table filter"
                              data-container="body">
                        <i class="glyphicon glyphicon-filter"></i>
                        </span>
                        </div>
                    </div>
                    <div class="panel-body">
                        <input type="text" class="form-control" id="dev-table-filter" data-action="filter"
                               data-filters="#dev-table" placeholder="Filter Batches"/>
                    </div>
                    <table class="table table-hover table-striped" id="summary">
                        <thead>
                        <tr>

                            <th align="center">Title ID</th>
                            <th align="center">ISBN</th>
                            <th align="center">Book Name</th>
                            <th align="center">Price</th>
                            <th align="center">Vendor ID</th>
                            <th align="center">Vendor </th>
                            <th align="center">Remove</th>
                            {{--<th align="center">Change Branch</th>--}}
                            {{--<th align="center"></th>--}}


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

            {{--<div class="col-xs-3" id="inputDiv" style="display: none">--}}
            {{--<label for="usr">Enter Invoice Number:</label>--}}
            {{--<input type="text" class="form-control"  id="invoiceInput" oninput="changeFunciton()"  required>--}}
            {{--</div>--}}
            {{--<div class="col-xs-3" id="inputDivAmout" style="display: none">--}}
            {{--<label for="usr">Enter Invoice Total Amount:</label>--}}
            {{--<input type="number" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control"  id="invoiceInputAmount" oninput="changeFuncitonInput()" required>--}}
            {{--</div>--}}
            <br>
            {{--<button type="button" class="btn btn-success btn-lg disabled" id="batchNext" style="float: right;" >Start Cataloging</button>--}}


        </div>

        <br><br><br>
        <br><br><br>


        <button type="button" class="btn btn-primary btn-lg" id="subBut" onclick="updateBranch()"
                style="display:none;float: right;margin-right: 2%;">Update
        </button>
    </div>


</div>
</div>
</div>
</div>
</body>

<style>.row {
        margin-top: 40px;
        padding: 0 10px;
    }

    .clickable {
        cursor: pointer;
    }

    .panel-heading div {
        margin-top: -18px;
        font-size: 15px;
    }

    .panel-heading div span {
        margin-left: 5px;
    }

    .panel-body {
        display: none;
    }

    #dev-table_length {
        margin-left: 1%;
        padding-top: 1%;
    }

    #dev-table_info {
        margin-left: 1%;
        padding-top: 2%;
    }

    #dev-table_paginate {
        padding-top: 1%;
        padding-bottom: 1%;
        padding-right: 1%;
    }

    .dataTables_filter {
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

    .hideC {
        display: none;
    }

    #totalPO_length {
        margin-left: 1%;
        padding-top: 1%;
    }

    #totalPO_info {
        margin-left: 1%;
        padding-top: 2%;
    }

    #totalPO_paginate {
        padding-top: 1%;
        padding-bottom: 1%;
        padding-right: 1%;
    }
</style>
</html>

