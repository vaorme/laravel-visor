
import '../../helpers/helpers';

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
            inputElement.files = e.dataTransfer.files;
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

function removeBodyScroll(){
    let bdy = document.querySelector('body');
    bdy.addClass('noscroll');
}
function clearBodyScroll(){
    let bdy = document.querySelector('body');
    bdy.removeClass('noscroll');
}

function validateEmail(email){
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

class Modalerts{
    fire(opt){
        let tis = '';
        let icon;
        let app = document.querySelector('#app');
        let createAlert = document.createElement('div');
        let createOverlay = document.createElement('div');
        createOverlay.addClass('al-overlay');
        createAlert.addClass('alertify');
        if(opt.type == "error"){
            createAlert.addClass(opt.type);
            icon = "sin icono error";
        }
        if(opt.type == "warning"){
            createAlert.addClass(opt.type);
            icon = `
                <svg aria-labelledby="errorIconTitle" color="#d62323" fill="none" height="48px" role="img" stroke="#d62323" stroke-linecap="square" stroke-linejoin="miter" stroke-width="1" viewBox="0 0 24 24" width="48px" xmlns="http://www.w3.org/2000/svg"><title id="errorIconTitle"/><path d="M12 8L12 13"/><line x1="12" x2="12" y1="16" y2="16"/><circle cx="12" cy="12" r="10"/></svg>
            `;
        }
        if(opt.type == "success"){
            createAlert.addClass(opt.type);
            icon = "sin icono success";
        }
        createAlert.innerHTML = `
            <div class="md-icon">
                ${icon}
            </div>
            <div class="md-content">
                <h2>${opt.title}</h2>
                <p>${opt.text}</p>
            </div>
            <div class="md-buttons">
                ${(opt.confirmButtonText)? '<button class="botn confirm">'+opt.confirmButtonText+'</button>': '' }
                <button class="botn cancel">${(opt.cancelButtonText)? opt.cancelButtonText : 'Close' }</button>
            </div>
        `;
        app.append(createOverlay);
        app.append(createAlert);

        let alertify = document.querySelector('.alertify');
        let over = document.querySelector('.al-overlay');

        let buttonConfirm = document.querySelector('.alertify button.confirm');
        let buttonCancel = document.querySelector('.alertify button.cancel');
        tis = this;

        return new Promise((resolve, reject) => {
            if(buttonConfirm){
                buttonConfirm.addEventListener('click', function(){
                    resolve({confirmed: true});
                });
            }
            if(buttonCancel){
                buttonCancel.addEventListener('click', function(){
                    tis.close(alertify, over);
                });
            }
        })
        
    }
    close(alert, over){
        let element = alert;
        let overlay = over;
        element.addClass('closing');
        overlay.addClass('closing');
        setTimeout(function(){
            element.remove();
            overlay.remove();
        }, 300);

        clearTimeout();
    }
}

export { dropZone, removeBodyScroll, clearBodyScroll, validateEmail, isUrl, Modalerts };