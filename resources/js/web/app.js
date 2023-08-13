import '../library';

document.addEventListener('DOMContentLoaded', function(){
    
});

// :BURGER MENu
const burger = document.querySelector('header.header nav.navbar .burger');
if(burger){
    const menu = document.querySelector('header.header nav.navbar .menu');
    window.addEventListener('click', function(){
        burger.removeClass('active');
        menu.removeClass('open');
        removeOverlay();
    });
    burger.addEventListener('click', function(e){
        e.stopPropagation();
        burger.toggleClass('active');
        menu.toggleClass('open');
        createOverlay();
    });
}
function createOverlay(){
    const bdy = document.querySelector('body');
    const ovl = document.createElement('div');
    ovl.addClass('mnu__overlay');

    bdy.append(ovl);
}
function removeOverlay(){
    const ovl = document.querySelector('.mnu__overlay');
    if(ovl){
        ovl.remove();
    }
}