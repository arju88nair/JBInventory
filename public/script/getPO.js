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

        $('#totalPO tbody').append('<tr><td class="idrow">'+getObjectFromJson(val[i],"po_id")+'</td><td>'
            +getObjectFromJson(val[i],"title")+'</td><td>'
            +getObjectFromJson(val[i],"vendor_id")+'</td><td>'
            +getObjectFromJson(val[i],"batch_id")+'</td><td>'
            +getObjectFromJson(val[i],"ordered_date")+'</td></tr>');
    }
    var table=$('#totalPO').DataTable( {
        "order": [[ 1, "asc" ]]
    } );

    // $(".dataTables_filter").hide();
    $(".spinner").hide();
    $('#POTotalTableDiv').show();
    $('#navig').show();

}
