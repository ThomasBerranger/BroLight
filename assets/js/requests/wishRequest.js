import $ from "jquery";

$(document).ready(function() {
    const body = $('body')

    body.on('click', '[data-wish-action]', function () {
        const element = $(this);

        switch (element.data('wish-action')) {
            case 'add':
                addRequest(element);
                break;
            case 'delete':
                deleteRequest(element);
                break;
        }
    })
});

function addRequest(element) {
    $.ajax({
        url: element.data('wish-url'),
        success: function (data) {
            $('#alert').trigger("trigger-alert", ["success", "Film ajouté à ta liste !"]);
            updateWishButton(data)
        },
        error: function (error) {
        }
    });
}

function deleteRequest(element) {
    $.ajax({
        url: element.data('wish-url'),
        success: function (data) {
            $('#alert').trigger("trigger-alert", ["success", "Film supprimé de ta liste !"]);
            updateWishButton(data)
        },
        error: function (error) {
        }
    });
}

function updateWishButton(data) {
    const currentWishButtons = $(`.wishButton-${data['tmdbId']}`);

    currentWishButtons.each(function () {
        $(this).html(data['button']);
    });
}