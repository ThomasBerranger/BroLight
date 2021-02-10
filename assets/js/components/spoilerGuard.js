import $ from "jquery";

$(function() {
   $('body').on('click', '.spoiler-guard',function() {
      $(this).fadeOut(200, function () {
         $(this).next().fadeIn(200);
      });
   });
})
