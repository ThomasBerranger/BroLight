import $ from "jquery";

$(document).ready(function() {
    $('body').on('click', '[data-view-action]', function () {
        const element = $(this);

        switch (element.data('view-action')) {
            case 'create':
                createRequest(element);
                break;
            case 'remove':
                removeRequest(element);
                break;
        }
    })
});

function createRequest(element) {
    $.ajax({
        url: element.data('url'),
        success: function () {
            element.parent().load(` #${element.parent().attr('id')}`);
            $('#alert').trigger("trigger-alert", ["success", "Visionnage sauvegardé !"]);
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function removeRequest(element) {
    $.ajax({
        url: element.data('url'),
        success: function () {
            $('#alert').trigger("trigger-alert", ["success", "Visionnage supprimé !"]);
        },
        error: function (error) {
            console.log(error);
        }
    });
}