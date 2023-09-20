const hasClass = function(element, n){
    if(element.classList.contains(n)){
        return true;
    }
    return false;
}
const toggleClass = function(element, n){
    if(element){
        let classes = n.split(' ');
        classes.forEach(i => {
            element.classList.toggle(i);
        })
    }
}
const addClass = function(element, n){
    if(element){
        let classes = n.split(' ');
        classes.forEach(i => {
            element.classList.add(i)
        })
    }
}
const removeClass = function(element, n){
    if(element){
        let classes = n.split(' ');
        classes.forEach(i => {
            element.classList.remove(i)
        })
    }
}

let slideUp = (target, duration=500) => {
    target.style.transitionProperty = 'height, margin, padding';
    target.style.transitionDuration = duration + 'ms';
    target.style.boxSizing = 'border-box';
    target.style.height = target.offsetHeight + 'px';
    target.offsetHeight;
    target.style.overflow = 'hidden';
    target.style.height = 0;
    target.style.paddingTop = 0;
    target.style.paddingBottom = 0;
    target.style.marginTop = 0;
    target.style.marginBottom = 0;
    window.setTimeout( () => {
    target.style.display = 'none';
    target.style.removeProperty('height');
    target.style.removeProperty('padding-top');
    target.style.removeProperty('padding-bottom');
    target.style.removeProperty('margin-top');
    target.style.removeProperty('margin-bottom');
    target.style.removeProperty('overflow');
    target.style.removeProperty('transition-duration');
    target.style.removeProperty('transition-property');
    //alert("!");
    }, duration);
}

let slideDown = (target, duration=500) => {
    target.style.removeProperty('display');
    let display = window.getComputedStyle(target).display;

    if (display === 'none')
    display = 'block';

    target.style.display = display;
    let height = target.offsetHeight;
    target.style.overflow = 'hidden';
    target.style.height = 0;
    target.style.paddingTop = 0;
    target.style.paddingBottom = 0;
    target.style.marginTop = 0;
    target.style.marginBottom = 0;
    target.offsetHeight;
    target.style.boxSizing = 'border-box';
    target.style.transitionProperty = "height, margin, padding";
    target.style.transitionDuration = duration + 'ms';
    target.style.height = height + 'px';
    target.style.removeProperty('padding-top');
    target.style.removeProperty('padding-bottom');
    target.style.removeProperty('margin-top');
    target.style.removeProperty('margin-bottom');
    window.setTimeout( () => {
    target.style.removeProperty('height');
    target.style.removeProperty('overflow');
    target.style.removeProperty('transition-duration');
    target.style.removeProperty('transition-property');
    }, duration);
}
let slideToggle = (target, duration = 500) => {
    if (window.getComputedStyle(target).display === 'none') {
        return slideDown(target, duration);
    } else {
        return slideUp(target, duration);
    }
}
function sluggify(str){
    let ctRegex = /[^a-zA-Z0-9]+/g;
    let strNoAccents = str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    let removeSc = strNoAccents.replace(ctRegex, ' ');
    let strNoSpaces = removeSc.trim();
    return strNoSpaces.replace(ctRegex, '-').toLowerCase();
}

export {
    sluggify,
    hasClass,
    toggleClass,
    addClass,
    removeClass,
    slideDown,
    slideUp,
    slideToggle
}