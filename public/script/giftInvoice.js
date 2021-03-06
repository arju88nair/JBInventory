$( document ).ready(function() {

    $.ajax({

        type: "GET",
        url: "getGiftBatches",
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function (data) {
            console.log(data);
            populateBatch(data);
            $('#dev-table').DataTable( {
                "order": [[ 5, "desc" ]]
            } );
            $(".dataTables_filter").hide();


        },
        error: function (err) {

            console.log(err.responseText);

        }
    });



});


function populateBatch(jsonVal)
{


    var jsonObj=jsonVal;
    //var htmlToAppend='';

    for(var i=0;i<jsonObj.length;i++){
        var from=jsonObj[i].from_date.slice(0,-8);
        var to=jsonObj[i].to_date.slice(0,-8);

        $('#dev-table tbody').append('<tr><td>'+jsonObj[i].id+'</td><td>'+jsonObj[i].name+'</td><td>'+jsonObj[i].description+'</td><td>'+from+'</td><td>'+to+'</td><td>'+jsonObj[i].created_at+'</td><td>'+jsonObj[i].p_name+'</td><td>'+jsonObj[i].status+'</td><td><span style="cursor: pointer; cursor: hand; " class=\'glyphicon glyphicon-trash\' onclick=\'deleteID(this,'+jsonObj[i].id+');\'></span>&nbsp;&nbsp;&nbsp; <a href=\"viewBatch?batch='+jsonObj[i].id+'\"><span class=\"glyphicon glyphicon-circle-arrow-right\" style="font-size: 16px"></span></a></span></td></tr>');
    }


}