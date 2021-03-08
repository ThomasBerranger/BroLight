import $ from "jquery";
import { debounce } from '../functions/debounce';

$(document).ready(function(){
    $("#headerSearchInput").on('keyup', debounce(function(){
        const text = $(this).val();
        const url = $(this).data('url')

        if(text.length > 2) {
            $.ajax({
                url: url.replace("replaceMe", text),
                success: function(view) {
                    $("#searchResultDiv").html(view);
                    $(".search").css('height', '100vh');
                    $(".container").css('opacity', '0.6');
                }
            });
        } else {
            $('#searchResultDiv').children().remove();
            $(".container").css('opacity', '1');
        }

    }, 350))

    $("nav > img").click(function () {
        $('#searchResultDiv').children().remove();
        $(".container").css('opacity', '1');
    });
});