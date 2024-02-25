import '../../js/app';

import axios from "axios";
import { Sortable, Plugins } from '@shopify/draggable';

import ownDropZone from "./own-dropzone";
import OwnValidator from "./own-validator";
import { addClass, formattedUsername, isValidEmail, removeClass, sluggify } from "./own-helpers";

axios.defaults.baseURL = axios.defaults.baseURL + '/space/users';

const sortChapters = new Sortable(document.querySelectorAll('.chapters .card-body'), {
    draggable: '.chapters .item',
    handle: '.chapters .item .drag',
    mirror: {
        xAxis: false,
        constrainDimensions: true
    },
    delay: 0,
    plugins: [Plugins.SortAnimation],
    swapAnimation: {
        duration: 200,
        easingFunction: 'ease-in-out',
    },
});

let initialOrder = [];

// Save the initial order when the page loads
let itemsInitial = document.querySelectorAll('.chapters .card-body .item');
itemsInitial?.forEach(item => {
    const chapterId = item.getAttribute('data-id');
    initialOrder.push(chapterId);
});

sortChapters.on('drag:stop', async (event) => {
    let currentOrder = [];
    
    // Get the current order after the drag
    await new Promise((resolve) => {
        setTimeout(() => {
            const chaptersList = document.querySelectorAll('.chapters .card-body .item');
            chaptersList?.forEach(item => {
                const chapterId = item.getAttribute('data-id');
                currentOrder.push(chapterId);
            });
            resolve();
        }, 200);
    });

    // Check if the order has changed
    if (JSON.stringify(initialOrder) !== JSON.stringify(currentOrder)) {
        // The order has changed, proceed with Axios request
        await axios.post("chapters/reorder-chapters", {
            order: currentOrder
        }).then(function (response) {
            const data = response.data;
            if (data && data.status === "success") {
                // Show success message using Toastify
                Toastify({
                    className: 'success',
                    text: data.msg,
                    duration: 3000,
                    newWindow: false,
                    close: true,
                    gravity: "top",
                    position: "center"
                }).showToast();
                itemsInitial = document.querySelectorAll('.chapters .card-body .item');
                initialOrder = [];
                itemsInitial?.forEach(item => {
                    const chapterId = item.getAttribute('data-id');
                    initialOrder.push(chapterId);
                });
            } else {
                console.log(response);
            }
        }).catch(function (error) {
            console.log('error:', error);
            const message = error.response.data.message;
            if (message) {
                // Show error message using Toastify
                Toastify({
                    className: 'error',
                    text: message,
                    duration: 3000,
                    newWindow: false,
                    close: true,
                    gravity: "top",
                    position: "center"
                }).showToast();
            } else {
                console.log(error);
            }
        });
    }
});

// * DROPZONE COVER COMIC
const allowedExtensions = ['jpg', 'png', 'gif', 'webp'];
ownDropZone('.own-dropzone .dz-choose', allowedExtensions);

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
	if(inputConfirmPassword.value === inputPassword.value){
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
	return true;
    if(validator.formValidation()){
        frmo?.submit();
    }
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