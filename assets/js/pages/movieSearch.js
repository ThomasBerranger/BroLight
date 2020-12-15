import $ from "jquery";
import { debounce } from '../functions/debounce';

$(document).ready(function(){
    $("#movieSearchInput").on('keyup', debounce(function(){
        const title = $(this).val();

        if (title.length > 2) {
            const url = $(this).data('search-url')

            $.ajax({
                url: url.replace("replaceMe", title),
                success: function(view) {
                    $("#moviesResult").html(view);
                }
            });
        }
    }, 350))
});