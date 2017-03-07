$(document).ready(function () {
    $(".spinner").show();

    $.ajax({
        type: 'GET',
        url: 'summaryReport',
        data: { get_param: 'value' },
        success: function (data) {
            $(".spinner").hide();
            console.log(data);
            populateBatch(data);
            // $('#dev-table').DataTable( {
            //     "order": [[ 5, "desc" ]]
            // } );
            // $(".dataTables_filter").hide();


        },
        error:function(err)
        {
            console.log(err.responseText);
        }
    });
});


function populateBatch(jsonObj)
{
    for(var i=0;i<jsonObj.length;i++){


        $('#dev-table tbody').append('<tr><td>'+jsonObj[i].name+'</td><td>'+jsonObj[i].no_of_pos+'</td><td>'+jsonObj[i].order_qnty+'</td><td>'+jsonObj[i].rec_qnty+'</td><td></span>&nbsp;&nbsp;&nbsp; <a href=\"detailedReport?id='+jsonObj[i].id+'\"><span class=\"glyphicon glyphicon-circle-arrow-right\" style="font-size: 16px"></span></a></span></td></tr>');
    }
    $('#dev-table').DataTable( {
        "order": [[ 0, "desc" ]]
    } );
    $(".dataTables_filter").hide();
}

function populateMain(jsonObj) {
    $(".spinner").hide();

    for(var i=0;i<jsonObj.length;i++){


        $('#main_table tbody').append('<tr><td>'+jsonObj[i].id+'</td><td>'+jsonObj[i].name+'</td><td>'+jsonObj[i].invoice+'</td><td>'+jsonObj[i].isbn+'</td><td>'+jsonObj[i].title+'</td><td>'+jsonObj[i].author+'</td><td>'+jsonObj[i].quantity_recieved+'</td></tr>');
    }
    $(".spinner").hide();
        $('#main_table').DataTable( {
    dom: 'Bfrtip',
    buttons: [
        'csv', 'excel', 'print'
    ]
    } );
    $(".dataTables_filter").hide();
}