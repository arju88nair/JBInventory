

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
    <script type="text/javascript" src="{!! asset('script/vendor.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('script/table.js') !!}"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
            height:300px;
        }
        #fileSelect {
            padding-left: 86px;
        }
    </style>


</head>
<script>


</script>
<body>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Just Books</a>
        </div>
        <ul class="nav navbar-nav">
            <li ><a href="/">Batches</a></li>
            <li class="active"><a href="vendors">Vendors</a></li>
            <li><a href="getPO">Purchase Orders</a></li>
        </ul>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="logout"><span class="glyphicon glyphicon-log-in"></span> Log Out</a></li>
        </ul>
    </div>
</nav>
<div class="container">
    <div class="spinner"  style='display: none'>
        <div class="double-bounce1"></div>
        <div class="double-bounce2"></div>
    </div>


    <div class="card text-center">

        <div class="card-block">
            <h4 class="card-title">Add Vendors</h4>

            <form role="form" method="post" action="{{ action('HomeController@addVendor') }}"
                  enctype="multipart/form-data"
                  accept-charset="UTF-8">
                <div class="form-group">
                    <label for="tags">Vendors: </label>
                    <input id="tags" name="vendors">
                </div>
                <br>
                <div class="form-group">
                    <label for="name">Upload File:</label>
                    &nbsp;&nbsp;&nbsp; <input id="fileSelect" type="file" name="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required/>
                </div>
                <br><br>

                <button type="submit" class="btn btn-primary btn-lg">Submit</button>
            </form>

        </div>
        <br><br>
        <div class="card-footer text-muted">
           Please make sure to upload the sheet for the selected vendor
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

