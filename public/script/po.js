$( document ).ready(function() {console.log("Gf")



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
            //     test(data);
            // autocompleted(data);
            populateBatchTable(data);



        },
        error: function (err) {

            console.log(err.responseText);

        }
    });

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
                console.log(data);
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


// function autocompleted(data){
//     var raw = data;
//     var source  = [ ];
//     var mapping = { };
//     for(var i = 0; i < raw.length; ++i) {
//         source.push(raw[i].name);
//         mapping[raw[i].name] = raw[i].id;
//     }
//
//
//     $('.tags').autocomplete({
//
//         minLength: 1,
//         source: source,
//         select: function(event, ui) {
//             $(".spinner").show();
//             var id=mapping[ui.item.value];
//              $('.tags_id').text(mapping[ui.item.value]);
//             callAjax(id);
//         }
//     });
// }

// $('#dev-table').DataTable( {
//     "order": [[ 5, "desc" ]]
// } );
// $(".dataTables_filter").hide();

// function callAjax(id)
// {
//     $.ajax({
//         type: 'GET',
//         url: 'getPOBatch?id='+id+'',
//         data: { get_param: 'value' },
//         success: function (data) {
//             console.log(data);
//             $(".spinner").hide();
//             var total=data['total'];
//             $("#Totalbooks").text(total);
//             $("#pending").text(total);
//            var response=data['response'];
//            $(".well").show();
//             vendorAjax(id);
//
//
//         },
//         error:function(err)
//         {
//             console.log(err.responseText);
//         }
//     });
//
// }

function vendorAjax(id)
{


    $.ajax({
        type: "GET",
        url: "getPOVendors",
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function (data) {

            console.log(data)

            populateVendors(data);
        },
        error: function (err) {

            console.log(err.responseText);

        }
    });

}


// function autocomplete(data,id)
// {
//
//     var availableTags = data;
//     $( "#tags" ).autocomplete({
//         source: availableTags,
//         select: function( event, ui ) {
//             $(".spinner").show();
//             // $('#dev-table tbody').empty();
//             $('#dev-table').DataTable().clear().destroy();
//            vendorDetailsAJax(ui.item.value,id);
//         }
//     });
// };


function vendorDetailsAJax()
{
var id=localStorage.getItem("bId");
var data=localStorage.getItem("vID");

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

            populateBatch(res,id,data)


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
        $('#dev-table tbody').append('<tr><td >'+jsonObj[i].title+'</td><td>'+jsonObj[i].title_id+'</td><td class="reqClass">'+jsonObj[i].quantity_required+'</td><td>'+jsonObj[i].quantity+'</td><td><input type=\"number\"  class="tdInput" value='+jsonObj[i].quantity_required+' oninput="input(this)" min=\"0\"   onkeydown=\"return false\"></input></td><td class="total">'+jsonObj[i].quantity_required+'</td><td>'+jsonObj[i].price+'</td><td><span style="cursor: pointer; cursor: hand; "  class=\'glyphicon glyphicon-list\' data-toggle=\"modal\"   data-target=\"#vendors\" data-isbn='+jsonVal[i].isbn+' data-title='+jsonVal[i].title_id+'  ></span></td><td class="hideC">'+jsonObj[i].branch_order_id+'</td><td class="hideC">'+jsonObj[i].isbn+'</td></tr>');
    };


    var table=$('#dev-table').DataTable( {
        destroy: true,
        //
        // "columnDefs": [
        //     {
        //         "targets": [ 8 ],
        //         "visible": false,
        //     }
        //     ],

        "order": [[ 0, "asc" ]]
    } );

    $("#poDivision").show();

    // $(".dataTables_filter").hide();

    $("#division").show();


}

//<input type=\"number\" value="'+jsonObj[i].quantity_required+'" min=\"0\" max="'+jsonObj[i].quantity_required+'"  onkeydown=\"return false\" id="'+jsonObj[i].isbn+'" onchange="click()">

// function click()
// {
//     alert("hkj");
// }
//
// function save(id)
// {
//     console.log(ti_id+"ad");
//     console.log(v_name+"sfda");
//
//
//
// }
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

// function test(data)
// {
//     var array=[];
//     for(var i=0;i<data.length;i++)
//     {
//         array.push(data[i].name);
//     }
//     console.log(array);
// }


function array_combine()
{
    var myTableArray = [];
    $("#dev-table tr").each(function() {
        var arrayOfThisRow = [];
        var tableData = $(this).find('td');
        if (tableData.length > 0) {
            tableData.each(function() { arrayOfThisRow.push($(this).text()); });
            myTableArray.push(arrayOfThisRow);
        }
    });
    console.log(myTableArray);
    var vId=localStorage.getItem("vID");
    var bId=localStorage.getItem("bId");

    $.ajax({
        type: "POST",
        url: "insertPO",
        data: {'table': myTableArray,'vName':vId,'bId':bId},
        async: true,
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function (data) {

            console.log(data);

        },
        error: function (err) {
            $(".alert").show();


            console.log(err.responseText);

        }
    });
}

function input(thisID)
{
    var tr =thisID.closest('tr');
    var input=$('td input.tdInput', tr).val();
    var req=$('td.reqClass', tr).text();

    $('td.total',tr).text(parseInt(input));



}

function populateBatchTable(val)
{
    for(var i=0;i<val.length;i++){

        $('#batch-table tbody').append('<tr><td class="idrow">'+getObjectFromJson(val[i],"id")+'</td><td>'
            +getObjectFromJson(val[i],"name")+'</td><td>'
            +getObjectFromJson(val[i],"from_date")+'</td><td>'
            +getObjectFromJson(val[i],"to_date")+'</td><td>'
            +getObjectFromJson(val[i],"status")+'</td><td>'
            +getObjectFromJson(val[i],"created_at")+'</td></tr>');
    }
    var table=$('#batch-table').DataTable( {

        "order": [[ 1, "asc" ]]
    } );

    // $(".dataTables_filter").hide();
    $("#batchTableDiv").show();
    $('body').delegate('#batch-table tbody tr', "click", function (){
        $(this).addClass("selected").siblings().removeClass("selected");
        var tr =this.closest('tr');
        var id=$('td.idrow', tr).text();
        localStorage.setItem("bId",id);
        $("#batchNext").removeClass('disabled');
        // $("#batchNext").onclick=vendorStatus();
        $("#batchNext").click(function() {
            vendorStatus();
        });
    });
}

function vendorStatus()
{
    $(".spinner").show();

    $("#batchTableDiv").hide();
    vendorAjax()


}


function populateVendors(val)
{
    for(var i=0;i<val.length;i++){

        $('#vendors-table tbody').append('<tr><td class="idrow">'+getObjectFromJson(val[i],"id")+'</td><td>'
            +getObjectFromJson(val[i],"name")+'</td><td>'
            +getObjectFromJson(val[i],"phone")+'</td><td>'
            +getObjectFromJson(val[i],"city")+'</td><td>'
            +getObjectFromJson(val[i],"discount")+'</td></tr>');
    }
    var table=$('#vendors-table').DataTable( {
        destroy: true,
        "order": [[ 1, "asc" ]]
    } );

    // $(".dataTables_filter").hide();
    $(".spinner").hide();

    $("#vendorDivision").show();
    $('body').delegate('#vendors-table tbody tr', "click", function (){
        console.log(this);
        $(this).addClass("selected").siblings().removeClass("selected");
        var tr =this.closest('tr');
        var id=$('td.idrow', tr).text();
        console.log(id);
        localStorage.setItem("vID",id);
        $("#VendorsNext").removeClass('disabled');
        $("#VendorsNext").click(function() {
            POStatus();
        });
    });
}


function POStatus()
{

    $(".spinner").show();

    $("#batchTableDiv").hide();
    $("#vendorDivision").hide();
    vendorDetailsAJax();

}


function POStatusBack()
{


    $("#vendorDivision").hide();
    $("#batchTableDiv").show();


}

function poInsertBack()
{
    var table=$('#dev-table').DataTable();
    console.log(table)
    table.destroy();
    $('#dev-table tbody').empty()

    $("#poDivision").hide();
    $("#vendorDivision").show();
}


function populatePO(val)
{
    for(var i=0;i<val.length;i++){

        $('#totalPO tbody').append('<tr><td class="idrow">'+getObjectFromJson(val[i],"po_id")+'</td><td>'
            +getObjectFromJson(val[i],"title")+'</td><td>'
            +getObjectFromJson(val[i],"vendor_id")+'</td><td>'
            +getObjectFromJson(val[i],"batch_id")+'</td><td>'
            +getObjectFromJson(val[i],"created_date")+'</td></tr>');
    }
    var table=$('#totalPO').DataTable( {
        "order": [[ 1, "asc" ]]
    } );

    // $(".dataTables_filter").hide();
    $(".spinner").hide();
    $('#POTotalTableDiv').show();
    $('#navig').show();

}