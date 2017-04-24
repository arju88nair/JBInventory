$( document ).ready(function() {
    $(".spinner").show();

        $.ajax({
            type: "GET",
            url: "getVendors",
            dataType: 'json',
            enctype: 'multipart/form-data',
            cache: false,
            success: function (data) {
                $(".spinner").hide();
                console.log(data);
                autocomplete(data);



            },
            error: function (err) {

                console.log(err.responseText);

            }
        });
});

function autocomplete(data)
{
    var availableTags = data;
    $( "#tags" ).autocomplete({
        source: availableTags
    });
};