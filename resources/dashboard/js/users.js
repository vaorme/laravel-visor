import '../../js/app';

import axios from "axios";
import { Sortable, Plugins } from '@shopify/draggable';

import ownDropZone from "./own-dropzone";
import OwnValidator from "./own-validator";
import { addClass, formattedUsername, generateUniqueID, hasClass, isUrl, isValidEmail, removeClass, sluggify } from "./own-helpers";

const urlAxios = window.location.origin;

axios.defaults.baseURL = urlAxios + '/space/users';

// ?: AVATAR

// * DROPZONE COVER COMIC
const allowedExtensions = ['jpg', 'png', 'gif', 'webp'];
ownDropZone('.own-dropzone .dz-choose', allowedExtensions);

const divAvatarActions = document.querySelector('form.frmo .avatar-actions');
const avatarImgPreview = document.querySelector('form.frmo .dz-choose .dz-preview img');
const inputDz = document.querySelector('form.frmo .own-dropzone input.dz-input');
inputDz?.addEventListener('change', function(){
	const files = this.files;
	if(files.length > 0){
		const extension = files[0].name.split('.').pop().toLowerCase();
		if(!allowedExtensions.includes(extension)){
			return false;
		}
		divAvatarActions.innerHTML = `
			<a href="javascript:void(0);" class="card-btn btn-primary action-change">
				<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-photo-edit me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 8h.01" /><path d="M11 20h-4a3 3 0 0 1 -3 -3v-10a3 3 0 0 1 3 -3h10a3 3 0 0 1 3 3v4" /><path d="M4 15l4 -4c.928 -.893 2.072 -.893 3 0l3 3" /><path d="M14 14l1 -1c.31 -.298 .644 -.497 .987 -.596" /><path d="M18.42 15.61a2.1 2.1 0 0 1 2.97 2.97l-3.39 3.42h-3v-3l3.42 -3.39z" /></svg>
				Cambiar
			</a>
			<a href="javascript:void(0);" class="card-btn action-remove">
				<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-rounded-minus me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 12h6" /><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" /></svg>
				Eliminar
			</a>
		`;
	}else{
		divAvatarActions.innerHTML = "";
	}
});

document.addEventListener("click", function(e) {
	if (e.target.classList.contains("action-remove")) {
		e.preventDefault();
		const inputCurrentAvatar = document.querySelector('form.frmo input[name="current_avatar"]');

		avatarImgPreview.src = "";
		removeClass(avatarImgPreview, 'show');
		
		inputDz.value = "";
		if(inputCurrentAvatar){
			inputCurrentAvatar.value = "";
		}

		// *: MANUALLY TRIGGER INPUT EVENT ON INPUT SLUG
		const inputEvent = new Event('change', { bubbles: true });
		inputDz.dispatchEvent(inputEvent);
	}
	if (e.target.classList.contains("action-change")) {
		e.preventDefault();
		inputDz.click();
	}
});


// ?: PREVIEW COVER
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
	(async() => {
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

// ? CREATE/UPDATE COMIC
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
const inputConfirmPassword = document.querySelector('form.frmo input[name="password_confirmation"]');
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

// ?: CHANGE PASSWORD
const buttonChangePassword = document.querySelector('form.update a#btnChangePassword');
buttonChangePassword?.addEventListener('click', changePassword);

async function changePassword(){
	if(inputPassword.value === ""){
		Toastify({
			text: "El campo contraseña no puede estar vacio.",
			className: "error",
			duration: 4000,
			newWindow: true,
			close: true,
			gravity: "top",
			position: "center",
		}).showToast();
		inputPassword.focus();
		addClass(inputPassword, 'is-invalid');
		return false;
	}
	if(inputConfirmPassword.value === ""){
		Toastify({
			text: "El campo Confirmar contraseña no puede estar vacío.",
			className: "error",
			duration: 4000,
			newWindow: true,
			close: true,
			gravity: "top",
			position: "center",
		}).showToast();
		inputConfirmPassword.focus();
		addClass(inputConfirmPassword, 'is-invalid');
		return false;
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
	const id = document.querySelector('form.update input[name="user_id"]');
	
	await axios.put("/change-password", {
		_method: 'PUT',
		id: id.value,
		password: inputPassword.value,
		password_confirmation: inputConfirmPassword.value
	}).then(function (response){
		const data = response.data;
		if(data && data.status){
			Toastify({
				className: 'success',
				text: data.message,
				duration: 1000,
				newWindow: false,
				close: true,
				gravity: "top",
				position: "center"
			}).showToast();
		}else if(data && !data.status){
			Toastify({
				className: 'error',
				text: data.message,
				duration: 3000,
				newWindow: false,
				close: true,
				gravity: "top",
				position: "center"
			}).showToast();
		}
	}).catch(function (error){
		console.log(error);
	});
	
	passwordFields.letter.classList.remove('valid');
	passwordFields.letter.classList.add('invalid');
	passwordFields.capital.classList.remove('valid');
	passwordFields.capital.classList.add('invalid');
	passwordFields.number.classList.remove('valid');
	passwordFields.number.classList.add('invalid');
	passwordFields.length.classList.remove('valid');
	passwordFields.length.classList.add('invalid');

	inputPassword.value = "";
	inputConfirmPassword.value = "";
}

// ?: HANDLE SUBMIT FORM

if(frmo){
    validator = new OwnValidator(frmo);
    validator.formValidationOnChange();
}
const btnSubmit = document.querySelector('button.btn-submit');
btnSubmit?.addEventListener('click', function(){
    if(!validator.formValidation()){
		return true;
    }
	const formData = new FormData(frmo);
	if(!hasClass(frmo, 'update')){
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
	}
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
				const newUrl = window.location.origin;
				window.location.href = newUrl+"/space/users";
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
// ? CLICK ACTIVATE/DEACTIVATE ACCOUNT
document.addEventListener('click', function(event) {
	if (event.target.matches('a#deactivateAccount')) {
		event.preventDefault();
		deactivateAccount();
	}
	if (event.target.matches('a#activateAccount')) {
		event.preventDefault();
		activateAccount();
	}
});
// ?: ACTIVATE ACCOUNT
async function activateAccount(){
	const buttonActivateAccount = document.getElementById('activateAccount');
	const id = document.querySelector('form.update input[name="user_id"]');
	await axios.put("/activate-account", {
		_method: 'PUT',
		id: id.value
	}).then(function (response){
		console.log(response);
		const data = response.data;
		if(data && data.status){
			Toastify({
				className: 'success',
				text: data.message,
				duration: 1000,
				newWindow: false,
				close: true,
				gravity: "top",
				position: "center"
			}).showToast();
			const buttonDeactivateAccount = document.createElement('a');
			buttonDeactivateAccount.classList.add('btn', 'btn-danger', 'w-100');
			buttonDeactivateAccount.setAttribute('href', 'javascript:void(0);');
			buttonDeactivateAccount.setAttribute('id', 'deactivateAccount');
			buttonDeactivateAccount.innerHTML = `
				<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-rotated-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9.793 2.893l-6.9 6.9c-1.172 1.171 -1.172 3.243 0 4.414l6.9 6.9c1.171 1.172 3.243 1.172 4.414 0l6.9 -6.9c1.172 -1.171 1.172 -3.243 0 -4.414l-6.9 -6.9c-1.171 -1.172 -3.243 -1.172 -4.414 0z" stroke-width="0" fill="currentColor" /></svg>
				Desactivar cuenta
			`;
			
			buttonActivateAccount.insertAdjacentElement('afterend',buttonDeactivateAccount);
			buttonActivateAccount.remove();
		}else if(data && !data.status){
			Toastify({
				className: 'error',
				text: data.message,
				duration: 3000,
				newWindow: false,
				close: true,
				gravity: "top",
				position: "center"
			}).showToast();
		}
	}).catch(function (error){
		console.log(error);
	});
}

// ?: DEACTIVATE ACCOUNT
async function deactivateAccount(){
	const buttonDeactivateAccount = document.getElementById('deactivateAccount');
	const id = document.querySelector('form.update input[name="user_id"]');
	await axios.put("/deactivate-account", {
		_method: 'PUT',
		id: id.value
	}).then(function (response){
		const data = response.data;
		if(data && data.status){
			Toastify({
				className: 'success',
				text: data.message,
				duration: 1000,
				newWindow: false,
				close: true,
				gravity: "top",
				position: "center"
			}).showToast();
			const buttonActivateAccount = document.createElement('a');
			buttonActivateAccount.classList.add('btn', 'btn-green', 'w-100');
			buttonActivateAccount.setAttribute('href', 'javascript:void(0);');
			buttonActivateAccount.setAttribute('id', 'activateAccount');
			buttonActivateAccount.innerHTML = `
				<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-rotated" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M13.446 2.6l7.955 7.954a2.045 2.045 0 0 1 0 2.892l-7.955 7.955a2.045 2.045 0 0 1 -2.892 0l-7.955 -7.955a2.045 2.045 0 0 1 0 -2.892l7.955 -7.955a2.045 2.045 0 0 1 2.892 0z"></path></svg>
				Activar cuenta
			`;
			
			buttonDeactivateAccount.insertAdjacentElement('afterend',buttonActivateAccount);
			buttonDeactivateAccount.remove();
		}else if(data && !data.status){
			Toastify({
				className: 'error',
				text: data.message,
				duration: 3000,
				newWindow: false,
				close: true,
				gravity: "top",
				position: "center"
			}).showToast();
		}
	}).catch(function (error){
		console.log(error);
	});
}

// ? ASSIGN COINS/DAYS
const itemModal = document.getElementById('modalCoinsAds');
const coinsAdsModal = itemModal? new bootstrap.Modal(itemModal) : '';
itemModal?.addEventListener('hide.bs.modal', () =>{
	const modal = document.querySelector('#modalCoinsAds .modal-content');
	const buttonConfirm = document.querySelector('#buttonModalSubmit');
	buttonConfirm?.removeEventListener('click', modalContentFormSubmit);
	setTimeout(() => {
		modal.innerHTML = "";
	}, 500);
});

itemModal?.addEventListener('show.bs.modal', async (e) => {
	const type = e.relatedTarget.getAttribute('data-type');
	const id = document.querySelector('form.update input[name="user_id"]');
	switch (type) {
		case "coins":
			await axios.get("/get-coins/"+id.value).then(function (response){
				let res = response.data;
				if(res.status){
					modalContentForm('coins', (res.data)? res.data.coins : 0);
				}else{
					Toastify({
						className: 'error',
						text: res.data.message,
						duration: 3000,
						newWindow: false,
						close: true,
						gravity: "top",
						position: "center"
					}).showToast();
				}
			}).catch(function (error){
				console.log(error);
			});
			break;
		case "days":
			await axios.get("/get-days/"+id.value).then(function (response){
				let res = response.data;
				if(res.status){
					modalContentForm('days', (res.data)? res.data.days_without_ads : 0);
				}else{
					Toastify({
						className: 'error',
						text: res.data.message,
						duration: 3000,
						newWindow: false,
						close: true,
						gravity: "top",
						position: "center"
					}).showToast();
				}
			}).catch(function (error){
				console.log(error);
			});
			break;
	
		default:
			break;
	}
	// ? SEND CHAPTER FORM
	const button = document.querySelector('#buttonModalSubmit');
	button?.addEventListener('click', modalContentFormSubmit);
})

function modalContentForm(type, amount = 0){
	const element = document.querySelector('#modalCoinsAds .modal-content');
	element.innerHTML = `
		<div class="modal-header">
			<h5 class="modal-title">Asignar ${type === "coins"? 'monedas' : 'días'}</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<form action="" id="modalForm" class="frmo-modal" enctype="multipart/form-data">
				<input type="hidden" name="type" value="${type}">
				<div class="row row-cards">
					<div class="col-3">
						<label class="form-label">Disminuir</label>
						<div class="row row-cards">
							<div class="col-6">
								<a href="javascript:void(0);" class="btn btn-danger btn-minus w-100">-5</a>
							</div>
							<div class="col-6">
								<a href="javascript:void(0);" class="btn btn-danger btn-minus w-100">-1</a>
							</div>
						</div>
					</div>
					<div class="col-6">
						<label class="form-label required">Cantidad</label>
						<input name="amount" type="number" class="form-control" value="${amount}">
					</div>
					<div class="col-3">
						<label class="form-label">Aumentar</label>
						<div class="row row-cards">
							<div class="col-6">
								<a href="javascript:void(0);" class="btn btn-green btn-plus w-100">+1</a>
							</div>
							<div class="col-6">
								<a href="javascript:void(0);" class="btn btn-green btn-plus w-100">+5</a>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn me-auto" data-bs-dismiss="modal">Cerrar</button>
			<button type="button" id="buttonModalSubmit" class="position-relative btn btn-primary">Asignar</button>
		</div>
	`;

	// ?: PREVENT FROM SUBMISSION
	const frmo = document.querySelector('form#modalForm');
	frmo?.addEventListener('submit', function(e){
		e.preventDefault();
	});
}

// * INCREMENT AMOUNT COINS/DAYS

document.addEventListener('click', function(event){
	if (event.target.matches('a.btn-minus')) {
		event.preventDefault();
		const amountInput = document.querySelector('#modalCoinsAds form.frmo-modal input[name="amount"]');
		const txt = event.target.textContent;
		const sub = (txt === "-1")? 1 : 5;
		const newValue = (amountInput.value - sub) < 0? 0 : (amountInput.value - sub);
		amountInput.value = newValue;
	}
	if (event.target.matches('a.btn-plus')) {
		event.preventDefault();
		const amountInput = document.querySelector('#modalCoinsAds form.frmo-modal input[name="amount"]');
		const txt = event.target.textContent;
		const sub = (txt === "+1")? 1 : 5;
		const newValue = parseInt(amountInput.value) + sub;
		amountInput.value = newValue;
	}
});

// ?: ASSIGN COINS/DAYS
async function modalContentFormSubmit(){
	const modalForm = document.querySelector('#modalCoinsAds form#modalForm');
	const type = document.querySelector('#modalCoinsAds form.frmo-modal input[name="type"]');
	const id = document.querySelector('form.update input[name="user_id"]');
	const amount = document.querySelector('#modalCoinsAds form.frmo-modal input[name="amount"]');

	let buttonText = this.textContent;
	this.disabled = true;
	this.innerHTML = `
		${buttonText}
		<span class="position-relative ms-2 input-icon-addon min-width-auto" style="min-width: auto;">
			<div class="spinner-border spinner-border-sm text-white" role="status"></div>
		</span>
	`;

	await axios.post("/assign-"+type.value+"/"+id.value, { amount: amount.value}).then(function (response){
		const data = response.data;
		console.log(response);
		if(data && data.status){
			Toastify({
				className: 'success',
				text: data.message,
				duration: 1000,
				newWindow: false,
				close: true,
				gravity: "top",
				position: "right"
			}).showToast();
			coinsAdsModal.hide();
		}else if(data && !data.status){
			Toastify({
				className: 'error',
				text: data.message,
				duration: 3000,
				newWindow: false,
				close: true,
				gravity: "top",
				position: "right"
			}).showToast();
		}
	}).catch(function (error){
		console.log('error:', error);
		const data = error;
		if(data && !data.error){
			Toastify({
				className: 'error',
				text: data.message,
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

	this.disabled = false;
	this.innerHTML = `
		${buttonText}
	`;
}