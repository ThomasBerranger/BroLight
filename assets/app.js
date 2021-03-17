import './styles/app.scss';

import 'bootstrap';

import './js/functions/serializePrefixedFormJSON';

import './js/components/alert';
import './js/components/modal';
import './js/components/rate';
import './js/components/search';
import './js/components/sidebar';
import './js/components/spoilerGuard';
import './js/components/tooltip';

import './js/requests/avatarRequest';
import './js/requests/opinionRequest';
import './js/requests/podiumRequest';
import './js/requests/relationRequest';
import './js/requests/wishRequest';

if('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js', { scope: '/' }).then();
    navigator.serviceWorker.ready.then();
}

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})