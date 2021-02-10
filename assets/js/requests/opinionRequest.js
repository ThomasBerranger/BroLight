import $ from "jquery";

$(document).ready(function() {
    const body = $('body')

    body.on('click', '[data-opinion-action]', function () {
        const element = $(this);

        switch (element.data('opinion-action')) {
            case 'create-view':
                createViewRequest(element);
                break;
            case 'delete-view':
                deleteViewRequest(element);
                break;
        }
    })

    body.on('submit', 'form[name="opinion"]', function () {
        event.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: "post",
            data: $(this).serializePrefixedFormJSON(),
            dataType: "json",
            success: function (data) {
                console.log(data);
            },
            error: function (error) {
                console.log(error);
            }
        });

        $('#customModal').modal('hide');
    });
});

function createViewRequest(element) {
    $.ajax({
        url: element.data('opinion-url'),
        success: function (data) {
            console.log(data);
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function deleteViewRequest(element) {
    $.ajax({
        url: element.data('opinion-url'),
        success: function (data) {
            console.log(data);
        },
        error: function (error) {
            console.log(error);
        }
    });
}