import '../../js/app';

import axios from "axios";
import { Sortable, Plugins } from '@shopify/draggable';

import ownDropZone from "./own-dropzone";
import OwnValidator from "./own-validator";
import { addClass, formattedUsername, generateUniqueID, isUrl, isValidEmail, removeClass, sluggify } from "./own-helpers";

axios.defaults.baseURL = axios.defaults.baseURL + '/space/users';

// ? PREVIEW COVER
const inputCoverPreview = document.querySelector('form.frmo input[name="cover_url"]');
inputCoverPreview?.addEventListener('change', coverPreview);

function coverPreview(e){
	const coverPreview = document.querySelector('form.frmo .cover-preview img');
	const urlPreview = e.target.value;
	removeClass(coverPreview, 'added');

	if(!isUrl(urlPreview)){
		return false;
	}
	coverPreview.src = urlPreview;
	coverPreview.onload = (e) =>{
		
	}
	coverPreview.onerror = (e) =>{
		Toastify({
			text: "Imagen invalida o enlace roto.",
			className: "error",
			duration: 5000,
			newWindow: true,
			close: true,
			gravity: "top",
			position: "center",
		}).showToast();
	}
	const getDimensions = (url) => new Promise((resolve, reject) => {
		const img = new Image();
		img.onload = () => resolve(img);
		img.onerror = (err) => reject(err);
		img.src = url;
	});

	// Usage example:
	(async() => {
		// const img = await getDimensions(urlPreview);
		// let sta = true;
		// if(img.naturalWidth > 1440){
		// 	Toastify({
		// 		text: "El ancho maximo debe ser de 1440px.",
		// 		className: "error",
		// 		duration: 5000,
		// 		newWindow: true,
		// 		close: true,
		// 		gravity: "top",
		// 		position: "center",
		// 	}).showToast();
		// 	coverPreview.setAttribute('data-validated', false);
		// 	sta = false;
		// }
		// if(img.naturalHeight > 380){
		// 	Toastify({
		// 		text: "El alto maximo debe ser de 380px.",
		// 		className: "error",
		// 		duration: 5000,
		// 		newWindow: true,
		// 		close: true,
		// 		gravity: "top",
		// 		position: "center",
		// 	}).showToast();
		// 	coverPreview.setAttribute('data-validated', false);
		// 	sta = false;
		// }

		// if(!sta){
		// 	return false;
		// }

		addClass(coverPreview, 'added');
		coverPreview.setAttribute('data-validated', true);
	})();
}

// ? SOCIAL LINKS
const socialLinkButton = document.querySelector('form.frmo .social-links a.btn-add');
socialLinkButton?.addEventListener('click', addSocial);

document.addEventListener("click", function(e) {
	if (e.target.classList.contains("btn-remove")) {
		e.preventDefault();
		const target = e.target;
		const id = target.getAttribute('href');

		const element = document.querySelector(id);
		element.remove();
	}
});

function addSocial(e){
	e.preventDefault();
	const socialLinks = document.querySelector('form.frmo .social-links .row-cards');
	const inputSocialLink = document.querySelector('form.frmo .social-links input[name="add_social"]');
	if(inputSocialLink.value === ""){
		Toastify({
			text: "Campo enlace es requerido.",
			className: "error",
			duration: 5000,
			newWindow: true,
			close: true,
			gravity: "top",
			position: "center",
		}).showToast();
		return true;
	}

	// ? VALIDATE URL

	// ? CREATE ITEM
	const items = document.querySelectorAll('.social-item');
	const uniqueID = generateUniqueID();
	const item = document.createElement('div');
	item.classList.add('social-item', 'col-12', 'row', 'g-2');
	item.setAttribute('id', 'item-'+uniqueID);
	item.innerHTML = `
		<div class="col">
			<input type="text" name="social[]" class="form-control" value="${inputSocialLink.value}">
		</div>
		<div class="col-auto">
			<a href="#item-${uniqueID}" class="btn btn-remove btn-icon btn-danger">
				<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
			</a>
		</div>
	`;
	socialLinks.append(item);

	inputSocialLink.value = "";
}

// ? CREATE COMIC
let validator;
const frmo = document.querySelector('form.frmo');
frmo?.addEventListener('submit', function(e){
    e.preventDefault();
});

// ?: USERNAME VALIDATOR
const inputName = document.querySelector('form.frmo:not(.update) input[name="name"]');
const inputUserName = document.querySelector('form.frmo input[name="username"]');
inputName?.addEventListener('input', function(){
    inputUserName.value = formattedUsername(inputName.value);

	// *: MANUALLY TRIGGER INPUT EVENT ON INPUT USERNAME
	const inputEvent = new Event('input', { bubbles: true });
	inputUserName.dispatchEvent(inputEvent);
});
inputUserName?.addEventListener('input', function(){
    inputUserName.value = formattedUsername(inputUserName.value);
});

// ?: PASSWORD VALIDATION
const inputPassword = document.querySelector('form.frmo input[name="password"]');
const inputConfirmPassword = document.querySelector('form.frmo input[name="confirm_password"]');
const passwordFields = {
	'letter': document.querySelector(".password-validation #letter"),
	'capital': document.querySelector(".password-validation #capital"),
	'number': document.querySelector(".password-validation #number"),
	'length': document.querySelector(".password-validation #length")
};
let passwordValidated = false;
inputPassword?.addEventListener('keyup', function () {
	// *: VALIDATE LOWER LETTERS
	let lowerCaseLetters = /[a-z]/g;
	if (inputPassword.value.match(lowerCaseLetters)) {
		removeClass(passwordFields.letter, "invalid");
		addClass(passwordFields.letter, "valid");
		passwordValidated = true;
	} else {
		removeClass(passwordFields.letter, "valid");
		addClass(passwordFields.letter, "invalid");
		passwordValidated = false;
	}

	// *: VALIDATE CAPITAL LETTERS
	let upperCaseLetters = /[A-Z]/g;
	if (inputPassword.value.match(upperCaseLetters)) {
		removeClass(passwordFields.capital, "invalid");
		addClass(passwordFields.capital, "valid");
		passwordValidated = true;
	} else {
		removeClass(passwordFields.capital, "valid");
		addClass(passwordFields.capital, "invalid");
		passwordValidated = false;
	}

	// *: VALIDATE NUMBERS
	let numbers = /[0-9]/g;
	if (inputPassword.value.match(numbers)) {
		removeClass(passwordFields.number, "invalid");
		addClass(passwordFields.number, "valid");
		passwordValidated = true;
	} else {
		removeClass(passwordFields.number, "valid");
		addClass(passwordFields.number, "invalid");
		passwordValidated = false;
	}

	// Validate length
	if (inputPassword.value.length >= 8) {
		removeClass(passwordFields.length, "invalid");
		addClass(passwordFields.length, "valid");
		passwordValidated = true;
	} else {
		removeClass(passwordFields.length, "valid");
		addClass(passwordFields.length, "invalid");
		passwordValidated = false;
	}
});

// ? EMAIL VALIDATOR
const inputEmail = document.querySelector('form.frmo input[name="email"]');
inputEmail?.addEventListener('change', function(){
	if(!isValidEmail(this.value)){
		Toastify({
			text: "Ingresa un email valido.",
			className: "error",
			duration: 5000,
			newWindow: true,
			close: true,
			gravity: "top",
			position: "center",
		}).showToast();
		return false;
	}
});

// ?: HANDLE SUBMIT FORM
if(frmo){
    validator = new OwnValidator(frmo);
    validator.formValidationOnChange();
}
const btnSubmit = document.querySelector('button.btn-submit');
btnSubmit?.addEventListener('click', function(){
    const formData = new FormData(frmo);
    if(!validator.formValidation()){
		return true;
    }

	if(!passwordValidated){
		Toastify({
			text: "Contraseña invalida.",
			className: "error",
			duration: 4000,
			newWindow: true,
			close: true,
			gravity: "top",
			position: "center",
		}).showToast();
		return false;
	}
	if(inputConfirmPassword.value !== inputPassword.value){
		Toastify({
			text: "Las contraseñas deben coincidir.",
			className: "error",
			duration: 4000,
			newWindow: true,
			close: true,
			gravity: "top",
			position: "center",
		}).showToast();
		return false;
	}
	console.log('paso todo');
	return true;
	frmo?.submit();
});

// ? DELETE ITEM
const modalDestroy = document.getElementById('modal-destroy')
let btpModalDestroy = modalDestroy? new bootstrap.Modal(modalDestroy) : '';
modalDestroy?.addEventListener('show.bs.modal', e => {
    const id = e.relatedTarget.getAttribute('data-id');
    const button = document.querySelector('#buttonConfirm');
    button.setAttribute('data-id', id);
    button?.addEventListener('click', itemDestroy);
})

async function itemDestroy(){
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
        const message = response.data.msg;
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