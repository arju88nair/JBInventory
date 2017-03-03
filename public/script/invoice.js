$( document ).ready(function() {


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

    $('body').delegate('#totalPO tbody tr', "click", function (){
        console.log(this);
        $(this).addClass("selected").siblings().removeClass("selected");
        var tr =this.closest('tr');
        var id=$('td.idrow', tr).text();
        $("#search").removeClass('disabled');
        $("#search").click(function() {
            POStatus(id);
        });
    });
});
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
        $('#totalPO tbody').append('<tr><td>'+j+'</td><td>'
            +getObjectFromJson(val[i],"name")+'</td><td class="idrow">'
            +getObjectFromJson(val[i],"orderid")+'</td><td>'
            +getObjectFromJson(val[i],"quantity")+'</td></tr>');
    }
    var table=$('#totalPO').DataTable( );

    // $(".dataTables_filter").hide();
    $(".spinner").hide();
    $('#POTotalTableDiv').show();
    $('#navig').show();

}

function POStatus(id)
{
    $(".spinner").show()
    $("#POTotalTableDiv").hide();

    $.ajax({

        type: "GET",
        url: "getInvoicePOs?id="+id,
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function (data) {
            console.log(data);
            //     test(data);
            // autocompleted(data);
            populatePOTable(data);


        },
        error: function (err) {

            console.log(err.responseText);

        }
    });}

    function populatePOTable(val)
    {


        $("#POTotalTableDiv").hide();

        for(var i=0;i<val.length;i++){

            var amount=val[i].total_price;
            var j=i+1
            $('#invoice tbody').append('<tr><td>'
                +getObjectFromJson(val[i],"batch_id")+'</td><td class="idrow">'
                +getObjectFromJson(val[i],"invoice")+'</td><td>'
                +getObjectFromJson(val[i],"invoice_amount")+'</td><td>'
                +getObjectFromJson(val[i],"created_at")+'</td></tr>');
        }
        var table=$('#invoice').DataTable( );
        var sum=table.column( 2 ).data().sum();

        $(".spinner").hide();
        // $("#invoiceAmount").text("Total Invoice Amount id " +sum )
        // $("#poAmount").text("Total PO Amount id " +amount )

        $('#invoiceDIv').show();
        $('#navig').show()

    }