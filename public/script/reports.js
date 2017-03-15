
$(document).ready(
    function () {
        $(".spinner").show();
        $("#datepicker_start").datepicker({
            changeMonth: true,//this option for allowing user to select month
            changeYear: true,//this option for allowing user to select from year range
            dateFormat: 'yy-mm-dd'
        });
        $("#datepicker_end").datepicker({
            changeMonth: true,//this option for allowing user to select month
            changeYear: true,//this option for allowing user to select from year range
            dateFormat: 'yy-mm-dd'
        });
    }
);


$(document).ready(function () {
    $("#repSub").submit(false);
    $("#wrapper").hide();
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



    $.ajax({
        type: 'GET',
        url: 'getDefaultReports',
        data: { get_param: 'value' },
        success: function (data) {
            $(".spinner").hide();
            $("#completed").text(data['completed']);
            $("#pos").text(data['totalPo']);
            $("#batches").text(data['totalBatches']);
            $("#ordered").text(data['totalBooks']);
            $("#received").text(data['receivedBooks']);
            $("#processed").text(data['remainingBooks']);
            $("#wrapper").show();




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


function subRepo()
{
    $(".spinner").show();

    var start=$("#datepicker_start").val();
    var end=$("#datepicker_end").val();

    if(start === '' || end=== ''){
        alert("Please select a date range");
        $(".spinner").hide();
        return false;

    }



    $.ajax({
        type: 'GET',
        url: 'getDateReports?start='+start+"&end="+end,
        data: { get_param: 'value' },
        success: function (data) {
            $(".spinner").hide();
            console.log(data);
            $("#completed").text(data['completed']);
            $("#pos").text(data['totalPo']);
            $("#batches").text(data['totalBatches']);
            $("#ordered").text(data['totalBooks']);
            $("#received").text(data['receivedBooks']);
            $("#processed").text(data['remainingBooks']);
            $("#wrapper").show();

        },
        error:function(err)
        {
            $(".spinner").hide();
            console.log(err.responseText);
        }
    });


}