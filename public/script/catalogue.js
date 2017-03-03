$(document).ready(function () {
    var table;
    var new_branch_id = "";
    $('form').submit(false);
    $(".spinner").show();
    $.ajax({

        type: "GET",
        url: "getPOs",
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
        var order = $('td.vendor', tr).text();
        localStorage.setItem("vendor", order);
        changeFunctionInput();

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


function changeFunctionInput() {

    $("#batchNext").removeClass('disabled');
    // $("#batchNext").onclick=vendorStatus();
    $("#batchNext").click(function () {
    });
}


function catalogue(id) {

    $("#POTotalTableDiv").hide();
    $(".catalogueDiv").show();
    $("#isbn").focus();


}
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
        $('#totalPO tbody').append('<tr><td class="batch" >' + getObjectFromJson(val[i], "po_id") + '</td><td>'
            + getObjectFromJson(val[i], "name") + '</td><td class="vendor">' + getObjectFromJson(val[i], "orderid") + '</td><td class="idrow">' + getObjectFromJson(val[i], "vendor_id") + '</td><td>'
            + getObjectFromJson(val[i], "quantity") + '</td><td><span id="' + getObjectFromJson(val[i], "orderid") + '" class=\"glyphicon glyphicon-circle-arrow-right\" style="font-size: 16px;cursor: pointer; cursor: hand; " onclick="POClick(\'' + val[i].orderid + '\',\'' + val[i].po_id + '\')"></span></td></tr>');
    }
     table = $('#totalPO').DataTable({
        destroy:true,
         clear:true
    });

    // $(".dataTables_filter").hide();
    $(".spinner").hide();
    $('#POTotalTableDiv').show();
    $('#navig').show();

}

function POClick(id, batch) {
    localStorage.setItem('batch', batch)
    localStorage.setItem("vendor", id);
    changeFunctionInput();
    catalogue();


}
function appendTable() {

$("#summary tbody").empty();
    $(".spinner").show();

    var batch = localStorage.getItem('batch');
    var order = localStorage.getItem('vendor');
    var isbn = $("#isbn").val();
    localStorage.setItem('isbn', isbn);
    localStorage.setItem('batch', batch);
    var bookNum = $("#num").val();
    localStorage.setItem("bookNum",bookNum);
    $.ajax({

        type: "GET",
        url: "catalogueUpdate?batch=" + batch + "&vendor=" + order + "&isbn=" + isbn + "&bookNum=" + bookNum,
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function (data) {
            console.log(data);
            if (data['code'] == 200) {
                populateReponse(data);
            }
            else {
                alert(data['message']);
            }


        },
        error: function (err) {

            console.log(err.responseText);

        }
    });
}


function populateReponse(val) {


    localStorage.setItem('branch_order',val['branch_order'])
    var branch_id = val['branch_id'];
    $("#title").text(val['title']);
    $("#isbnId").text(val['isbn']);
    $("#batch").text(val['batch']);
    $("#branch_order").text(val['branch_order']);


    var option = '';
    for (var i = 0; i < branch_id.length; i++) {
        option += '<option value="' + branch_id[i] + '">' + branch_id[i] + '</option>';
    }
    console.log(option)
    $('#summary tbody').append('<tr> <td >' + val['title'] + '</td><td>' + val['isbn'] + '</td><td class="vendor">' + val['batch'] + '</td><td class="idrow">' + val['branch_order'] + '</td><td>'+ val['selectedBranch'] + '</td><td><select id="branchId" onchange="triggered(this)">'+option+'</select></td></tr>');

    // $(' #summary tbody #branchId').append(option);
    $("#summaryDiv").show();
    $(".spinner").hide();


}




function updateBranch() {
    var new_branch = localStorage.getItem("newBranch");
    var order = localStorage.getItem('vendor');
    var isbn = localStorage.getItem('isbn');
    var batch = localStorage.getItem('batch');
    var branch_order=localStorage.getItem('branch_order');
    var bookNum=localStorage.getItem('bookNum');
    $(".spinner").show();
    $.ajax({

        type: "GET",
        url: "catalogueNewUpdate?batch=" + batch + "&order=" + order + "&isbn=" + isbn + "&branchOrder=" + branch_order+"&newBranch="+new_branch+"&bookNum="+bookNum,
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function (data) {
            console.log(data);
            $(".spinner").hide();
            if (data['code'] == 200) {
                populateReponse(data);
            }
            else {
                alert(data['message']);
            }


        },
        error: function (err) {
            $(".spinner").hide();

            console.log(err.responseText);
            $(".spinner").hide();


        }
    });
    $(".spinner").hide();

}

function triggered(id){
    new_branch_id = id.value;
    localStorage.setItem("newBranch", new_branch_id);
    $("#subBut").show();
}