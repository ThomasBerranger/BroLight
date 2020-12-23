import $ from "jquery";

$(document).ready(function(){
    const modal = $('#headerModal');

    modal.on("trigger-header-modal", function(event, title, body, conf = {keyboard: true, backdrop: true}) {
        modal.modal(conf)
        modal.modal('show')
        $(".modal-title", modal).text(title);
        $(".modal-body", modal).html(body);
    });

    const spanFirstTime = $('span#trigger-first-time-modal');
    if(spanFirstTime.length === 1) {
        modal.trigger("trigger-header-modal", [
            spanFirstTime.text(),
            `Te voila enfin ${spanFirstTime.data('user-firstname')} ! <i class="far fa-laugh-beam"></i><br>` +
            `Tu peux commencer par venir modifier ton avatar <a href="${spanFirstTime.data('user-edit-link')}">ici</a>`,
            {keyboard: false, backdrop: 'static'}
        ]);
    }

});