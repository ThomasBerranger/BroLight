import $ from "jquery";

$(document).ready(function() {
    $('body').on('click', '[data-movie-action]', function () {
        const element = $(this);

        switch (element.data('movie-action')) {
            case 'create-view':
                createViewRequest(element);
                break;
            case 'remove-view':
                removeViewRequest(element);
                break;
        }
    })
});

function createViewRequest(element) {
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

function removeViewRequest(element) {
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