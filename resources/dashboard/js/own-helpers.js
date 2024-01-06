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
function sluggify(str){
    let ctRegex = /[^a-zA-Z0-9-]+/g;
    let strNoAccents = str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    let removeSc = strNoAccents.replace(ctRegex, ' ');
    let strNoSpaces = removeSc.trim();
    return strNoSpaces.replace(ctRegex, '-').replace(/--+/g, '-').toLowerCase();
}

export {
    sluggify,
    hasClass,
    toggleClass,
    addClass,
    removeClass
}