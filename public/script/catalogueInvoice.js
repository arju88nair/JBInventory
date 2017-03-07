$(document).ready(function () {






    $('form').submit(false);
    $("#batchTable").hide();
    $(".spinner").show()
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
        var from=jsonObj[i].from_date.slice(0,-8);
        var to=jsonObj[i].to_date.slice(0,-8);

        $('#dev-table tbody').append('<tr><td>'+jsonObj[i].id+'</td><td>'+jsonObj[i].name+'</td><td>'+jsonObj[i].description+'</td><td>'+from+'</td><td>'+to+'</td><td>'+jsonObj[i].created_at+'</td><td>'+jsonObj[i].p_name+'</td><td>'+jsonObj[i].status+'</td><td><span id="'+getObjectFromJson(jsonObj[i],"id")+'" class=\"glyphicon glyphicon-circle-arrow-right\" style="font-size: 16px;cursor: pointer; cursor: hand; " onclick="batchClick(this.id)"></span></td></tr>');
    }
    $("#batchTable").show();
    $(".spinner").hide()

}



function autocomplete(data)
{
    var availableTags = data;
    $( "#tags" ).autocomplete({
        source: availableTags
    });
}


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

function batchClick(id)
{
    localStorage.setItem("catBatch",id);
    $(".spinner").show()
    $.ajax({
        type: 'GET',
        url: 'catalogueGetBatch?id='+id,
        data: { get_param: 'value' },
        success: function (data) {
            $(".spinner").hide();
            console.log(data);
            populateSelect(data);


        },
        error:function(err)
        {
            console.log(err);
        }
    });
}


function populateSelect(data){


    var option = '';
    for (var i = 0; i < data.length; i++) {
        option += '<option value="' + data[i].branch_id + '">' + data[i].branchname + '</option>';
    }
    $("#sel1").append(option)

    $("#selectDiv").show();
    $(".spinner").hide()
}


function selectValues()
{

    $(".spinner").show();
    var id=localStorage.getItem('catBatch');
    var branch=$("#sel1").val();
    localStorage.setItem("catBranch",branch);

    $.ajax({
        type: 'GET',
        url: 'catalogueTable?batch='+id+"&branch="+branch,
        data: { get_param: 'value' },
        success: function (data) {
            $(".spinner").hide();
            console.log(data);
            populateMain(data);
            // populateBatch(data);
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



}


function populateMain(jsonObj)
{
    $("#batchTable").hide();
    $("#selectDiv").hide();
    for(var i=0;i<jsonObj.length;i++){



        $('#invoice tbody').append('<tr><td>'+i+'</td><td>'+jsonObj[i].isbn+'</td><td>'+jsonObj[i].title+'</td><td>'+jsonObj[i].author_name+'</td><td>'+jsonObj[i].price+'</td><td>'+jsonObj[i].quantity+'</td><td>'+jsonObj[i].total+'</td></tr>');
    }

    $('#invoice').DataTable( {
        destroy:true,
        "order": [[ 5, "desc" ]]
    } );
    $(".dataTables_filter").hide();

    $("#invoiceTable").show();
    $(".spinner").hide();
}


function cataloguePDF()
{

    var id=localStorage.getItem('catBatch');
    var branch=localStorage.getItem('catBranch');
    var url="cataloguePDF?batch='"+id+"'&branch='"+branch+"'";
    window.location=url

}

