import './bootstrap';

// :ALPINE
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
    
// :TOASTIFY
import Toastify from 'toastify-js'

window.Alpine = Alpine;
window.Toastify = Toastify;

Alpine.plugin(persist);
Alpine.start();