$(document).ready(function () {
    $('table').on('click', 'tr a', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });
    var table;
    var new_branch_id = "";
    $('form').submit(false);
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
            + getObjectFromJson(val[i], "status") + '</td><td><span id="' + getObjectFromJson(val[i], "id") + '" class=\"glyphicon glyphicon-circle-arrow-right\" style="font-size: 16px;cursor: pointer; cursor: hand; " onclick="POClick(\'' + val[i].id + '\',\'' + val[i].procurement_type_id + '\')"></span></td></tr>');
    }
    table = $('#totalPO').DataTable({
        destroy: true,
        clear: true
    });

    // $(".dataTables_filter").hide();
    $(".spinner").hide();
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
                option += '<option value="' + data[i].id + '">' + data[i].branchname + '</option>';
            }
            $("#selectDrop").append(option);

            $(".spinner").hide();
            $("#POTotalTableDiv").hide();
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
    var price=$("#priceIn").val();
    $("#summaryDiv").show()
    $('#summary tbody').append('<tr><td >' + isbn + '</td><td >' + bookNum + '</td><td >' + price + '</td><td><a  href="#">Remove</a></td></tr>');


}


function updateBranch() {
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
                $(".spinner").hide();

                console.log(err.responseText);

            }
        });
    }

}


function clean() {
    $("#summary tbody").empty();
    $("#isbn").val('');
    $("#num").val('');


}





