import $ from "jquery";

$(document).ready(function(){
    $("#userSearchInput").on("keyup", function() {
        const value = $(this).val().toLowerCase();

        if (value.length > 1) {
            $("#usersList li").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        }
    });
});