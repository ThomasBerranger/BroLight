import $ from "jquery";

$(document).ready(function() {
    $("form[name='avatar']").submit(function (){
        event.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: "post",
            data: $(this).serialize(),
            dataType: "json",
            success: function (data) {
                console.log(data);
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
});