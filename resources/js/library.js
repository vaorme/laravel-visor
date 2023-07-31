import './bootstrap';
import './helpers/helpers'

// :ALPINE

import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';

// :TOASTIFY

import Toastify from 'toastify-js'

import { delegate } from 'tippy.js';

window.Alpine = Alpine;
window.Toastify = Toastify;

Alpine.plugin(persist);
Alpine.start();

delegate( '#app', {
    target: '[data-tippy-content]',
    arrow: false
});