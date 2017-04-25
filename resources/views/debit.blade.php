

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
    <script type="text/javascript" src="{!! asset('script/debit.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('script/table.js') !!}"></script>

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
        #dev-table td {text-align:center; vertical-align:middle;}
        #dev-table th {text-align:center; vertical-align:middle;}
        #expand-table td {text-align:center; vertical-align:middle;}
        #expand-table th {text-align:center; vertical-align:middle;}



    </style>
</head>
<script></script>
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
            <li ><a href="materials">Material PO</a></li>
            <li><a href="vendors">Vendors</a></li>
            <li><a href="getPO">Purchase Orders</a></li>
            <li ><a href="scanner">GR</a></li>
            <li ><a href="catalogue">Catalogue</a></li>
            <li ><a href="giftCatalogue">Donation/Gift</a></li>
            <li class="dropdown active">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Others
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li ><a href="branchInvoice">Branch Invoice</a></li>
                    <li><a href="invoice">Invoice</a></li>
                    <li ><a href="reports">Reports</a></li>
                    <li class="active"><a href="debitCredit">Debit/Credit Notes</a></li>
                </ul>
            </li>


        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="logout"><span class="glyphicon glyphicon-log-in"></span> Log Out</a></li>
        </ul>
    </div>
</nav>
<div class="container" style="margin-top: 6%">
    <div class="spinner"  style='display: none'>
        <div class="double-bounce1"></div>
        <div class="double-bounce2"></div>
    </div>
<h3>Create debit/credit notes</h3>
    <div class="row" id="formField" style="background-color: white;padding-bottom: 33px;padding-top: 30px;border: thin solid gray;">


        <form role="form" class="form-inline" >
                     <div class="form-group">
                <label for="sel1">Select PO:</label>
                <select class="form-control" id="selProc" name="proc">
                    <?php foreach($data as $brand): ?>
                    <option value="<?php echo $brand->po_id; ?>"><?php echo $brand->po_id; ?></option>
                        <?php endforeach ?>



                </select>
            </div>
            &nbsp;&nbsp;
            {{--<div class="form-group" style="margin-left: 5%;">--}}
                {{--<label for="sel1">Select Reasons:</label>--}}
                {{--<select class="form-control" id="selBran" style="width: 44%;" name="branch">--}}
                    {{--<option value="Reason 1">Reason 1</option>--}}
                    {{--<option value="Reason 2">Reason 2</option>--}}
                    {{--<option value="Reason 3">Reason 3</option>--}}


                {{--</select>--}}
            {{--</div>--}}
            <div class="form-group" style="margin-left: 4%;">
                <label for="sel1">Select Type:</label>
                <select class="form-control" id="selBran" style="width: 49%;" name="branch">
                    <option value="Credit">Credit</option>
                    <option value="Debit">Debit</option>


                </select>
            </div>
            &nbsp;&nbsp;
            <div class="form-group" style="margin-left: 4%;">
                <label for="from">Invoice No.:</label>
                <input type="text" class="form-control" id="invoiceInput" name="invoice" style="width:48%;" required>
            </div>



            <button  class="btn btn-info btn-sm" style="margin-left: -2%;" onclick="submitButton()">Create</button>
            <button  class="btn btn-info btn-sm" style="margin-left: 2%;" onclick="searchButton()">Search</button>
            <button type="button" class="btn btn-default btn-sm"onclick="DownloadPO()" style="margin-left: 2%;>
                <span class="glyphicon glyphicon-download-alt"></span> Download
            </button>
        </form>


    </div>

<div class="container" id="dataDIv" style="display:none">



    <div class="row" id="formField2" style="background-color: white;padding-bottom: 33px;padding-top: 30px;border: thin solid gray;margin-left: -4%;">


        <form role="form" class="form-inline" >
            <div class="form-group" style="margin-left: 4%;">
                <label for="from">ISBN/TitleID</label>
                <input type="text" class="form-control" id="isbn" name="isbn" style="width:48%;" required>
            </div>

            <div class="form-group" style="margin-left: -5%;">
                <label for="from">Price:</label>
                <input type="text" class="form-control" id="price" name="price" style="width:48%;" required>
            </div>

            <div class="form-group" style="margin-left: -7%;">
                <label for="from">Quantity:</label>
                <input type="text" class="form-control" id="quantity" name="quantity" style="width:48%;" required>
            </div>


            &nbsp;&nbsp;
            <div class="form-group" style="margin-left: -8%;">
            <label for="sel1">Select Reasons:</label>
            <select class="form-control" id="selReason" style="width: 44%;" name="branch">
            <option value="Reason 1">Reason 1</option>
            <option value="Reason 2">Reason 2</option>
            <option value="Reason 3">Reason 3</option>


            </select>
            </div>


            <button  class="btn btn-info" style="margin-left: 2%;" onclick="appendTable()">Submit</button>
        </form>


    </div>

    <br>
    <div class="container" id="tableDov" style="display: none">
        <button type="button" id="pdfBUt" class="btn btn-info" style="float:right;display:none" onclick="pdfDOwn()">Download as PDF</button>
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
                <thead style="background-color: #1ABB9C;">
                <tr>

                    <th align="center">Title</th>
                    <th align="center">Quantity</th>
                    <th align="center">Price</th>
                    <th align="center">Reason</th>
                    <th align="center">Remove</th>


                </tr>
                </thead >
                <tbody class="populateIsbnTable" style="background-color: white !important;text-align: center">
                </tbody>
            </table>

        </div>
    </div>
        <br>

        <button  class="btn btn-info" style="margin-left: 2%;" onclick="uploadTable()">Submit</button>
        <br><br>
    </div>


</div>

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
    #ui-tooltip-0{
        display: none;
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
</html>

