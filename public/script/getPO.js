$( document ).ready(function() {
    console.log("Gf")


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
        $('#totalPO tbody').append('<tr><td class="idrow">'+j+'</td><td>'
            +getObjectFromJson(val[i],"name")+'</td><td>'
            +getObjectFromJson(val[i],"orderid")+'</td><td>'
            +getObjectFromJson(val[i],"quantity")+'</td><td> <a href="PDF?id='+val[i].orderid+'&vid='+val[i].vendor_id+'" class="btn btn-info" role="button">Generate PDF</a></td><td><button style="border: 2px solid lightblue;background-color: white;" type="button" class="btn btn-outline-primary" onclick="viewPO(\'' + val[i].orderid + '\',\'' + val[i].vendor_id + '\')">View</button></td></tr>');
    }
    var table=$('#totalPO').DataTable( );

    // $(".dataTables_filter").hide();
    $(".spinner").hide();
    $('#POTotalTableDiv').show();
    $('#navig').show();

}



function viewPO(order,vendor)
{
    $('#POTotalTableDiv').hide();
    $("#viewPO").show();

    $(".spinner").show();
    $.ajax({

        type: "GET",
        url: "viewPOPDF?id="+order,
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function (data) {
            console.log(data);
            //     test(data);
            // autocompleted(data);
            populateView(data);


        },
        error: function (err) {

            console.log(err.responseText);

        }
    });



}



function populateView(val)
{
    for(var i=0;i<val.length;i++){

        var j=i+1
        $('#viewPO tbody').append('<tr><td class="idrow">'+j+'</td><td>'
            +getObjectFromJson(val[i],"title")+'</td><td>'
            +getObjectFromJson(val[i],"isbn")+'</td><td>'
            +getObjectFromJson(val[i],"ordered_quantity")+'</td><td>'
            +getObjectFromJson(val[i],"price")+'</td><td>'
            +getObjectFromJson(val[i],"discount")+'</td><td>'
            +getObjectFromJson(val[i],"net_price")+'</td><td>'
            +getObjectFromJson(val[i],"total")+'</td></tr>');
    }
    var table=$('#viewPO').DataTable( {
        destroy: true,
    });
    $(".spinner").hide();
}


function backView()
{
    $('#POTotalTableDiv').show();
    $("#viewPO").hide();
}