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
            `Tu peux commencer par modifier ton avatar <br>` +
            `<a role="button" class="btn btn-light" href="${spanFirstTime.data('user-edit-link')}"><img class="avatar-medium" src="${spanFirstTime.data('user-avatar')}" alt="${spanFirstTime.data('user-firstname')} avatar"></a><br>` +
            `Ensuite tu pourras ajouter des amis à ta liste d'abonnements`,
            {keyboard: false, backdrop: 'static'}
        ]);
    }

});