import $ from "jquery";

$(document).ready(function() {
    $("form[name='avatar']").submit(function (){
        event.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: "put",
            data: $(this).serializePrefixedFormJSON(),
            dataType: "json",
            success: function () {
                $('#alert').trigger("trigger-alert", ["success", "Avatar sauvegardé !"]);
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
});