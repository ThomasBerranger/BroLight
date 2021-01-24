import $ from "jquery";

$(document).ready(function() {
    $('body').on('click', '[data-user-relationship-action]', function () {
        event.preventDefault();
        switch ($(this).data('user-relationship-action')) {
            case 'create':
                createFollowRequest($(this));
                break;
            case 'accept':
                acceptFollowRequest($(this));
                break;
            case 'delete':
                deleteFollowRequest($(this));
                break;
        }
    })
});

function createFollowRequest(element) {
    $.ajax({
        url: element.data('user-relationship-url'),
        method: "get",
        success: function (data) {
            updateFollowButton(data);
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function acceptFollowRequest(element) {
    $.ajax({
        url: element.data('user-relationship-url'),
        method: "get",
        success: function (data) {
            updateFollowButton(data);
            $('#alert').trigger("trigger-alert", ["success", "Demande acceptée !"]);
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function deleteFollowRequest(element) {

    $.ajax({
        url: element.data('user-relationship-url'),
        method: "get",
        success: function (data) {
            updateFollowButton(data);
        },
        error: function (error) {
            console.log(error);
        }
    });

    $('#customModal').modal('hide');
}

function updateFollowButton(data) {
    const followButtonDiv = $('#followButtonDiv');
    if (followButtonDiv.length === 1) {
        followButtonDiv.html(data);
    }
}