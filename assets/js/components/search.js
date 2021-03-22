import $ from "jquery";
import { debounce } from '../functions/debounce';

$(document).ready(function(){
    const headerSearchInput = $("#headerSearchInput");
    const searchDiv = $(".search");
    const icon = $("nav > i");

    headerSearchInput.on('keyup', debounce(function(){
        const url = $(this).data('url');
        const text = $(this).val();

        if(text.length > 2) {
            $.ajax({
                url: url.replace("replaceMe", text),
                success: function(view) {

                    $('.container').css('position', 'fixed');
                    $("#searchResultDiv").html(view);
                    icon.removeClass('fa-search');
                    icon.addClass('fa-times pointer-cursor');
                    searchDiv.css({'height': '100vh'});

                }
            });
        } else {
            $('.container').css('position', 'static');
            $('#searchResultDiv').children().remove();
            icon.addClass('fa-search');
            icon.removeClass('fa-times pointer-cursor');
            searchDiv.css('height', 0);
        }

    }, 350))

    icon.click(function () {
        if (headerSearchInput.val().length > 2) {
            $('.container').css('position', 'static');
            $('#searchResultDiv').children().remove();
            $(this).addClass('fa-search');
            $(this).removeClass('fa-times pointer-cursor');
            searchDiv.css('height', 0);
            headerSearchInput.val('');
        }
    });
});