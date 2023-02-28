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

function dropZone(zone, allowed){
    const drop = document.querySelector(zone);
    let inputElement, img;
    if(drop){
        inputElement = drop.nextElementSibling;
        Object.values(drop.children).forEach(val => {
            if(val.hasClass('preview')){
                img = val.children[0];
            }    
        });

        inputElement.addEventListener('change', function (e) {
            const file = this.files[0];
            dropFile(file, img, allowed);
        })
        drop.addEventListener('click', () => inputElement.click());
        drop.addEventListener('dragover', (e) => {
            e.preventDefault();
        });
        drop.addEventListener('drop', (e) => {
            e.preventDefault();
            const file = e.dataTransfer.files[0];
            dropFile(file, img, allowed);
        });
    }
}
function dropFile(file, preview, allowed){
    if(!file) {
        console.log('error, no hay archivo');
        return 'error';
    }
    let extension = file.name.split('.').pop().toLowerCase();
    if(!allowed.includes(extension)){
        alert('Tipo de archivo no permitido');
        return 'no allowed';
    }
    preview.removeClass('added');
    setTimeout(() => {
        preview.addClass('added');
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onloadend = function () {
            const result = reader.result;
            let src = this.result;
            preview.src = src;
            preview.alt = file.name
        }
    }, 200);
}

function sanitizeText(text){
    
}

export { dropZone };