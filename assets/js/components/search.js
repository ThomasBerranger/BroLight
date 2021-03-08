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
                }
            });
        } else {
            $(".search").css('height', '0');
        }

    }, 350))

    $("nav > img").click(function () {
        $(".search").css('height', '0');
    });
});