import $ from "jquery";

$(document).ready(function () {
    $('.timeline-inverted').each(function () {
        const element = $(this)
        if (element.prev().offset().top - 10 <= element.offset().top && element.offset().top <= element.prev().offset().top + 10) {
            element.css("margin-top", "30px");
        }
    });

    $('ul.timeline').on("update-opinion", function(event, opinionId) {
        const timelinePanel = $(`#timelinePanel-${opinionId}`);

        if (timelinePanel.length === 1) {
            timelinePanel.html('<div class="d-flex align-items-center justify-content-center" style="height:'+timelinePanel.height()+'px;">' +
                '<i class="fas fa-compact-disc fa-spin fa-2x"></i></div>').load(` #timelinePanel-${opinionId}`);
        }
    });
});