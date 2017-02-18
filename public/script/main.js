
$(document).ready(

    function () {
        $(".spinner").show();
        $( "#datepicker_start" ).datepicker({
            changeMonth: true,//this option for allowing user to select month
            changeYear: true,//this option for allowing user to select from year range
            dateFormat: 'yy-mm-dd'
        });
        $( "#datepicker_end" ).datepicker({
            changeMonth: true,//this option for allowing user to select month
            changeYear: true,//this option for allowing user to select from year range
            dateFormat: 'yy-mm-dd'
        });
    }
);


$(document).ready(function () {
    $.ajax({
        type: 'GET',
        url: 'getBatch',
        data: { get_param: 'value' },
        success: function (data) {
            $(".spinner").hide();
            console.log(data);
            populateBatch(data);
            $('#dev-table').DataTable( {
                "order": [[ 5, "desc" ]]
            } );
            $(".dataTables_filter").hide();


        },
        error:function(err)
        {
            console.log(err);
        }
    });
});

function populateBatch(jsonVal)
{


    var jsonObj=jsonVal;
    //var htmlToAppend='';

    for(var i=0;i<jsonObj.length;i++){
        $('#dev-table tbody').append('<tr><td>'+jsonObj[i].id+'</td><td>'+jsonObj[i].name+'</td><td>'+jsonObj[i].description+'</td><td>'+jsonObj[i].from_date+'</td><td>'+jsonObj[i].to_date+'</td><td>'+jsonObj[i].created_at+'</td><td>'+jsonObj[i].status+'</td><td><span class=\'glyphicon glyphicon-trash\' onclick=\'deleteID(this,'+jsonObj[i].id+');\'></span>&nbsp;&nbsp;&nbsp; <a href=\"viewBatch?batch='+jsonObj[i].id+'\"><span class=\"glyphicon glyphicon-circle-arrow-right\"></span></a></span></td></tr>');
    }


}



function deleteID(row,id)
{
    var answer = confirm ("Are you sure you want to delete from the database?");
    $(".spinner").show();
    if (answer)
    {

        $.ajax({
            type: "POST",
            url: "deleteBatch",
            data: {'id': id},
            async: true,
            dataType: 'json',
            enctype: 'multipart/form-data',
            cache: false,
            success: function (data) {
                $(".spinner").hide();
                console.log(data);
                var i = row.parentNode.parentNode.rowIndex;
                document.getElementById("dev-table").deleteRow(i);

            },
            error: function (err) {

                console.log(err);

            }
        });


    }

}
