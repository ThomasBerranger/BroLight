import $ from "jquery";

$(function() {
   $('body').on('click', '.spoiler-guard',function() {
      console.log('fade bordel');
      $(this).fadeOut(200, function () {
         $(this).next().fadeIn(200);
      });
   });
})
