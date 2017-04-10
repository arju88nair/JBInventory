<!DOCTYPE html>
<html lang="en">
<head>
    <title>Just Books</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script
            src="https://code.jquery.com/jquery-3.1.1.js"
    ></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="{!! asset('script/table.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('script/catalogue.js') !!}"></script>

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
            <li><a href="vendors">Vendors</a></li>
            <li><a href="getPO">Purchase Orders</a></li>
            <li><a href="scanner">GR</a></li>
            <li><a href="reports">Reports</a></li>
            <li class="active"><a href="catalogue">Catalogue</a></li>
            <li ><a href="giftCatalogue">Dontaion/Gift</a></li>
            <li ><a href="branchInvoice">Branch Invoice</a></li>



        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="logout"><span class="glyphicon glyphicon-log-in"></span> Log Out</a></li>
        </ul>
    </div>
</nav>
<div class="container" style="margin-top: 6%">

    <h3 style="text-align: center">Catalogue</h3>

    <div class="spinner" style='display: none'>
        <div class="double-bounce1"></div>
        <div class="double-bounce2"></div>
    </div>

    <div class="row" style="margin-left:-3%">
        <form class="form-inline" action="catReport" id="catReport">
            <div class="form-group">
                <label for="from">From :&nbsp;&nbsp;</label>
                <input type="text" id="from"  name="from" required>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </div>
            <div class="form-group">
                <label for="to">To :&nbsp;&nbsp;</label>
                <input type="text" id="to"  name="to" required>
            </div>
            <div class="form-group" style="margin-left: 2%">
                <label for="sel1">Select Batch:</label>

                <select class="form-control" name="batch" style="width: 118px;">
                    <option></option>
                    <?php foreach($batch as $brand): ?>
                    <option value="<?php echo $brand->id?>"><?php echo $brand->name?></option>
                    <?php endforeach ?>

                </select>
            </div>
            <div class="form-group" style="margin-left: 2%">
                <label for="sel1">Select Po:</label>
                <select class="form-control" name="po"  style="width: 118px;">
                    <option></option>
                    <?php foreach($po as $item): ?>
                    <option><?php echo $item->orderid?></option>
                    <?php endforeach ?>

                </select>

            </div>

            <button type="submit" class="btn btn-info" style="margin-left: 2%">Submit</button>
        </form>
    </div>


    <div id="POTotalTableDiv" style="display: none;">
        <h2 style="text-align: center">Select a PO</h2>
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
                <table class="table table-hover table-striped" id="totalPO">
                    <thead>
                    <tr>

                        <th align="center">Batch Id</th>
                        <th align="center">Name</th>
                        <th align="center">PO Order ID</th>
                        <th align="center">Vendor ID</th>
                        <th align="center">Vendor Name</th>
                        <th align="center">Qauntity</th>
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
    <div class="catalogueDiv" style="display:none;margin-left: 18%">
        <form class="form-inline" id="catForm" onsubmit="return false">
            <div class="form-group">
                <label for="email">ISBN:</label>
                <input type="text" class="form-control" id="isbn" name="isbn" autofocus>
            </div>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <div class="form-group">
                <label for="pwd">Book Number:</label>
                <input type="text" class="form-control" id="num" name="bookNum">
            </div>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <button type="submit" class="btn btn-default" onclick="appendTable()">Submit</button>
        </form>

        <br><br><br>
        <div id="summaryDiv" style="display: none;margin-left: -18%">
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

                            <th align="center">Title Name</th>
                            <th align="center">ISBN </th>
                            <th align="center">Batch ID</th>
                            <th align="center">Branch Order  ID</th>
                            <th align="center">Selected Branch</th>
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




        <button type="button" class="btn btn-primary btn-lg" id="subBut" onclick="updateBranch()" style="display:none;float: right;margin-right: 2%;">Update</button>
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
<script>var dateObj=new Date();
    var year=dateObj.getFullYear();
    var month=dateObj.getMonth()+1;
    var date=dateObj.getDate();
    //        var today_date=date+'-'+month+'-'+year;
    if(date.toString().length <= 1) {
        date = '0' + date;
    }
    var today_date=year+'-'+month+'-'+date
    $( "#from" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        onClose: function( selectedDate ) {
            $( "#to" ).datepicker( "option", "maxDate", "0" );
        }
    }).val();
    $( "#to" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        onClose: function( selectedDate ) {
            $( "#from" ).datepicker( "option", "maxDate", "0" );
        }
    }).val();
</script>

</html>

