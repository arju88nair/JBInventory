$(document).ready(function()
{
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