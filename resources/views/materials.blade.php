

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
    <script type="text/javascript" src="{!! asset('script/materials.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('script/table.js') !!}"></script>
    <style>
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
<div class="container">

    @if (session('status'))

        <div class="alert alert-success">
            <strong>Success!</strong>  {{ session('status') }}
        </div>
    @endif

    <div class="spinner"  style='display: none;z-index: 10000;'>
        <div class="double-bounce1"></div>
        <div class="double-bounce2"></div>
    </div>
    <div id="data"></div>
    <div class="row">
        <div class="col-md-12" id="create" style="padding-bottom: 15px">
            <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#Batch">Create Material PO</button>
        </div>
        <div id="material" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" id="title"></h4>
                    </div>
                    <div class="modal-body">
                        {{--<form role="form" method="post" action="{{ action('HomeController@updateMaterials') }}"--}}
                              {{--enctype="multipart/form-data"--}}
                              {{--accept-charset="UTF-8">--}}

                            {{--<div class="form-group">--}}
                                {{--<label for="name">Received Quantity Name:</label>--}}
                                {{--<input type="text" class="form-control" id="recQuan" name="recQuan" required maxlength="20">--}}
                            {{--</div>--}}
                            {{--<input type="hidden" class="form-control" id="id" name="id" required maxlength="20">--}}


                            {{--<button type="submit" class="btn btn-primary" >Submit</button>--}}
                        {{--</form>--}}
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
                                <table class="table table-hover table-striped" id="listView">
                                    <thead>
                                    <tr>

                                        <th align="center"class="hideC"></th>
                                        <th align="center">Item</th>
                                        <th align="center">Ordered</th>
                                        <th align="center">Recieved</th>
                                        <th class="hideC"></th>



                                    </tr>
                                    </thead>
                                    <tbody class="populateIsbnTable">
                                    </tbody>
                                </table>

                            </div>
                            <button  class="btn btn-primary"  onclick="updateEntries()">Update</button>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div id="Batch" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Create a new material PO</h4>
                    </div>
                    <div class="modal-body">
                        <form role="form" method="post" action="{{ action('HomeController@insertMaterials') }}"
                              enctype="multipart/form-data"
                              accept-charset="UTF-8" id="normal">

                            <div class="form-group">
                                <label for="name">Batch Name</label>
                                <input type="text" class="form-control" id="name" name="name" required maxlength="20">
                            </div>
                            <div class="form-group">
                                <label for="sel1">Select Material :</label>
                                <select style="background-color: lightgrey" class="form-control" id="type" name="select" >
                                    <option value="RFID Tags">RFID Tags</option>
                                    <option value="Membership Cardss">Membership Cards</option>
                                    <option value="Membership JB Lables (Paper Sticker)">Membership JB Lables (Paper Sticker)</option>
                                    <option value="Membership JB Lables (PVC Sticker)">Membership JB Lables (PVC Sticker)</option>
                                    <option value="Blank White colour Stickers">Blank White colour Stickers</option>
                                    <option value="Acrylic Stands">Acrylic Stands</option>
                                    <option value="JB Packing cartons">JB Packing cartons</option>
                                    <option value="Magazine Cards">Magazine Cards</option>
                                    <option value="Book Number Stickers">Book Number Stickers</option>
                                    <option value="ISBN Stickers">ISBN Stickers</option>
                                    <option value="Wax Ribbons">Wax Ribbons</option>
                                    <option value="Quality Stickers">Quality Stickers</option>
                                    <option value="Membership Registration Forms">Membership Registration Forms</option>
                                    <option value="Membership Plans">Membership Plans</option>
                                    <option value="Membership Fee Receipt">Membership Fee Receipt</option>
                                    <option value="Membership Closure Form">Membership Closure Form</option>
                                    <option value="Subscription Holiday Form">Subscription Holiday Form</option>
                                    <option value="Size 1 =  25.00 x17.50''">Size 1 =  25.00 x17.50''</option>
                                    <option value="Size 2 =  27.00 x 18.00''">Size 2 =  27.00 x 18.00''</option>
                                    <option value="Size 3 = 27.00 x 19.00 ">Size 3 = 27.00 x 19.00 "</option>
                                    <option value="Size 4 = 28.00 x 19.50 ">Size 4 = 28.00 x 19.50 "</option>
                                    <option value="Size 5 =  28.00 x 20.50">Size 5 =  28.00 x 20.50</option>
                                    <option value="Size 6 =  31.00 x 21.50">Size 6 =  31.00 x 21.50</option>
                                    <option value="Size 7 =  34.00 x 23.00">Size 7 =  34.00 x 23.00</option>
                                    <option value="Size 8 =  28.50 x 20.00">Size 8 =  28.50 x 20.00</option>
                                    <option value="Size 9 =  31.00 x 23.50">Size 9 =  31.00 x 23.50</option>
                                    <option value="Size 10 = 32.00 x 23.00">Size 10 = 32.00 x 23.00</option>
                                    <option value="Size 11=  37.00 x 24.00">Size 11=  37.00 x 24.00</option>
                                    <option value="Size 12 =  36.50 x 23.50">Size 12 =  36.50 x 23.50</option>
                                    <option value="Size 13 =  40.00 x 27.00">Size 13 =  40.00 x 27.00</option>
                                    <option value="Size 14 =  45.00 x 24.50">Size 14 =  45.00 x 24.50</option>
                                    <option value="Size 15 =  48.00 x 31.50">Size 15 =  48.00 x 31.50</option>
                                    <option value="RSize 16 =  34.00 x 14.50">Size 16 =  34.00 x 14.50</option>
                                    <option value="Size 17=  23.00 x 39.00">Size 17=  23.00 x 39.00s</option>
                                    <option value="Size 18 =  40.50 x 26.50">Size 18 =  40.50 x 26.50</option>
                                    <option value="Size 19 =  29.50 x 25.50">Size 19 =  29.50 x 25.50</option>
                                    <option value="Size 20 = 38.00 x 24.00">Size 20 = 38.00 x 24.00</option>
                                    <option value="Size 21 = 60.00 x 23.50">Size 21 = 60.00 x 23.50</option>
                                    <option value="Size 22 =  48.00 x 31.00">Size 22 =  48.00 x 31.00</option>
                                    <option value="Size 23 =  31.00 x 21.00">Size 23 =  31.00 x 21.00</option>
                                    <option value="Size 24 =  33.00 x 21.00 ">Size 24 =  33.00 x 21.00 </option>
                                    <option value="Size 25 = 48.00 x 31.50">Size 25 = 48.00 x 31.50</option>
                                    <option value="Size 26 = 44.00 x 31.50">Size 26 = 44.00 x 31.50</option>
                                    <option value="Size 27 = 26.00 x 17.50">Size 27 = 26.00 x 17.50</option>
                                    <option value="Size 28 = 36.00 x 21.00">Size 28 = 36.00 x 21.00</option>
                                    <option value="Size 29 = 63.00 x 24.00">Size 29 = 63.00 x 24.00</option>
                                    <option value="Size 30 = 51.00 x 32.00">Size 30 = 51.00 x 32.00</option>
                                    <option value="Size 31 = 55.00 x 32.00">Size 31 = 55.00 x 32.00</option>
                                    <option value="Size 32 =  23.00 x 17.50">Size 32 =  23.00 x 17.50</option>
                                    <option value="Size 33 =  27.50 x 18.00">Size 33 =  27.50 x 18.00</option>
                                    <option value="Size 34 = 30.00 x 21.5">Size 34 = 30.00 x 21.50</option>
                                    <option value="Size 35 = 30.00 x 20.00">Size 35 = 30.00 x 20.00</option>
                                    <option value="Size 36 = 27.00 x 20.00">Size 36 = 27.00 x 20.00</option>
                                    <option value="Size 37 = 45.00 x 28.50">Size 37 = 45.00 x 28.50</option>
                                    <option value="Size 38 = 34.00 x 12.00">Size 38 = 34.00 x 12.00</option>
                                    <option value="Size 39 = 35.00 x 13.50">Size 39 = 35.00 x 13.50</option>
                                    <option value="Size 40 = 37.00 x 26.50">Size 40 = 37.00 x 26.50</option>
                                    <option value="Size 41 = 53.00 x 37.00 ">Size 41 = 53.00 x 37.00 </option>
                                    <option value="Size 42 = 29.00 x 13.00">Size 42 = 29.00 x 13.00</option>
                                    <option value="Size 43 = 37.00 x 24.00">Size 43 = 37.00 x 24.00</option>
                                    <option value="Size 44 = 41.00 x 20.50">Size 44 = 41.00 x 20.50</option>
                                    <option value="Size 45 = 44.00 x14.00 ">Size 45 = 44.00 x14.00 </option>
                                    <option value="Size 46 = 39.00 x  14.00">Size 46 = 39.00 x  14.00</option>
                                    <option value="Size 47 = 56.00 x 35.00">Size 47 = 56.00 x 35.00</option>
                                    <option value="Size 48 = 59.00 x 35.00">Size 48 = 59.00 x 35.00</option>
                                    <option value="Size 49 = 43.00 x 18.00">Size 49 = 43.00 x 18.00</option>
                                    <option value="Size 50 = 29.00 x 13.00 ">Size 50 = 29.00 x 13.00 </option>
                                    <option value="Size 51 = 68.00 x 32.00">Size 51 = 68.00 x 32.00</option>
                                   

                                    {{--<option value="3">IBT</option>--}}
                                    {{--<option value="4">Warehouse Procurement</option>--}}
                                    {{--<option value="5">Donation/Gift</option>--}}
                                    {{--<option value="6">Branch Procurement</option>--}}


                                </select>
                            </div>
                            <div class="form-group">
                                <label for="name">Quantity:</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" required maxlength="20">
                            </div>
                            <div class="form-group">
                                <label for="name">Price Per Unit:</label>
                                <input type="number" class="form-control" id="priceFirst" name="price" required maxlength="20">
                            </div>
                            <div class="form-group">
                                <label for="name">Vendor Name:</label>
                                <select style="background-color: lightgrey" class="form-control" id="vendorDrop" name="vendor" >
                                    <?php foreach($vendor as $brand): ?>
                                <option value=<?php echo $brand->id; ?>><?php echo $brand->name; ?></option>

                                {{--<option value="3">IBT</option>--}}
                                {{--<option value="4">Warehouse Procurement</option>--}}
                                {{--<option value="5">Donation/Gift</option>--}}
                                {{--<option value="6">Branch Procurement</option>--}}

                                        <?php endforeach ?>
                                </select>                            </div>
                            <div class="form-group">
                                <label for="comment">Description:</label>
                                <textarea class="form-control" rows="5" id="description" maxlength="255" name="description"></textarea>
                            </div>
                            <button class="btn btn-primary" onclick="add()">add</button>
                            <button  class="btn btn-primary" onclick="submitData()">Submit</button>

                        </form>
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

                                        <th align="center">Item</th>
                                        <th align="center">Qauntity</th>
                                        <th align="center">Price</th>
                                        <th align="center">Remove</th>



                                    </tr>
                                    </thead>
                                    <tbody class="populateIsbnTable">
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



        <div class="col-md-12">
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
                        <th>PO Name</th>
                        <th>Description</th>
                        <th>Ordered Quantity</th>
                        <th>Received Quantity</th>
                        <th>Total</th>
                        <th>Vendor</th>
                        <th>Date</th>
                        <th>Delete</th>
                        <th>Edit</th>
                        <th>Download</th>


                    </tr>
                    </thead>
                    <tbody class="populate">
                    <?php foreach($data as $brand): ?>
                    <TR>
                        <TD><?php echo $brand->po_id; ?></TD>
                        <TD><?php echo $brand->name; ?></TD>
                        <TD><?php echo $brand->description; ?></TD>
                        <TD><?php echo $brand->ordered_quantity; ?></TD>
                        <TD><?php echo $brand->recieved_quantity; ?></TD>
                        <TD><?php echo $brand->total; ?></TD>
                        <TD><?php echo $brand->vendor; ?></TD>
                        <TD><?php echo $brand->created_at; ?></TD>
                        <TD><span style="cursor: pointer; cursor: hand; " class='glyphicon glyphicon-trash' onclick="deleteID('<?php echo $brand->po_id; ?>',this);"></span></TD>
                        <TD><span  class='glyphicon glyphicon-pencil' style="cursor: pointer; cursor: hand; " data-toggle="modal"  data-target="#material" data-material='<?php echo $brand->po_id; ?>' data-title='<?php echo $brand->name; ?>' data-received='<?php echo $brand->recieved_quantity; ?>'></span></TD>
                        <TD><a href="materialPDF?id=<?php echo $brand->po_id; ?>"><span class="glyphicon glyphicon-download-alt"></span></a></TD>

                    </TR>
                    <?php endforeach ?>                    </tbody>
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
</html>

