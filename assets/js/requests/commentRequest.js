import $ from "jquery";

$(document).ready(function() {
    const body = $('body');

    body.on('submit', 'form[name="comment"]', function () {
        event.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: "post",
            data: $(this).serializePrefixedFormJSON(),
            dataType: "json",
            success: function () {
                $('#alert').trigger("trigger-alert", ["success", "Commentaire sauvegardé !"]);
            },
            error: function (error) {
                console.log(error);
            }
        });

        $('#headerModal').modal('hide');
    });

    body.on('click', '[data-comment-action]', function () {
        const element = $(this);

        switch (element.data('comment-action')) {
            case 'open-form':
                openFormRequest(element);
                break;
            case 'delete':
                deleteRequest(element);
                break;
        }
    });
});

function openFormRequest(element) {
    let formDiv = $('<div class="px-4"></div>')

    $('#headerModal').trigger("trigger-header-modal", [
        element.data('title'),
        formDiv.load(element.data('comment-url'))
    ]);
}

function deleteRequest(element) {
    $.ajax({
        url: element.data('comment-url'),
        method: "get",
        success: function () {
            $('#alert').trigger("trigger-alert", ["success", "Commentaire supprimé !"]);
        },
        error: function (error) {
            console.log(error);
        }
    });
}