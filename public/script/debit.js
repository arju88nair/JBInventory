$(document).ready(function(){
    $('table').on('click','tr a',function(e){
        e.preventDefault();
        $(this).parents('tr').remove();
    });
    $('form').submit(false);
})



function submitButton()
{
$("#dataDIv").show();
}


function appendTable()
{
$("#tableDov").show();
var isbn=$("#isbn").val();
var price=$("#price").val();
var quantity=$("#quantity").val();
var reason=$("#selReason").val();
    $('#isbn tbody').append('<tr><td >'+isbn+'</td><td >'+quantity+'</td><td >'+price+'</td><td >'+reason+'</td><td><a  href="#">Remove</a></td></tr>');

    $("#isbn").val('');
    $("#price").val('');
    $("#quantity").val('');
}

function uploadTable()
{
    $(".spinner").show();

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

    var po=$("#selProc").val();
    var type=$("#selBran").val();
    var invoice=$("#invoiceInput").val();
    $.ajax({
        type: "POST",
        url: "InsertdebitCredit",
        data: {'table': myTableArray,'po':po,'type':type,'invoice':invoice},
        async: true,
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function (res) {

$(".spinner").hide();
            console.log(res);
            if(res.code==200)
            {
                $("html, body").animate({ scrollTop: 0 }, "slow");

                $("#pdfBUt").show()
                alert("Succesfully added")
            }



        },
        error: function (err) {
            $(".spinner").hide();

            console.log(err)
            console.log(err.responseText);

        }
    });
}



function pdfDOwn()
{
     var po=$("#selProc").val();
     window.location.href="debitPDF?poid="+po;

}

function searchButton()
{
    $.ajax({
        type: "GET",
        url: "searchInvoice",
        data: {'table': myTableArray,'po':po,'type':type,'invoice':invoice},
        async: true,
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function (res) {

            $(".spinner").hide();
            console.log(res);
            if(res.code==200)
            {
                $("html, body").animate({ scrollTop: 0 }, "slow");

                $("#pdfBUt").show()
                alert("Succesfully added")
            }



        },
        error: function (err) {
            $(".spinner").hide();

            console.log(err)
            console.log(err.responseText);

        }
    });
}