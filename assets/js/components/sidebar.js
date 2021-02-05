import $ from 'jquery';

$(function(){
    $(".btn-header-sidebar").on("click", function(){
        $("#sidebar").toggleClass("open");
        $("body").toggleClass("overflow-hidden");
    });
});