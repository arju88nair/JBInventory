$( document ).ready(function() {
    var order;
    localStorage.clear();


    $(".spinner").show();
 var isbn;
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
    $('body').delegate('#totalPO tbody tr', "click", function (){
        $(this).addClass("selected").siblings().removeClass("selected");
        var tr =this.closest('tr');
        var vendor=$('td.idrow', tr).text();
        var batch=$('td.batch', tr).text()
         order=$('td.vendor', tr).text();
        localStorage.setItem("vendor",vendor);
        $("#inputDiv").show();
        localStorage.setItem("order",order);
        localStorage.setItem("batch",batch);

    });

});

$(document).ready(function() {
    $('table').on('click','tr a',function(e){
        e.preventDefault();
        $(this).parents('tr').remove();
    });
 order=localStorage.getItem('order');



    // var isbn=["9789352641796","X000KFQE99","521256911457","9789385854118","9788129144447","9780062135292"];

    // var isbn=["9789385854118","521256911457","QB561B3001398"];
    var scanned=0;

           // localStorage.setItem("isbnArray",isbn);
//            isbn=localStorage.getItem("isbnArray");
    console.log(typeof isbn);
    var pressed = false;
    var chars = [];
    $(window).keypress(function(e) {
        var isbn=localStorage.getItem("isbnArray");

        isbn = isbn.split(',');
        console.log(isbn);
        $(".alert").hide();
        //if (e.which >= 48 && e.which <= 57) {
        chars.push(String.fromCharCode(e.which));
        //}
        // console.log(e.which + ":" + chars.join("|"));
        if (pressed == false) {
            setTimeout(function(){
                if (chars.length >= 10) {
                    var barcode = chars.join("");
                    console.log("Barcode Scanned: " + barcode);
                    // assign value to some input (or do whatever you want)
                    // $("#barcode").text(barcode);
                    if($.inArray(barcode, isbn)== -1||$.inArray(barcode, isbn)=== "-1")
                    {
                        var check=localStorage.getItem("check")
                        if(check){
                            $(".alert").show();

                        }
                    }
                    else{
                        $('#isbn tbody').append('<tr><td >'+barcode+'</td><td >1</td><td><a  href="#">Remove</a></td></tr>');
                    }

                    // var index=arraySearch(isbn,barcode);
                    // if (index > -1) {
                    //     isbn.splice(index, 1);
                    // }
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





function getObjectFromJson(jsonObject,key)
{
    getObjectFromJson(jsonObject,key,null);
}


function getObjectFromJson(jsonObject,key,defaultVal) // Validating the existence of key
{
    if(jsonObject.hasOwnProperty(key)){
        return jsonObject[key];
    }else
    {
        return (defaultVal==null ?"":defaultVal);
    }
}

function populatePO(val)
{
    for(var i=0;i<val.length;i++){

        var j=i+1
        $('#totalPO tbody').append('<tr><td class="batch" >' +getObjectFromJson(val[i],"po_id")+'</td><td>'
            +getObjectFromJson(val[i],"name")+'</td><td class="vendor">' +getObjectFromJson(val[i],"orderid")+'</td><td class="idrow">' +getObjectFromJson(val[i],"vendor_id")+'</td><td>'
            +getObjectFromJson(val[i],"quantity")+'</td></tr>');
    }
    var table=$('#totalPO').DataTable({
        "order": [[ 0, "desc" ]]
    } );

    // $(".dataTables_filter").hide();
    $(".spinner").hide();
    $('#POTotalTableDiv').show();
    $('#navig').show();

}


function populateISBN()
{
    var invoice=$("#invoiceInput").val();
    var amount=$("#invoiceInputAmount").val();
    localStorage.setItem("invoice",invoice);
    localStorage.setItem("invoiceAmount",amount);
    localStorage.setItem("check","true");
    $('#POTotalTableDiv').hide();

    $(".spinner").hide();

    $('#isbnTable').show();
    orderRequired=localStorage.getItem('order');
    $(".spinner").show();

    getisbn(orderRequired);





}



function saveisbn(){
    var myTableArray = [];
    $("#isbn tr").each(function() {
        var arrayOfThisRow = [];
        var tableData = $(this).find('td');
        if (tableData.length > 0) {
            tableData.each(function() { arrayOfThisRow.push($(this).text()); });
            myTableArray.push(arrayOfThisRow);
        }
    });
    console.log(myTableArray);
    var batch=localStorage.getItem("batch");
    var order=localStorage.getItem("order");
    var vendor=localStorage.getItem("vendor");
    var invoice =localStorage.getItem("invoice");
    var amount =localStorage.getItem("invoiceAmount");
    var isbnTable=myTableArray;
    $.ajax({
        type: "POST",
        url: "savegr",
        data: {'batch': batch,'order':order,'isbn':isbnTable,'invoice':invoice,'vendor':vendor,'amount':amount},
        async: true,
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function (res) {


            console.log(res);
            summary(res);



        },
        error: function (err) {
            console.log(err)
            console.log(err.responseText);

        }
    });

}

function changeFunciton()
{
    $("#inputDivAmout").show();

}
function changeFuncitonInput()
{

    $("#batchNext").removeClass('disabled');
    // $("#batchNext").onclick=vendorStatus();
    $("#batchNext").click(function() {
        populateISBN();
    });
}

function summary (res) {

    $('#isbnTable').hide();
    $("#statusDiv").show();
    console.log(res)
    populateStatus(res);


}

function populateStatus(val){
    for(var i=0;i<val.length;i++){

        $('#status tbody').append('<tr><td  >' +getObjectFromJson(val[i],"name")+'</td><td>'
            +getObjectFromJson(val[i],"invoice")+'</td><td >' +getObjectFromJson(val[i],"isbn")+'</td><td >' +getObjectFromJson(val[i],"oq")+'</td><td>'
            +getObjectFromJson(val[i],"qr")+'</td><td >' +getObjectFromJson(val[i],"final")+'</td></tr>');
    }
}

function getisbn(order)
{
    $.ajax({

        type: "GET",
        url: "getPoArray?id="+order,
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function (data) {
            $(".spinner").hide();

            console.log(data);
            //     test(data);
            // autocompleted(data);
             isbn=data;
             $("#count").text("You have total " +isbn.length+ " books" )
             localStorage.setItem("isbnArray",isbn);

        },
        error: function (err) {

            console.log(err.responseText);

        }
    });
}