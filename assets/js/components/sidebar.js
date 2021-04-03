import $ from 'jquery';

let deferredPrompt;
self.addEventListener('beforeinstallprompt', function(event) {
    event.preventDefault();
    deferredPrompt = event;
    return false;
});

$(function(){
    $(".btn-header-sidebar").on("click", function(){
        $("#sidebar").toggleClass("open");
        $("body").toggleClass("overflow-hidden");
    });

    if(window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone) {
        $('#installWebAppButton').addClass('d-none');
    } else {
        $('#installWebAppButton').click(function () {
            if (deferredPrompt) {
                deferredPrompt.prompt();
            }
        });
    }
});