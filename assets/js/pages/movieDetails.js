import $ from "jquery";

$(document).ready(function(){

    $('.details-movie-opinions').css('max-height', $('.details-movie-card').height())

    $('#movieDetailsOpinionDiv').on("update-opinion", function(event, opinionId) {
        const selectedOpinion = $(`#opinionMovieDetails-${opinionId}`);

        if (selectedOpinion.length === 1) {
            selectedOpinion.html('<div class="text-center w-100"><i class="fas fa-compact-disc fa-spin fa-2x"></i></div>').parent().load(` #opinionMovieDetails-${opinionId}`);
        } else {
            $('#movieDetailsOpinionDiv').load(' #movieDetailsOpinionDiv>div');
        }
    });

});