

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
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" />
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="{!! asset('script/invoiceView.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('script/table.js') !!}"></script>
    <style>
        hr {
            display: block;
            height: 1px;
            border: 0;
            border-top: 1px solid lightgrey;
            margin: 1em 0;
            padding: 0;
        }
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

        .hideC {
            display: none;
        }
        .navbar-fixed-left {
            left: 0%;
            top: 8.8%;
            width: 171px;
            position: fixed;
            border-radius: 0;
            height: 100%;
        }

        .navbar-fixed-left .navbar-nav > li {
            float: none;  /* Cancel default li float: left */
            width: 171px;
            margin-bottom: 2%;
        }

        .navbar-fixed-left + .container {
            padding-left: 160px;
        }

        /* On using dropdown menu (To right shift popuped) */
        .navbar-fixed-left .navbar-nav > li > .dropdown-menu {
            margin-top: -50px;
            margin-left: 140px;
        }

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
            <li ><a href="/">Batches</a></li>
            <li class="active"><a href="materials">Material PO</a></li>
            <li><a href="vendors">Vendors</a></li>
            <li><a href="getPO">Purchase Orders</a></li>
            <li ><a href="scanner">GR</a></li>
            <li ><a href="catalogue">Catalogue</a></li>
            <li ><a href="giftCatalogue">Donation/Gift</a></li>
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
<div class="container">

     @if (session('status'))

        <div class="alert alert-success">
            <strong>Success!</strong>  {{ session('status') }}
        </div>
    @endif
        {{--<div class="navbar navbar-inverse navbar-fixed-left">--}}
            {{--<ul class="nav navbar-nav" style="margin-top: 42%">--}}
                {{--<li style="border-bottom: thin solid grey;border-top: thin solid grey" onclick="eightyTwenty()"><a href="javascript:void(0)">80:20 Invoice</a></li>--}}
                {{--<li style="border-bottom: thin solid grey"><a href="javascript:void(0)">Complementaries' Invoice</a></li>--}}

            {{--</ul>--}}
        {{--</div>--}}


    <div class="spinner"  style='display: none;z-index: 10000;'>
        <div class="double-bounce1"></div>
        <div class="double-bounce2"></div>
    </div>


         <div class="row" id="formField" style="background-color: white;
padding-bottom: 33px;
padding-top: 30px;
border: thin solid gray;">

             <form role="form" class="form-inline" method="post" action="{{ action('HomeController@eightyTwenty') }}" enctype="multipart/form-data"
                   accept-charset="UTF-8">

                 <div class="form-group">
                     <label for="sel1">Select Branch:</label>
                     <select class="form-control" id="selBran" style="width: 41%;" name="branch">
                         <?php foreach($data as $brand): ?>
                         <option value="<?php echo $brand->id; ?>"><?php echo $brand->branchname; ?>-<?php echo $brand->id; ?></option>

                     <?php endforeach ?>
                                              </select>
                 </div>
                 &nbsp;&nbsp;
                 <div class="form-group" style="margin-left: -15%">
                     <label for="from">From Date:</label>
                     <input type="date" class="form-control" id="datepicker_start" name="start" style="width: 38%;" required>
                 </div>
                 &nbsp;&nbsp;&nbsp;&nbsp;
                 <div class="form-group" >
                     <label for="from">To Date:</label>
                     <input type="date" class="form-control" id="datepicker_end" name="end" style="width: 38%;" required>
                 </div>

                 <button type="submit" class="btn btn-info"  onclick="invoice()">Submit</button>
             </form>


         </div>
         <br><br>
         <div class="container" style="display:none" id="summary">
             <div clas="row"><h4 id="statusDi"></h4>
                 <button type="button" class="btn btn-default" style="float:right;margin-top: -3%" onclick="pdfDownload()">Download as PDF</button>
             </div>
             <hr>
             <br>

         <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
             <div class="panel panel-primary">
                 <div class="panel-heading" style="background-color: #2A3F54">
                     <h3 class="panel-title">Procurement Summary</h3>
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
                     <thead style="background-color: #1ABB9C;">
                     <tr>
                         <th>Total books purchased</th>
                         <th>Total cost of procurement</th>
                         <th>Processing charges</th>

                     </tr>
                     </thead>
                     <tbody class="populate">
                     <tr style="background-color: white !important;text-align: center">
                         <td id="procQuantity">--</td>
                         <td id="procCost">--</td>
                         <td id="procCharge">--</td>

                     </tr>
                     </tbody>
                 </table>
             </div>
         </div>

         <hr />

             <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                 <div class="panel panel-primary">
                     <div class="panel-heading" style="background-color: #2A3F54">
                         <h3 class="panel-title">Donation Summary</h3>
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
                         <thead style="background-color: #1ABB9C;">
                         <tr>
                             <th>Total books purchased</th>
                             <th>Total cost of procurement</th>
                             <th>Processing charges</th>

                         </tr>
                         </thead>
                         <tbody class="populate">
                         <tr style="background-color: white !important;text-align: center">
                             <td id="giftQuantity">--</td>
                             <td id="giftCost">--</td>
                             <td id="giftCharge">--</td>

                         </tr>
                         </tbody>
                     </table>
                 </div>
             </div>
             <hr>
             <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                 <div class="panel panel-primary">
                     <div class="panel-heading" style="background-color: #2A3F54">
                         <h3 class="panel-title">Branch Procurement Summary</h3>
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
                         <thead style="background-color: #1ABB9C;">
                         <tr>
                             <th>Total books purchased</th>
                             <th>Total cost of procurement</th>
                             <th>Processing charges</th>

                         </tr>
                         </thead>
                         <tbody class="populate">
                         <tr style="background-color: white !important;text-align: center">
                             <td id="branchQuantity">--</td>
                             <td id="branchCost">--</td>
                             <td id="branchCharge">--</td>

                         </tr>
                         </tbody>
                     </table>
                 </div>
             </div>

             <hr>
             <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                 <div class="panel panel-primary">
                     <div class="panel-heading" style="background-color: #2A3F54">
                         <h3 class="panel-title">Branch Procurement Summary</h3>
                         <div class="pull-right">
                        <span class="clickable filter" data-toggle="tooltip" title="Toggle table filter" data-container="body">
                        <i class="glyphicon glyphicon-filter"></i>
                        </span>
                         </div>
                     </div>
                     <div class="panel-body">
                         <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Filter Batches" />
                     </div>
                     <table class="table table-hover table-striped" id="dev-tableData">
                         <thead style="background-color: #1ABB9C;">
                         <tr>
                             <th>Title Id</th>
                             <th>ISBN</th>
                             <th>Title</th>
                             <th>Book Number</th>
                             <th>Quantity</th>
                             <th>Total Amount</th>
                             <th>Created At</th>

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
<script>var dateObj=new Date();
    var year=dateObj.getFullYear();
    var month=dateObj.getMonth()+1;
    var date=dateObj.getDate();
    //        var today_date=date+'-'+month+'-'+year;
    if(date.toString().length <= 1) {
        date = '0' + date;
    }
    var today_date=year+'-'+month+'-'+date
    $( "#datepicker_start" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        onClose: function( selectedDate ) {
            $( "#to" ).datepicker( "option", "maxDate", "0" );
        }
    }).val();
    $( "#datepicker_end" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        onClose: function( selectedDate ) {
            $( "#from" ).datepicker( "option", "maxDate", "0" );
        }
    }).val();
</script>
</html>

