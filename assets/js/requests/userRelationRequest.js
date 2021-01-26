import $ from "jquery";

$(document).ready(function() {
    $('body').on('click', '[data-user-relationship-action]', function () {
        switch ($(this).data('user-relationship-action')) {
            case 'create':
                createFollowRequest($(this));
                break;
            case 'delete':
                deleteFollowRequest($(this));
                break;
            case 'accept':
                acceptFollowRequest($(this));
                break;
            case 'refuse':
                refuseFollowRequest($(this));
                break;
        }
    })
});

function createFollowRequest(element) {
    $.ajax({
        url: element.data('user-relationship-url'),
        method: "get",
        success: function (data) {
            updateFollowingButtonContainer(data);
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
            updateFollowingButtonContainer(data);
        },
        error: function (error) {
            console.log(error);
        }
    });

    $('#customModal').modal('hide');
}

function updateFollowingButtonContainer(data) {
    const followingButtonContainer = $(`#followingButtonContainer-${data['userId']}`);

    if (followingButtonContainer.length === 1) {
        followingButtonContainer.html(data['view']);
    }
}

function acceptFollowRequest(element) {
    $.ajax({
        url: element.data('user-relationship-url'),
        method: "get",
        success: function (data) {
            updateFollowerButtonContainer(data, 'move-to-followers');
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function refuseFollowRequest(element) {
    $.ajax({
        url: element.data('user-relationship-url'),
        method: "get",
        success: function (data) {
            updateFollowerButtonContainer(data, 'remove-from-followers');
        },
        error: function (error) {
            console.log(error);
        }
    });

    $('#customModal').modal('hide');
}

function updateFollowerButtonContainer(data, type) {
    const followerButtonContainer = $(`#followerButtonContainer-${data['userId']}`);

    if (followerButtonContainer.length === 1) {
        followerButtonContainer.html(data['view']);
        followerButtonContainer.trigger('update-user-interface', [type]);
    }
}