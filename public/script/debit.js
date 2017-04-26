$(document).ready(function(){
    $('isbn').on('click','tr a',function(e){
        e.preventDefault();
        $(this).parents('tr').remove();
    });
    $('form').submit(false);
})



function submitButton()
{
    $("#searchDIv").hide();

    $("#dataDIv").show();
}


function appendTable()
{

    if($("#invoiceInput").val() == '')
    {
        alert("Please Enter An Invoice Number");
        return false;
    }
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
    $(".spinner").show();
    $("#dataDIv").hide();
    var po=$("#selProc").val();


    $
    $.ajax({
        type: "GET",
        url: "searchInvoice?po="+po,
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function (res) {
            $("#searchDIv").show();
            $("#populateInvoiceTable tbody").empty();
            $(".spinner").hide();
            console.log(res.length);
            for(var i=0;i<res.length;i++)
            {
                $('#populateInvoiceTable tbody').append('<tr><td >' + res[i].invoice + '</td><td >   <a href="debitPDF?poid='+po+'&invoice='+res[i].invoice+'"  class="btn btn-info" role="button">Download</a></td></tr>');

            }




        },
        error: function (err) {
            $(".spinner").hide();

            console.log(err)
            console.log(err.responseText);

        }
    });
}


function DownloadPO()
{
    var po=$("#selProc").val();
    window.location.href="debitPDF?poid="+po;
}