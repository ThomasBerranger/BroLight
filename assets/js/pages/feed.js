$(document).ready(function () {
    $(".timeline-inverted").each(function () {
        const element = $(this)
        if (element.prev().offset().top - 10 <= element.offset().top && element.offset().top <= element.prev().offset().top + 10) {
            element.css("margin-top", "30px");
        }
    });
});