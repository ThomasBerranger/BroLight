import $ from "jquery";

$(document).ready(function () {
    manageTimelineEventSpacing();

    $('ul.timeline').on("update-opinion", function(event, opinionId) {
        const timelinePanel = $(`#timelinePanel-${opinionId}`);

        if (timelinePanel.length === 1) {
            timelinePanel.html('<div class="d-flex align-items-center justify-content-center" style="height:'+timelinePanel.height()+'px;">' +
                '<i class="fas fa-compact-disc fa-spin fa-2x"></i></div>').load(` #timelinePanel-${opinionId}`);
        }
    });

    $('#loadTimelineEvents').click(function () {
        const element = $(this);
        const buttonTimelineLoad = element.find('i');

        const isInverted = ($('ul.timeline > li').length-1) % 2 !== 0 ? 1 : 0;

        element.data("offset", element.data('offset') + element.data('limit'));

        buttonTimelineLoad.addClass('fa-spin');

        $.ajax({
            url: `${element.data('url')}/${element.data('offset')}/${element.data('limit')}/${isInverted}`,
            success: function (data) {
                element.before(data);
                manageTimelineEventSpacing();
                buttonTimelineLoad.removeClass('fa-spin');
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
});

function manageTimelineEventSpacing() {
    $('.timeline-inverted').each(function () {
        const element = $(this)
        if (element.prev().offset().top - 10 <= element.offset().top && element.offset().top <= element.prev().offset().top + 10) {
            element.css("margin-top", "30px");
        }
    });
}