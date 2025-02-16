import './bootstrap';

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

const tippyDelegate = delegate( '#app', {
    target: '[data-tippy-content]',
    arrow: false
});

hideTippyMovil();
window.addEventListener('resize', function(){
    hideTippyMovil();
})
function hideTippyMovil(){
    const windowWidth = window.innerWidth;
    if(windowWidth <= 1024){
        tippyDelegate[0].disable()
    }else{
        tippyDelegate[0].enable()
    }
}
