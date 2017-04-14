$(document).ready(function()
{
    var table;

    $("#normal").submit(false);
    $("#dev-table").DataTable();




    $('#material').on('show.bs.modal', function (event) {
        $(".spinner").show();

        var button = $(event.relatedTarget);
        // Button that triggered the modal
        var title = button.data('title');
        var batch = button.data('material');
        var quantity=button.data('received');
        var modal = $(this);
        $('#title').text(title);
        $(".modal-body #recQuan").val(quantity);
        $(".modal-body #id").val(batch);

        $(".spinner").hide();
        $.ajax({
            type: "POST",
            url: "listMaterials",
            data: {'id': batch},
            async: true,
            dataType: 'json',
            enctype: 'multipart/form-data',
            cache: false,
            success: function (data) {

                $(".spinner").hide();
                console.log(data);
                var modal = $(this);

                populateExpand(data);



            },
            error: function (err) {

                console.log(err.responseText);

            }
        });

    });


})



function deleteID(id,row)
{
    $(".spinner").show();
    var answer = confirm("Are you sure you want to delete from the database?");
    if (answer) {
        $.ajax({
            type: "GET",
            url: "deleteMaterialPO",
            data: {'id': id},
            async: true,
            dataType: 'json',
            cache: false,
            success: function (data) {
                $(".spinner").hide();
                console.log(data);
                var i = row.parentNode.parentNode.rowIndex;
                document.getElementById("dev-table").deleteRow(i);

            },
            error: function (err) {

                console.log(err.responseText);


            }
        });


    }
    $(".spinner").hide();


}

$(document).ready(function() {
    $('#isbn').on('click', 'tr a', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });
});

function add() {
    
   var type= $("#type").val();
    $('#isbn tbody').append('<tr><td >'+$("#type").val()+'</td><td >'+$("#quantity").val()+'</td><td>'+$("#priceFirst").val()+'</td><td><a  href="#">Remove</a></td></tr>');

    $("#quantity").val("");
    $("#priceFirst").val("");
}

function submitData()
{
    $(".spinner").show();
    var batch=$("#name").val();
    var description=$("#description").val();
    var vendor=$("#vendorDrop").val();
    table=$('#isbn').DataTable();
    var array=[];

    var data= table.rows().every(function(){
        var cell=[];
        cell.push(this.data()[0]);
        cell.push(this.data()[1]);
        cell.push(this.data()[2]);

        array.push(cell);
    });
    console.log(array);
    $.ajax({
        type: "POST",
        url: "insertMaterials",
        data: {'name':batch,'vendor':vendor,'table':array,'description':description,'data':array},
        async: true,
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function (data) {
            $(".spinner").hide();


            console.log(data);
            alert("Successfully added");

            // if(data.status==200)
            // {
            //     window.location="materials";
            // }

        },
        error: function (err) {
            $(".spinner").hide();

            console.log(err.responseText)
            alert("Successfully added");

            // if(err.responseText.status==200)
            // {
            //     window.location="materials";
            // }

        }
    });



}

function populateExpand(data)
{
    for(var i=0;i<data.length;i++){

        $('#listView tbody').append('<tr><td class="hideC">' + data[i].id + '</td><td class="idrow">' + data[i].material + '</td><td>' + data[i].ordered_quantity + '</td><td><input style=\"width: 80%;white-space:nowrap;\" type=\"number\"  class="tdInputDis" value=' + data[i].recieved_quantity + ' oninput="inputDis(this)" min=\"0\"   ></input></td><td class="recie hideC">' + data[i].recieved_quantity + '</td></tr>');
    }
  table= $(" #listView").DataTable();
}



function inputDis(thisID){
    var tr =thisID.closest('tr');
    var input=$('td input.tdInputDis', tr).val();
    // var req=$('td.reqClass', tr).text();
    $('td.recie',tr).text(parseInt(input));

}



function updateEntries() {
    $(".spinner").show();
    table.destroy();
    table=$('#listView').DataTable();
    var array=[];
    var data= table.rows().every(function(){
        console.log(this.data());
        var cell=[];
        cell.push(this.data()[0]);
        cell.push(this.data()[4]);

        array.push(cell);
    });
    console.log(array);
    $.ajax({
        type: "POST",
        url: "updateMaterials",
        data: {'data':array},
        async: true,
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function (data) {
            $(".spinner").hide();
            alert("Successfully updated");


            console.log(data);
            if(data.status==200)
            {
                window.location="materials";
            }

        },
        error: function (err) {
            alert("Successfully updated");

            $(".spinner").hide();

            console.log(err.responseText);

            if(err.responseText.status==200)
            {
                window.location="materials";
            }

        }
    });


}