import $ from "jquery";

$(document).ready(function(){
    const modal = $('#customModal');

    modal.on("trigger-custom-modal", function(event, title, body, footer = true, conf = {keyboard: true, backdrop: true}) {
        modal.modal(conf);
        modal.modal('show');
        $(".modal-title", modal).text(title);
        $(".modal-body", modal).html(body);
        if (!footer)
            $(".modal-footer", modal).hide();
        else
            $(".modal-footer", modal).show();
    });

    const spanFirstTime = $('span#trigger-first-time-modal');
    if(spanFirstTime.length === 1) {
        modal.trigger("trigger-custom-modal", [
            spanFirstTime.text(),
            `<div class="text-center">Te voila enfin ${spanFirstTime.data('user-firstname')} 🎉<br>` +
            `Tu peux commencer par modifier ton avatar:<br>` +
            `<a role="button" class="btn btn-light" href="${spanFirstTime.data('user-edit-link')}"><img class="avatar-medium" src="${spanFirstTime.data('user-avatar')}" alt="${spanFirstTime.data('user-firstname')} avatar"></a><br>` +
            `Ensuite tu pourras ajouter des amis à ta liste d'abonnements !</div>`,
            true,
            {keyboard: false, backdrop: 'static'}
        ]);
    }

    $('body').on('click', '[data-custom-confirm]', function () {
        const element = $(this);

        modal.trigger("trigger-custom-modal", [
            'Attention !',
            $('<div class="text-center">' +
                '<p class="custom-font-text">'+element.data('custom-confirm-text')+'</p><br>' +
                '<button id="modalConfirmButton" class="btn btn-danger mx-2 px-4" data-opinion-action="" data-relationship-action="">Oui</button>' +
                '<button class="btn btn-primary mx-2 px-4" data-dismiss="modal">Non</button>' +
                '</div>'),
            false
        ]);

        const modalConfirmButton = $('#modalConfirmButton');

        if (element.data('custom-confirm-configurations')['action']) {
            modalConfirmButton.data(`${element.data('custom-confirm-configurations')['entity']}-action`, `${element.data('custom-confirm-configurations')['action']}`);
        } else {
            modalConfirmButton.data(`${element.data('custom-confirm-configurations')['entity']}-action`, 'delete');
        }

        modalConfirmButton.data(`${element.data('custom-confirm-configurations')['entity']}-url`, element.data('custom-confirm-configurations')['url']);
    });
});