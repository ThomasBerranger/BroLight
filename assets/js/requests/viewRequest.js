import $ from "jquery";

$(document).ready(function() {
    $('body').on('click', '[data-view-action]', function () {
        const element = $(this);

        switch (element.data('view-action')) {
            case 'create':
                createRequest(element);
                break;
            case 'delete':
                deleteRequest(element);
                break;
        }
    })
});

function createRequest(element) {
    $.ajax({
        url: element.data('view-url'),
        success: function (data) {
            element.parent().html(data);
            $('#alert').trigger("trigger-alert", ["success", "Visionnage sauvegardé !"]);
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function deleteRequest(element) {
    $.ajax({
        url: element.data('view-url'),
        success: function (data) {
            element.parent().html(data);
            $('#alert').trigger("trigger-alert", ["success", "Visionnage Supprimé !"]);
        },
        error: function (error) {
            console.log(error);
        }
    });
}