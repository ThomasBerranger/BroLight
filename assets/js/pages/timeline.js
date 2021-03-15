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
                if (data[0].length !== 0) {
                    element.before(data);
                    manageTimelineEventSpacing();
                    buttonTimelineLoad.removeClass('fa-spin');
                } else {
                    buttonTimelineLoad.parent().html('<span class="custom-font-text text-white">The end.</span>');
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
});

function manageTimelineEventSpacing() {
    $('li').each(function () {
        const element = $(this)

        if (element.prev().length === 1 && element.prev().offset().top - 20 <= element.offset().top && element.offset().top <= element.prev().offset().top + 20) {
            element.css("margin-top", "30px");
        }
    });
}