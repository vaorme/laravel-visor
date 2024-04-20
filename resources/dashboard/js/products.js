import '../../js/app';

import ownDropZone from "./own-dropzone";
import OwnValidator from './own-validator';

const urlAxios = window.location.origin;

axios.defaults.baseURL = urlAxios + '/space/products';

// * DROPZONE COVER COMIC
const allowedExtensions = ['jpg', 'png', 'gif', 'webp'];
ownDropZone('.own-dropzone .dz-choose', allowedExtensions);

// ? SETTINGS FORM
const frmo = document.querySelector('form.frmo');
frmo?.addEventListener('submit', function(e){
    e.preventDefault();
});
let validator;
if(frmo){
    validator = new OwnValidator(frmo);
    validator.comicValidateOnChange();
}

// ? SUBMIT BUTTON FORM
const btnSubmit = document.querySelector('button.btn-submit');
btnSubmit?.addEventListener('click', function(){
    const formData = new FormData(frmo);
    if(validator.comicValidate()){
        frmo?.submit();
    }
});

// ? PRODUCT DELETE
const modalDestroy = document.getElementById('modal-destroy')
let btpModalDestroy = modalDestroy? new bootstrap.Modal(modalDestroy) : '';
modalDestroy?.addEventListener('show.bs.modal', e => {
    const id = e.relatedTarget.getAttribute('data-id');
    const button = document.querySelector('#buttonConfirm');
    button.setAttribute('data-id', id);
    button?.addEventListener('click', productDestroy);
})

async function productDestroy(){
    const id = this.getAttribute('data-id');
    this.disabled = true;
    let buttonText = this.textContent;
    this.innerHTML = `
        ${buttonText}
        <span class="input-icon-addon">
            <div class="spinner-border spinner-border-sm text-white" role="status"></div>
        </span>
    `;
    await axios.delete("/"+id).then(function (response){
        const message = response.data.message;
        if(message){
            Toastify({
                className: 'success',
                text: message,
                duration: 1000,
                newWindow: false,
                close: true,
                gravity: "top",
                position: "right"
            }).showToast();
            setTimeout(() => {
                window.location.reload();
            }, 600);
        }else{
            console.log(response);
        }
    }).catch(function (error){
        console.log('error:', error);
        const message = error.response.data.message;
        if(message){
            Toastify({
                className: 'error',
                text: message,
                duration: 3000,
                newWindow: false,
                close: true,
                gravity: "top",
                position: "right"
            }).showToast();
        }else{
            console.log(error);
        }
    });
    btpModalDestroy.hide();
    this.disabled = false;
    this.removeAttribute('data-id');
    this.innerHTML = `
        ${buttonText}
    `;
};