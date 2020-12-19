import $ from "jquery";

$(document).ready(function(){
   $('#alert').on("trigger-alert", function(event, type, message) {
      const alert = $(this);

      alert.text(message);

      alert.removeClass(['alert-success', 'alert-danger', 'alert-info', 'alert-warning']);

      switch (type) {
         case 'success':
            alert.addClass('alert-success');
            break;
         case 'error':
            alert.addClass('alert-danger');
            break
      }

      alert.fadeIn(400).delay(3400).fadeOut(400);
   });
});

