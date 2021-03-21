import $ from "jquery";

$(function() {
   $('body').on('click', '.spoiler-guard',function() {
      $(this).fadeOut(200, function () {
         $(this).prev().css({
            'transition' : '2s',
            'text-shadow' : 'none',
            'color' : 'black'
         });
      });
   });
})
