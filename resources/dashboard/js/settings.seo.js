import '../../js/app';

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