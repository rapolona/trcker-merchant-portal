$(document).ready(function (e) {

    Pace.restart();
    
    $(".spinner-border").hide();
});

//pace
$(document).ajaxStart(function() { Pace.restart(); });

//ajax helper method
function post(url, activity, button, payload, redirect = "/dashboard")
{
    $.ajax({
        type:'POST',
        url: url,
        data: payload,
        cache:false,
        contentType: false,
        processData: false,
        success: (data) => {
            $(".modal-title").text(activity + " Successful!");
            $(".modal-body").html("<p>" + data.message + "</p>");
            $("#myModal").modal('show');
            $('#loader_' + button).hide();
            $("#" + button).removeAttr("disabled");
            $('#myModal').on('hidden.bs.modal', function () {
                window.location.href = redirect;
            });
        },
        error: function(data){
            $(".modal-title").text(activity + " Failed!");
            $(".modal-body").html("<p>" + data.responseJSON.message + "</p>");
            $("#myModal").modal('show');
            $('#loader_' + button).hide();
            $("#" + button).removeAttr("disabled");
        },
        beforeSend: function() {
            $('#loader_' + button).show();
            $("#" + button).attr("disabled");
        }
    });
}

function findObjectInArrayByProperty(array, propertyName, propertyValue) {
    return array.find((o) => { return o[propertyName] === propertyValue });
}