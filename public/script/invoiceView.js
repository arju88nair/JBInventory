$(document).ready(function () {
    $("#formField").show();

    $("form").submit(false);
    // $(".spinner").show();
//     $.ajax({
//
//     type: "GET",
//         url: "getGiftBranch",
//         dataType: 'json',
//         enctype: 'multipart/form-data',
//         cache: false,
//         success: function (data) {
//         console.log(data);
//         //     test(data);
//         // autocompleted(data);
//         var option = '';
//         for (var i = 0; i < data.length; i++) {
//             option += '<option  value="' + data[i].id + '">' + data[i].branchname + '  -  ' + data[i].id + '</option>';
//         }
//         $("#selBran").append(option);
//         $("#formField").show();
//
//         $(".spinner").hide();
//
//
//
//     },
//     error: function (err) {
//
//         console.log(err.responseText);
//
//     }
// });

})


function invoice()
{
    $(".spinner").show();
    var from =$("#datepicker_start").val();
    var to=$("#datepicker_end").val();
    var branch=$("#selBran").val();
    var text=$("#selBran").text();
    $.ajax({
        type: "POST",
        url: "eightyTwenty",
        data: {'from':from,'to':to,'branch':branch},
        async: true,
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function (data) {
            $(".spinner").hide();
            console.log(data);
            $("#summary").show();
            $("#statusDi").text("Summary  for "+from+" to "+to)
            if(data['procQuantity'] == null || data['procQuantity'] == 'null')
            {
                var procQuantity="--";
            }
            else{
                var procQuantity=data['procQuantity'];
            }
            $("#procQuantity").text(procQuantity);
            if(data['procCost'] == null || data['procCost'] == 'null')
            {
                var procCost="--";
            }
            else{
                var procCost=data['procCost'];
            }
            $("#procCost").text(procCost);
            if(data['procCharge'] == null || data['procCharge'] == 'null')
            {
                var procCharge="--";
            }
            else{
                var procCharge=data['procCharge'];
            }
            $("#procCharge").text(procCharge);
            if(data['giftQuantity'] == null || data['giftQuantity'] == 'null')
            {
                var giftQuantity="--";
            }
            else{
                var giftQuantity=data['giftQuantity'];
            }
            $("#giftQuantity").text(giftQuantity);
            if(data['giftCost'] == null || data['giftCost'] == 'null')
            {
                var giftCost="--";
            }
            else{
                var giftCost=data['giftCost'];
            }
            $("#giftCost").text(giftCost);
            if(data['giftCharge'] == null || data['giftCharge'] == 'null')
            {
                var giftCharge="--";
            }
            else{
                var giftCharge=data['giftCharge'];
            }
            $("#giftCharge").text(giftCharge);
            if(data['branchQuantity'] == null || data['branchQuantity'] == 'null')
            {
                var branchQuantity="--";
            }
            else{
                var branchQuantity=data['branchQuantity'];
            }
            $("#branchQuantity").text(branchQuantity);
            if(data['branchCost'] == null || data['branchCost'] == 'null')
            {
                var branchCost="--";
            }
            else{
                var branchCost=data['branchCost'];
            }
            $("#branchCost").text(branchCost);
            if(data['branchCharge'] == null || data['branchCharge'] == 'null')
            {
                var branchCharge="--";
            }
            else{
                var branchCharge=data['branchCharge'];
            }
            $("#branchCharge").text(branchCharge)

            var jsonObj=data['data'];
            for(var i=0;i<jsonObj.length;i++){

                $('#dev-tableData tbody').append('<tr><td>'+jsonObj[i].title_id+'</td><td>'+jsonObj[i].isbn+'</td><td>'+jsonObj[i].title+'</td><td>'+jsonObj[i].book_num+'</td><td>'+jsonObj[i].quantity+'</td><td>'+jsonObj[i].amount+'</td><td>'+jsonObj[i].created_at+'</td></tr>');

            }
            $('#dev-tableData').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                    'csv', 'excel', 'print'
                ]
            } );
            $(".dataTables_filter").hide();



        },
        error: function (err) {
            $(".spinner").hide();


            console.log(err.responseText);

        }
    });
}


function pdfDownload()
{
    var from =$("#datepicker_start").val();
    var to=$("#datepicker_end").val();
    var branch=$("#selBran").val();

    window.location="invoicePDFDownload?from="+from+"&to="+to+"&branch="+branch;
}