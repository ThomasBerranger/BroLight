import $ from "jquery";

$(document).ready(function(){

   $('body').on('click', '.rating > input', function () {
      console.log($(this).attr('id'));

      $('#comment_rate').val($(this).attr('id').replace('rating-', ''))

   });

});

