import $ from "jquery";

$(document).ready(function(){
   $('#alert').on("trigger-alert", function(event, type, message) {
      const alert = $(this);

      alert.text(message);

      alert.removeClass(['alert-success', 'alert-danger', 'alert-info', 'alert-warning']);

      switch (type) {
         case 'success':
            alert.addClass('alert-primary');
            break;
         case 'error':
            alert.addClass('alert-danger');
            break
      }

      alert.fadeIn(300).delay(2000).fadeOut(300);
   });
});

