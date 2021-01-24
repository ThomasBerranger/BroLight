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
            `Te voila enfin ${spanFirstTime.data('user-firstname')} ! <i class="far fa-laugh-beam"></i><br>` +
            `Tu peux commencer par modifier ton avatar <br>` +
            `<a role="button" class="btn btn-light" href="${spanFirstTime.data('user-edit-link')}"><img class="avatar-medium" src="${spanFirstTime.data('user-avatar')}" alt="${spanFirstTime.data('user-firstname')} avatar"></a><br>` +
            `Ensuite tu pourras ajouter des amis à ta liste d'abonnements`,
            {keyboard: false, backdrop: 'static'}
        ]);
    }

    $('body').on('click', '[data-custom-confirm]', function () {
        const element = $(this);

        // todo : refactoring data-***-action
        modal.trigger("trigger-custom-modal", [
            'Attention !',
            $('<div class="text-center">' +
                '<p>Toute suppression est définitive !</p>' +
                '<p>Êtes vous sûr de ce que vous faites ?</p><br>' +
                '<button id="modalConfirmButton" class="btn btn-danger mx-2 px-4" data-comment-action="" data-user-relationship-action="">Confirmer</button>' +
                '<button class="btn btn-info mx-2 px-4" data-dismiss="modal">Annuler</button>' +
                '</div>'),
            false
        ]);

        $('#modalConfirmButton').data(`${element.data('custom-confirm-configurations')['entity']}-action`, 'delete');
        $('#modalConfirmButton').data(`${element.data('custom-confirm-configurations')['entity']}-url`, element.data('custom-confirm-configurations')['url']);
    });

});