import $ from "jquery";
import {debounce} from "../functions/debounce";

$(document).ready(function() {
    const body = $('body');

    body.on('click', '[data-podium-action]', function () {
        const element = $(this);

        switch (element.data('podium-action')) {
            case 'open-form':
                openFormRequest(element);
                break;
            case 'vote':
                voteRequest(element);
                break;
        }
    });

    body.on('keyup', '#podiumMovieSearchInput', debounce(function(){
        const title = $(this).val();

        if (title.length > 2) {
            const url = $(this).data('search-url')

            $.ajax({
                url: url.replace("replaceMe", title),
                success: function(view) {
                    $("#podiumMoviesResult").html(view);
                }
            });
        }
    }, 350))
});

function openFormRequest(element) {
    let formDiv = $('<div class="px-4"></div>')

    $('#customModal').trigger("trigger-custom-modal", [
        element.data('title'),
        formDiv.html('<div class="text-center"><i class="fas fa-compact-disc fa-spin fa-3x"></i></div>').load(element.data('podium-url'))
    ]);
}

function voteRequest(element) {
    $.ajax({
        url: element.data('podium-url'),
        method: "get",
        success: function () {
            updatePodium();
        },
        error: function (error) {
            console.log(error);
        }
    });

    $('#customModal').modal('hide');
}

function updatePodium() {
    const podiumContainer = $('#podiumContainer');

    if (podiumContainer.length === 1) {
        podiumContainer.parent().load(' #podiumContainer');
    }
}