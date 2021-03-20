import $ from "jquery";
import { debounce } from '../functions/debounce';

$(document).ready(function(){
    const searchDiv = $(".search");

    $("#headerSearchInput").on('keyup', debounce(function(){
        const text = $(this).val();
        const url = $(this).data('url')

        if(text.length > 2) {
            $.ajax({
                url: url.replace("replaceMe", text),
                success: function(view) {
                    $("#searchResultDiv").html(view);
                    searchDiv.css('height', '100vh');
                    searchDiv.css('background-color', 'rgba(0, 0, 0, 0.6');
                }
            });
        } else {
            $('#searchResultDiv').children().remove();
            searchDiv.css('height', 0);
            searchDiv.css('background-color', 'rgba(0, 0, 0, 0.6');
        }

    }, 350))

    $("nav > img").click(function () {
        $('#searchResultDiv').children().remove();
        searchDiv.css('height', 0);
        searchDiv.css('background-color', 'rgba(0, 0, 0, 0.6');
    });
});