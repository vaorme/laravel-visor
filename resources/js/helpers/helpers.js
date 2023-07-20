const hasClass = function(n){
    if(this.classList.contains(n)){
        return true;
    }
    return false;
}
const toggleClass = function(n){
    let classes = n.split(' ');
    classes.forEach(i => {
        this.classList.toggle(i);
    })
}
const addClass = function(n){
    let classes = n.split(' ');
    classes.forEach(i => {
        this.classList.add(i)
    })
}
const removeClass = function(n){
    let classes = n.split(' ');
    classes.forEach(i => {
        this.classList.remove(i)
    })
}

Object.prototype.hasClass = hasClass;
Object.prototype.toggleClass = toggleClass;
Object.prototype.addClass = addClass;
Object.prototype.removeClass = removeClass;