$( document ).ready(function() {

$(".spinner").show();
    $.ajax({
        type: "GET",
        url: "getBatches",
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function (data) {
            $(".spinner").hide();
            console.log(data);
                test(data);
            autocompleted(data);



        },
        error: function (err) {

            console.log(err.responseText);

        }
    });



    $('#vendors').on('show.bs.modal', function (event) {
        $(".spinner").show();

        var button = $(event.relatedTarget);
        // Button that triggered the modal
        var title = button.data('title');
        var isbn = button.data('isbn');


        $.ajax({
            type: "POST",
            url: "getIsbnVendors",
            data: {'isbn': isbn,'title_id':title},
            async: true,
            dataType: 'json',
            enctype: 'multipart/form-data',
            cache: false,
            success: function (data) {

                $(".spinner").hide();
                console.log(data[0].title);
                var modal = $(this);
                $('.modal-title').text(data[0].title);
                $('#expand-table tbody').empty();
                populateModal(data);

            },
            error: function (err) {

                console.log(err.responseText);

            }
        });
    });

});


function autocompleted(data){
    var raw = data;
    var source  = [ ];
    var mapping = { };
    for(var i = 0; i < raw.length; ++i) {
        source.push(raw[i].name);
        mapping[raw[i].name] = raw[i].id;
    }


    $('.tags').autocomplete({

        minLength: 1,
        source: source,
        select: function(event, ui) {
            $(".spinner").show();
            var id=mapping[ui.item.value];
             $('.tags_id').text(mapping[ui.item.value]);
            callAjax(id);
        }
    });
}

// $('#dev-table').DataTable( {
//     "order": [[ 5, "desc" ]]
// } );
// $(".dataTables_filter").hide();

function callAjax(id)
{
    $.ajax({
        type: 'GET',
        url: 'getPOBatch?id='+id+'',
        data: { get_param: 'value' },
        success: function (data) {
            console.log(data);
            $(".spinner").hide();
            var total=data['total'];
            $("#Totalbooks").text(total);
            $("#pending").text(total);
           var response=data['response'];
           $(".well").show();
            vendorAjax(id);


        },
        error:function(err)
        {
            console.log(err.responseText);
        }
    });

}

function vendorAjax(id)
{

    console.log(id);

    $.ajax({
        type: "GET",
        url: "getPOVendors",
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function (data) {

            console.log(data)

            autocomplete(data,id);
        },
        error: function (err) {

            console.log(err.responseText);

        }
    });

}


function autocomplete(data,id)
{

    var availableTags = data;
    $( "#tags" ).autocomplete({
        source: availableTags,
        select: function( event, ui ) {
            $(".spinner").show();
            // $('#dev-table tbody').empty();
            $('#dev-table').DataTable().clear().destroy();
           vendorDetailsAJax(ui.item.value,id);
        }
    });
};


function vendorDetailsAJax(data,id)
{


    $.ajax({
        type: "POST",
        url: "getPODetails",
        data: {'id': id,'vendor_name':data},
        async: true,
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function (res) {

            console.log(res);

            populateBatch(res,id,name)


        },
        error: function (err) {
            console.log(err.responseText);

        }
    });
}

function populateBatch(jsonVal,ti_id,v_name)
{




    var jsonObj=jsonVal;
    //var htmlToAppend='';

    $(".spinner").hide();
    for(var i=0;i<jsonObj.length;i++){
        $('#dev-table tbody').append('<tr><td>'+i+'</td><td>'+jsonObj[i].title+'</td><td>'+jsonObj[i].quantity+'</td><td>'+jsonObj[i].quantity_required+'</td><td>'+jsonObj[i].currency+'</td><td>'+jsonObj[i].price+'</td><td><button type=\"button\" class=\"btn btn-info\" onclick="save(ti_id,v_name)">Save</button></td><td><span  class=\'glyphicon glyphicon-list\' data-toggle=\"modal\" id='+i+'  data-target=\"#vendors\" data-isbn='+jsonVal[i].isbn+' data-title='+jsonVal[i].title_id+'  ></span></td></tr>');
    };


    var table=$('#dev-table').DataTable( {
        "order": [[ 0, "asc" ]]
    } );

    $(".dataTables_filter").hide();

    $("#division").show();


}

//<input type=\"number\" value="'+jsonObj[i].quantity_required+'" min=\"0\" max="'+jsonObj[i].quantity_required+'"  onkeydown=\"return false\" id="'+jsonObj[i].isbn+'" onchange="click()">

function click()
{
    alert("hkj");
}

function save(id)
{
    console.log(ti_id+"ad");
    console.log(v_name+"sfda");



}
$('#vendors').on('hidden', function () {
  ;
    // do something…
    $(".spinner").hide();

})



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




function populateModal(val)
{
    for(var i=0;i<val.length;i++){

        $('#expand-table tbody').append('<tr><td>'+getObjectFromJson(val[i],"title")+'</td><td>'
            +getObjectFromJson(val[i],"quantity")+'</td></tr>');
    }

}

function test(data)
{
    var array=[];
    for(var i=0;i<data.length;i++)
    {
        array.push(data[i].name);
    }
    console.log(array);
}