import './styles/app.scss';

import 'bootstrap';

import './js/functions/serializePrefixedFormJSON';

import './js/components/alert';
import './js/components/modal';
import './js/components/sidebar';
import './js/components/rate';
import './js/components/tooltip';

import './js/requests/relationRequest';
import './js/requests/opinionRequest';
import './js/requests/viewRequest';
import './js/requests/commentRequest';
import './js/requests/avatarRequest';
import './js/requests/podiumRequest';

if('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js', { scope: '/' }).then();
    navigator.serviceWorker.ready.then();
}