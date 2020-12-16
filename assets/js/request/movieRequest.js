import $ from "jquery";

$(document).ready(function() {
    $('body').on('click', '[data-movie-action]', function () {
        switch ($(this).data('movie-action')) {
            case 'create-view':
                createViewRequest($(this), parent);
                break;
        }
    })
});

function createViewRequest(element) {
    $.ajax({
        url: element.data('url'),
    }).fail(function(error) {
        // console.log(error);
    }).done(function() {
        element.parent().load(` #${element.parent().attr('id')}`);
    });
}