import './bootstrap';
import './helpers/helpers'

// :ALPINE

import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';

// :TOASTIFY

import Toastify from 'toastify-js'
import "toastify-js/src/toastify.css";

import { delegate } from 'tippy.js';
import 'tippy.js/dist/tippy.css'; // optional for styling

window.Alpine = Alpine;
window.Toastify = Toastify;

Alpine.plugin(persist);
Alpine.start();

delegate( '#app', {
    target: '[data-tippy-content]',
    arrow: false
});