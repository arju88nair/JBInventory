$( document ).ready(function() {
    $('#batch').on('hidden.bs.modal', function () {
       var total=$("#expand-table >tbody >tr").length;


    });
    // Populating the modal for the corresponding edit row
    $('#batch').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget);
        // Button that triggered the modal
        var title = button.data('title');
        var batch = button.data('batch');
        var rowThis=button.data('row');
        console.log(rowThis);

        $.ajax({
            type: "POST",
            url: "batchExpand",
            data: {'batch_id': batch,'title_id':title},
            async: true,
            dataType: 'json',
            enctype: 'multipart/form-data',
            cache: false,
            success: function (data) {
                console.log(data);
                var modal = $(this);
                $('.modal-title').text(data[0].title);
                $('#expand-table tbody').empty();
                populateBatch(data);



            },
            error: function (err) {

                console.log(err.responseText);

            }
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

function deleteID(row,batch_id,title_id) //Deleting the row from the main table
{

    var answer = confirm ("Are you sure you want to delete from the database?");
    if (answer)
    {

        $.ajax({
            type: "POST",
            url: "deleteBobm",
            data: {'batch_id': batch_id,'title_id':title_id},
            async: true,
            dataType: 'json',
            enctype: 'multipart/form-data',
            cache: false,
            success: function (data) {
                console.log(data.status);
                if(data.status==200) {
                    var i = row.parentNode.parentNode.rowIndex;
                    document.getElementById("dev-table").deleteRow(i);
                }

            },
            error: function (err) {

                console.log(err.responseText);

            }
        });


    }

}


function populateBatch(jsonVal) //Populating the modal
{


    var jsonObj=jsonVal;
    //var htmlToAppend='';
    for(var i=0;i<jsonObj.length;i++){

        $('#expand-table tbody').append('<tr><td>'+getObjectFromJson(jsonObj[i],"title")+'</td><td>'+getObjectFromJson(jsonObj[i],"isbn_13")+'</td><td>'+getObjectFromJson(jsonObj[i],"copies")+'</td><td>'+getObjectFromJson(jsonObj[i],"branchname")+'</td><td>'+getObjectFromJson(jsonObj[i],"branch_order_id")+'</td><td>'+getObjectFromJson(jsonObj[i],"amount")+'</td><td><span class=\'glyphicon glyphicon-trash\' onclick=\'deleteBID(this,'+jsonObj[i].branch_order_id+');\'></span></td></tr>');
    }


}


function deleteBID(row,BID) // Deleting the row from the modal
{

    var answer = confirm ("Are you sure you want to delete from the database?");
    if (answer)
    {

        $.ajax({
            type: "POST",
            url: "deleteBID",
            data: {'id':BID },
            async: true,
            dataType: 'json',
            enctype: 'multipart/form-data',
            cache: false,
            success: function (data) {
                console.log(data.status);
                if(data.status==200) {
                    var i = row.parentNode.parentNode.rowIndex;
                    document.getElementById("expand-table").deleteRow(i);
                }

            },
            error: function (err) {

                console.log(err.responseText);

            }
        });


    }

}


function populateMain(val) // Populating the main table
{
    for(var i=0;i<val.length;i++){

        $('#dev-table tbody').append('<tr><td>'+getObjectFromJson(val[i],"title")+'</td><td>'
            +getObjectFromJson(val[i],"isbn_13")+'</td><td>'
            +getObjectFromJson(val[i],"copies")+'</td><td>'
            +getObjectFromJson(val[i],"amount")+'</td><td>'+getObjectFromJson(val[i],"total_amount")+'</td><td><span  class=\'glyphicon glyphicon-pencil\' data-toggle=\"modal\" id='+i+'  data-target=\"#batch\" data-batch='+val[i].batch_id+' data-title='+val[i].title_id+' data-row='+i+' ></span></td><td><span class=\'glyphicon glyphicon-trash\' onclick=\'deleteID(this,'+val[i].batch_id+','+val[i].title_id+');\'></span></td></tr>');
    }
}



