$(document).ready(function () {


    localStorage.removeItem("invalidISBN");
    $('table').on('click', 'tr a', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });
    branchCall();
    var table;
    var new_branch_id = "";
    $('#secondForm').submit(false);
    $(".spinner").show();
    $.ajax({

        type: "GET",
        url: "getGiftBatch",
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function (data) {
            console.log(data);
            //     test(data);
            // autocompleted(data);
            populatePO(data);



        },
        error: function (err) {

            console.log(err.responseText);

        }
    });
    $('body').delegate('#totalPO tbody tr', "click", function () {
        $(this).addClass("selected").siblings().removeClass("selected");
        var tr = this.closest('tr');


    });


    var pressed = false;
    var chars = [];

    $(window).keypress(function (e) {
        var code = e.keyCode || e.which;
        if(code == 13) { //Enter keycode
       $("#num").focus();
       var islength=$("#isbn").val();
       var numlength=$("#num").val()
       if(islength.length === 0 || numlength.length === 0)
       {
           return false;

       }
        }

        //if (e.which >= 48 && e.which <= 57) {
        chars.push(String.fromCharCode(e.which));
        //}
        // console.log(e.which + ":" + chars.join("|"));
        if (pressed == false) {
            setTimeout(function () {
                if (chars.length >= 20) {
                    var barcode = chars.join("");
                    console.log("Barcode Scanned: " + barcode);

                }
                chars = [];
                pressed = false;
            }, 500);
        }
        pressed = true;
    });


});


function getObjectFromJson(jsonObject, key) {
    getObjectFromJson(jsonObject, key, null);
}


function getObjectFromJson(jsonObject, key, defaultVal) // Validating the existence of key
{
    if (jsonObject.hasOwnProperty(key)) {
        return jsonObject[key];
    } else {
        return (defaultVal == null ? "" : defaultVal);
    }
}

function populatePO(val) {
    for (var i = 0; i < val.length; i++) {

        var j = i + 1
        $('#totalPO tbody').append('<tr><td class="batch" >' + getObjectFromJson(val[i], "id") + '</td><td>'
            + getObjectFromJson(val[i], "name") + '</td><td >' + getObjectFromJson(val[i], "description") + '</td><td ">' + getObjectFromJson(val[i], "created_at") + '</td><td ">' + getObjectFromJson(val[i], "pname") + '</td><td>'
            + getObjectFromJson(val[i], "status") + '</td><td><span id="' + getObjectFromJson(val[i], "id") + '" class=\"glyphicon glyphicon-circle-arrow-right\" style="font-size: 16px;cursor: pointer; cursor: hand; " onclick="POClick(\'' + val[i].id + '\',\'' + val[i].procurement_type_id + '\')"></span></td><td><button style="border: 2px solid lightblue;background-color: white;" type="button" class="btn btn-outline-primary" onclick="viewISBN(\'' + val[i].id + '\')">Validate</button></td><td><button style="border: 2px solid lightblue;background-color: white;" type="button" class="btn btn-outline-primary" onclick="generatecsv(\'' + val[i].id + '\')">Generate CSV</button></td></tr>');
    }
    table = $('#totalPO').DataTable({
        "order": [[ 0, "desc" ]],
        destroy: true,
        clear: true
    });

    // $(".dataTables_filter").hide();
    $('#POTotalTableDiv').show();
    $('#navig').show();

}

function POClick(id, pId) {

    localStorage.setItem('pID', pId);
    if (pId == 5) {
        $("#price").hide()
        $("#inputDiv").hide();

    }
    else {
        $("#price").show()

        $("#inputDiv").show();
    }

    localStorage.setItem('giftBatch', id)
    $(".spinner").show();

    $.ajax({

        type: "GET",
        url: "getGiftBranch",
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function (data) {
            console.log(data);
            //     test(data);
            // autocompleted(data);
            var option = '';
            for (var i = 0; i < data.length; i++) {
                option += '<option value="' + data[i].id + '">' + data[i].branchname + '  -  ' + data[i].id + '</option>';
            }
            $("#selectDrop").append(option);

            $(".spinner").hide();
            $("#POTotalTableDiv").hide();
            $("#formField").hide();

            $(".catalogueDiv").show();


        },
        error: function (err) {

            console.log(err.responseText);

        }
    });


}


function appendTable() {
    var isbn = $("#isbn").val();
    var bookNum = $("#num").val();
    var price = $("#priceIn").val();
    $("#subBut").show();
    $("#summaryDiv").show();
    $("#isbn").focus();

    validateISBN(isbn,bookNum,price);

}


function updateBranch() {
    if($("#selectDrop").val() == 0 || $("#selectDrop").val() == "0")
    {
        alert("Please select a branch");
        return false;
    }
    $(".spinner").show();

    var myTableArray = [];
    $("#summary tr").each(function () {
        var arrayOfThisRow = [];
        var tableData = $(this).find('td');
        if (tableData.length > 0) {
            tableData.each(function () {
                arrayOfThisRow.push($(this).text());
            });
            myTableArray.push(arrayOfThisRow);
        }
    });
    console.log(myTableArray);
    if(myTableArray.length === 0)
    {
        alert("Table is empty");
        $(".spinner").hide();
        return false;
    }
    var pID = localStorage.getItem("pID");
    if (pID == 6) {
        var invoice = $("#invoiceIn").val();
        var batch = localStorage.getItem('giftBatch');
        var isbn = $("#isbn").val();
        var bookNum = $("#num").val();
        var branch = $("#selectDrop").val();
        $.ajax({
            type: "POST",
            url: "insertGift",
            data: {'batch': batch, 'isbn': myTableArray, 'branch': branch, 'invoice': invoice, 'pType': 6},
            async: true,
            dataType: 'json',
            enctype: 'multipart/form-data',
            cache: false,
            success: function (res) {
                clean();

                $(".spinner").hide();

                alert("Successfully Assigned");
                console.log(res);


            },
            error: function (err) {
                clean();
                $(".spinner").hide();

                console.log(err.responseText);

            }
        });
    }
    else {
        var batch = localStorage.getItem('giftBatch');
        var isbn = $("#isbn").val();
        var bookNum = $("#num").val();
        var branch = $("#selectDrop").val();
        $.ajax({
            type: "POST",
            url: "insertGift",
            data: {'batch': batch, 'isbn': myTableArray, 'branch': branch, 'pType': 5},
            async: true,
            dataType: 'json',
            enctype: 'multipart/form-data',
            cache: false,
            success: function (res) {
                clean();
                $(".spinner").hide();

                alert("Successfully Assigned");
                console.log(res);


            },
            error: function (err) {
                clean();

                $(".spinner").hide();

                console.log(err.responseText);

            }
        });
    }

}


function clean() {
    $("#summary tbody").empty();
    $("#isbn").val('');
    $("#subBut").hide();
    $("#num").val('');


}

function viewISBN(id) {
    $(".spinner").show();
    $.ajax({

        type: "GET",
        url: "isbnValidate?batch=" + id,
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function (data) {
            $(".spinner").hide();
            console.log(data.length);
            if (data.length >= 1) {
                alert("There are  ISBN(s) containing no title ids")
                localStorage.setItem("invalidISBN",data);
                window.location.href="invalidISBNGift";

            }
            else {

                alert("Title ids are present for every ISBNs")
            }


        },
        error: function (err) {
            $(".spinner").hide();


            console.log(err.responseText);

        }
    });

}



function generatecsv(id)
{
    window.location.href="generatecsv?id="+id;
}


function validateISBN(id,booknum,price)
{

    $(".spinner").show();
    $.ajax({

        type: "GET",
        url: "indISBNVal?isbn=" + id,
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function (data) {
            $(".spinner").hide();
            if(data == 200 || data == '200') {
                $('#summary tbody').append('<tr><td >' + id + '</td><td >' + booknum + '</td><td >' + price + '</td><td><a  href="#">Remove</a></td></tr>');
                $("#alertDiv").hide();
            }
            else{
                $("#alertDiv").show();
            }
            $("#isbn").val("");
             $("#num").val("");



        },
        error: function (err) {
            $(".spinner").hide();
            if(err.responseText == 200 || err.responseText == '200') {
                $('#summary tbody').append('<tr><td >' + id + '</td><td >' + booknum + '</td><td >' + price + '</td><td><a  href="#">Remove</a></td></tr>');
                $("#alertDiv").hide();
            }
            else{
                $("#alertDiv").show();
            }
            $("#isbn").val("");
            $("#num").val("");



        }
    });

}


function branchCall()
{
    $.ajax({

        type: "GET",
        url: "getGiftBranch",
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function (data) {
            console.log(data);
            //     test(data);
            // autocompleted(data);
            var option = '';
            for (var i = 0; i < data.length; i++) {
                option += '<option  value="' + data[i].id + '">' + data[i].branchname + '  -  ' + data[i].id + '</option>';
            }
            $("#selBran").append(option);
            $("#formField").show();

            $(".spinner").hide();



        },
        error: function (err) {

            console.log(err.responseText);

        }
    });

}
$(document).ready(

    function () {
        $(".spinner").show();
        $( "#datepicker_start" ).datepicker({
            changeMonth: true,//this option for allowing user to select month
            changeYear: true,//this option for allowing user to select from year range
            dateFormat: 'yy-mm-dd'
        });
        $( "#datepicker_end" ).datepicker({
            changeMonth: true,//this option for allowing user to select month
            changeYear: true,//this option for allowing user to select from year range
            dateFormat: 'yy-mm-dd'
        });
    }
);