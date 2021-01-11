/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application

import 'bootstrap';

import './js/functions/serializePrefixedFormJSON';

import './js/components/alert';
import './js/components/modal';
import './js/components/sidebar';
import './js/components/rate';

import './js/requests/userRelationRequest';
import './js/requests/viewRequest';
import './js/requests/commentRequest';
import './js/requests/avatarRequest';