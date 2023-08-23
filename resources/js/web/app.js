import '../library';
import { addClass, removeClass, toggleClass } from '../helpers/helpers';

document.addEventListener('DOMContentLoaded', () => {
    
});

// :BURGER MENu
const burger = document.querySelector('header.header nav.navbar .burger');
if(burger){
    const menu = document.querySelector('header.header nav.navbar .menu');
    window.addEventListener('click', function(){
        removeClass(burger, 'active');
        removeClass(menu, 'open');
        removeOverlay();
    });
    burger.addEventListener('click', function(e){
        e.stopPropagation();
        toggleClass(burger, 'active');
        toggleClass(menu, 'open');
        createOverlay();
    });
}
function createOverlay(){
    const bdy = document.querySelector('body');
    const ovl = document.createElement('div');
    addClass(ovl, 'mnu__overlay');

    bdy.append(ovl);
}
function removeOverlay(){
    const ovl = document.querySelector('.mnu__overlay');
    if(ovl){
        ovl.remove();
    }
}