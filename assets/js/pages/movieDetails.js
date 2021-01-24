import $ from "jquery";

$(document).ready(function(){

    $('#movieDetailsCommentDiv').on("update-comment", function(event, commentId) {

        const selectedComment = $(`#commentMovieDetails-${commentId}`);
        if (selectedComment.length === 1) {
            selectedComment.html('<div class="text-center w-100"><i class="fas fa-compact-disc fa-spin fa-2x"></i></div>').parent().load(` #commentMovieDetails-${commentId}`);
        } else {
            $('#movieDetailsCommentDiv').children().load(' #movieDetailsCommentDiv>div');
        }

    });

});