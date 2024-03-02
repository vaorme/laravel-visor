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
function formattedUsername(username){
	const formatted = username.replace(/[^a-zA-Z0-9_]/g, '');
	return formatted.slice(0, 20);
};

function isValidEmail(email){
    const regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if(regex.test(email)){
        return true;
    }
    return false;
}
const isUrl = urlString => {
	const pattern = new RegExp(
        '^(https?:\\/\\/)?' + // protocol
          '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
          '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR IP (v4) address
          '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
          '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
          '(\\#[-a-z\\d_]*)?$', // fragment locator
        'i'
    );
    const expression = /(http(s)?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})/gi;
    const regex = new RegExp(expression);
    return urlString.match(regex);
}
function generateUniqueID() {
	return Math.random().toString(36).substring(2, 15) + Math.round(new Date().getTime() / 1000).toString(36);
}

export {
    sluggify,
    hasClass,
    toggleClass,
    addClass,
	isUrl,
    removeClass,
	isValidEmail,
	formattedUsername,
	generateUniqueID
}