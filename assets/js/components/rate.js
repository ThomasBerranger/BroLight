import $ from "jquery";

$(document).ready(function(){
   $('body').on('click', '.rating > input', function () {
      $('#comment_rate').val($(this).attr('id').replace('rating-', ''))
   });
});

