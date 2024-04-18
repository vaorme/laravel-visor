import '../../js/app';

import ownDropZone from "./own-dropzone";
import OwnValidator from './own-validator';

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

// ? ADS

const adspopoverLists = document.querySelectorAll('[data-bs-toggle="popover"]');

adspopoverLists?.forEach(popoverList => {
    const image = popoverList.getAttribute('data-bs-image');
    const popover = new bootstrap.Popover(popoverList, {
        container: 'body',
        trigger: "hover",
        html: true,
        content: function () {
            return '<img class="img-fluid" src="'+image+'" />';
        },
    });
});