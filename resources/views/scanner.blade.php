<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            var isbn=["9789385854118","X000KFQE99","521256911457","QB561B3001398"];
            var len=$('#Remaining_Books').text(isbn.length);
            var scanned=0;

//            localStorage.setItem("isbnArray",isbn);
//            isbn=localStorage.getItem("isbnArray");
            console.log(typeof isbn);

            var pressed = false;
            var chars = [];
            $(window).keypress(function(e) {
                //if (e.which >= 48 && e.which <= 57) {
                    chars.push(String.fromCharCode(e.which));
                //}
                console.log(e.which + ":" + chars.join("|"));
                if (pressed == false) {
                    setTimeout(function(){
                        if (chars.length >= 10) {
                            var barcode = chars.join("");
                            console.log("Barcode Scanned: " + barcode);
                            // assign value to some input (or do whatever you want)
                            $("#barcode").text(barcode);
                            var index=arraySearch(isbn,barcode);
                            if (index > -1) {
                                isbn.splice(index, 1);
                            }
                            $('#Remaining_Books').text(isbn.length);
                            console.log(isbn);
                            scanned++;
                            $("#scanned").text(scanned);
                        }
                        chars = [];
                        pressed = false;
                    },500);
                }
                pressed = true;
            });
        });
        $("#barcode").keypress(function(e){
            if ( e.which === 13 ) {
                console.log("Prevent form submit.");
                e.preventDefault();
            }
        });
        function arraySearch(arr,val) {
            for (var i=0; i<arr.length; i++)
                if (arr[i] == String(val))
                    return i;

            return false;
        }


    </script>
</head>
<body>

<div class="container">
    <div class="text-center col-md-4 col-md-offset-4" >

        <form>
            <div class="form-group">
                <label for="email">Enter PO:</label>
                <input class="form-control tags" id="inputdefault" type="text" name="poTag">
            </div>

            <button type="submit" class="btn btn-default">Submit</button>
        </form>
        <br>

        <div class="well well-lg" style="display:block">
            <table id="main_table">
                <tr>
                    <td class="tab"><b>Remainig Books </b></td>
                    <td class="tab"><b>Scanned Books</b></td>
                    <td class="tab"><b>Current Scanned</b></td>

                </tr>
                <tr>
                    <td class="tab" id="Remaining_Books"></td>
                    <td class="tab"  id="scanned"></td>
                    <td class="tab"  id="barcode">0</td>
                </tr>
            </table>


        </div>
<span id="barcode"></span>


    </div>
</div>

</div>

</body>
<style>.tab {
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
    }</style>
</html>
