import $ from "jquery";

$(document).ready(function() {
    $('body').on('click', '[data-relationship-action]', function () {
        switch ($(this).data('relationship-action')) {
            case 'accept':
                acceptFollowRequest($(this));
                break;
            case 'refuse':
                refuseFollowRequest($(this));
                break;
            case 'create':
                createFollowRequest($(this));
                break;
            case 'delete':
                deleteFollowRequest($(this));
                break;
        }
    })
});

function acceptFollowRequest(element) {
    $.ajax({
        url: element.data('relationship-url'),
        method: "get",
        success: function (data) {
            $(`#followerContainer-${data['userId']}`).trigger('update-user-interface', ['accepted-as-follower', data['button']])
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function refuseFollowRequest(element) {
    $.ajax({
        url: element.data('relationship-url'),
        method: "get",
        success: function (data) {
            $(`#followerContainer-${data}`).trigger('update-user-interface', ['removed-from-followers']);
        },
        error: function (error) {
            console.log(error);
        }
    });

    $('#customModal').modal('hide');
}

function createFollowRequest(element) {
    $.ajax({
        url: element.data('relationship-url'),
        method: "get",
        success: function (data) {
            $(`#followingContainer-${data['userId']}`).html(data['button']);
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function deleteFollowRequest(element) {
    $.ajax({
        url: element.data('relationship-url'),
        method: "get",
        success: function (data) {
            $(`#followingContainer-${data['userId']}`).html(data['button']);
        },
        error: function (error) {
            console.log(error);
        }
    });

    $('#customModal').modal('hide');
}

// function updateFollowingButtonContainer(data) {
//     console.log('ok');
//     const followingButtonContainer = $(`#followingButtonContainer-${data['userId']}`);
//     console.log(followingButtonContainer.length);
//     console.log(data['button']);
//
//     if (followingButtonContainer.length === 1) {
//         followingButtonContainer.html(data['button']);
//     }
// }