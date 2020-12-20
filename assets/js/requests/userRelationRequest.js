import $ from "jquery";

$(document).ready(function() {
    $("a[data-user-relationship]").click(function () {
        event.preventDefault();
        switch ($(this).data('user-relationship')) {
            case 'create-follow':
                createFollowRequest($(this));
                break;
            case 'accept-follow':
                acceptFollowRequest($(this));
                break;
        }
    })
});

function createFollowRequest(element) {
    $.ajax({
        url: element.attr('href'),
        method: "get",
    }).fail(function() {
        console.log("fail");
    }).done(function() {
        element.removeClass('btn-follow');
        element.addClass('btn-remove-follow-requests');
    });
}

function acceptFollowRequest(element) {
    $.ajax({
        url: element.attr('href'),
        method: "get",
    }).fail(function() {
        console.log("fail");
    }).done(function() {
        $('#alert').trigger("trigger-alert", ["success", "Demande acceptée !"]);
        element.fadeOut(200);
        $("#followers-container").append(element.prev()).children(':last').hide().fadeIn(500);
    });
}