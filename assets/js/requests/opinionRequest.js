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
            case 'open-form':
                openFormRequest(element);
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
                updateOpinionButton(data);
                updateOpinionOnMovieDetails(data['opinionId']);
                updateOpinionOnTimeline(data['opinionId']);
                $('#alert').trigger("trigger-alert", ["success", "Avis enregistré !"]);
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

function openFormRequest(element) {
    let formDiv = $('<div class="px-4"></div>')

    $('#customModal').trigger("trigger-custom-modal", [
        element.data('title'),
        formDiv.html('<div class="text-center"><i class="fas fa-compact-disc fa-spin fa-3x"></i></div>').load(element.data('opinion-url'))
    ]);
}

function updateOpinionButton(data) {
    const currentOpinionButton = $(`#opinionButton-${data['tmdbId']}`);

    if (currentOpinionButton.length === 1) {
        currentOpinionButton.html(data['button']);
    }
}

function updateOpinionOnMovieDetails(opinionId) {
    const movieDetailsOpinionDiv = $('#movieDetailsOpinionDiv');

    if (movieDetailsOpinionDiv.length === 1) {
        movieDetailsOpinionDiv.trigger('update-opinion', opinionId);
    }
}

function updateOpinionOnTimeline(opinionId) {
    const timeline = $('ul.timeline');

    if (timeline.length === 1) {
        timeline.trigger('update-opinion', opinionId);
    }
}