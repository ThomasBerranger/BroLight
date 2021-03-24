import $ from "jquery";

$(document).ready(function() {
    const body = $('body');

    body.on('click', '[data-user-action]', function () {
        const element = $(this);

        switch (element.data('user-action')) {
            case 'open-form':
                openFormRequest(element);
                break;
        }
    });

    body.on('submit', 'form[name="user"]', function () {
        event.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: "post",
            data: $(this).serializePrefixedFormJSON(),
            dataType: "json",
            success: function (data) {
                $('#alert').trigger("trigger-alert", ["success", "Informations modifiées !"]);
            },
            error: function (error) {
                $('#alert').trigger("trigger-alert", ["error", error['responseJSON']]);
            }
        });

        $('#customModal').modal('hide');
    });
});

function openFormRequest(element) {
    let formDiv = $('<div class="px-4"></div>')

    $('#customModal').trigger("trigger-custom-modal", [
        element.data('title'),
        formDiv.html('<div class="text-center"><i class="fas fa-compact-disc fa-spin fa-3x"></i></div>').load(element.data('user-url'))
    ]);
}