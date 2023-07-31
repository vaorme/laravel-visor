import axios from 'axios';
import { dropZone, validateEmail, isUrl, Modalerts } from '../admin/helpers/helpers';

let nn = new Modalerts();

const errorAlert = (msg) =>{
	Toastify({
		text: msg,
		className: "error",
		duration: 5000,
		newWindow: true,
		close: true,
		gravity: "top",
		position: "center",
	}).showToast();
}

// :USER FORM


// :AVATARES
const listInputAvatares = document.querySelectorAll('.form__avatares .list .item:not(#userAvatar) input');
if (listInputAvatares) {
	listInputAvatares.forEach(item => {
		item.addEventListener('change', (e) => {
			const avatarSelected = document.querySelector('.form__avatares .list .item#avatar');
			if (avatarSelected) {
				avatarSelected.removeClass('selected');
			}

		});
	});
}

document.addEventListener('click', function (e) {
	if (!e.target.matches('.form__avatares .list .item#avatar')) return;
	e.preventDefault();

	const currentInput = document.querySelector('.form__avatares .list .item input:checked');
	if (currentInput) {
		currentInput.checked = false;
	}

	e.target.addClass('selected');


});

// :USER DROPZONE AVATAR

let allowTypes = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
const validateAvatar = async (file) =>{
	let formData = new FormData();
	let sts;
	formData.append("avatar", file);
	await axios.post('/users/validate-avatar', formData,{
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).then(function (response){
        const { data, data: {status, msg} } = response;
		if(status === 'error'){
			for(let error of msg){
				errorAlert(error);
			}
			sts = false;
			return false;
		}
		sts = true;
    })
    .catch(function (error){
        console.log("error: ",error);
    });
	
	return sts;
}

const userAvatarDropZone = function (zone) {
	const drop = document.querySelector(zone);
	let inputElement;
	if (drop) {
		inputElement = drop.nextElementSibling;
		inputElement.addEventListener('change', function (e) {
			const checkFile = e.target.files;
			const userFile = { ...checkFile }
			validateAvatar(checkFile[0]).then(res => {
				if(res){
					previewUserAvatar(userFile);
				}
			})

			inputElement.value = "";
		})
		drop.addEventListener('click', () => inputElement.click());
		drop.addEventListener('dragover', (e) => {
			e.preventDefault();
		});
		drop.addEventListener('drop', (e) => {
			e.preventDefault();
			const checkFile = e.dataTransfer.files;
			const userFile = { ...checkFile }
			validateAvatar(checkFile[0]).then(res => {
				if(res){
					previewUserAvatar(userFile);
				}
			})

			inputElement.value = "";
		});
	}
}
userAvatarDropZone('#userAvatar #choose', allowTypes);

// :USER PREVIEW AVATAR

function previewUserAvatar(image) {
	limpiarPreviewUserAvatar();
	
	const previewBox = document.querySelector('.form__avatares .list');
	const imageUrl = URL.createObjectURL(image[0]);
	const imageDiv = document.createElement('div');
	const currentInput = document.querySelector('.form__avatares .list .item input:checked');
	if (currentInput) {
		currentInput.checked = false;
	}
	imageDiv.addClass('item selected');
	imageDiv.setAttribute('id', 'avatar');
	imageDiv.innerHTML = `
        <div class="avatar">
            <img src="${imageUrl}" alt="avatar">
			<input type="file" name="avatar_file" accept="image/jpg,image/png,image/jpeg,image/gif" hidden>
        </div>
    `;

	previewBox.append(imageDiv);


	const createFiles = new DataTransfer();
	const currentAvatar = document.querySelector('.form__avatares #avatar input');
	
	createFiles.items.add(image[0]);
	currentAvatar.files = createFiles.files;
}

function limpiarPreviewUserAvatar() {
	const deleteUserAvatarPreview = document.querySelector('.form__avatares #avatar');
	if (deleteUserAvatarPreview) {
		deleteUserAvatarPreview.remove();
	}
}

const $coverPreview = document.querySelector('.account__form .form__cover input');

if($coverPreview){
	$coverPreview.addEventListener('input', userCoverPreview);
}

async function userCoverPreview(e){
	const $imagePreview = document.querySelector('.account__form .form__cover .preview img');
	const urlPreview = e.target.value;
	
	$imagePreview.removeClass('show');

	if(!isUrl(urlPreview)){
		return false;
	}
	$imagePreview.src = urlPreview;
	$imagePreview.onload = (e) =>{
		
	}
	$imagePreview.onerror = (e) =>{
		errorAlert('Imagen invalida o enlace roto');
	}
	const getDimensions = (url) => new Promise((resolve, reject) => {
		const img = new Image();
		img.onload = () => resolve(img);
		img.onerror = (err) => reject(err);
		img.src = url;
	});

	// Usage example:
	const img = await getDimensions(urlPreview);
	let sta = true;
	if(img.naturalWidth > 1920){
		errorAlert('El ancho maximo es 1920');
		$coverPreview.setAttribute('data-validated', false);
		sta = false;
	}
	if(img.naturalHeight > 548){
		errorAlert('El alto maximo es 548');
		$coverPreview.setAttribute('data-validated', false);
		sta = false;
	}

	if(!sta){
		return false;
	}

	$imagePreview.addClass('show');
	$coverPreview.setAttribute('data-validated', true);
}

// :USER SOCIALS

const $redes = {
	list: document.querySelector('.account__form .form__redes .list'),
	listInputs: document.querySelectorAll('.account__form .form__redes .list .item:not(.add) input'),
	addInput: document.querySelector('.account__form .form__redes .list .add input')
};

if($redes.listInputs){
	$redes.listInputs.forEach(item =>{
		item.addEventListener('input', () =>{
			if(item.value === ""){
				errorAlert("Campo requerido");
				item.focus();
				item.addClass('error');
			}else{
				item.removeClass('error');
			}
		});
	});
}

document.addEventListener('click', function (e) {
	if (!e.target.matches('.account__form .form__redes .list .add .new')) return;
    e.preventDefault();

	let val = $redes.addInput.value;
	let id = Date.now();
	if(val === ""){
		errorAlert("Campo requerido");
		$redes.addInput.focus();
		$redes.addInput.addClass('error');
		return false;
	}else{
		$redes.addInput.removeClass('error');
	}
	if(!isUrl(val)){
		errorAlert("Debes insertar un enlace valido");
		$redes.addInput.focus();
		$redes.addInput.addClass('error');
		return false;
	}else{
		$redes.addInput.removeClass('error');
	}
	
	let createDiv = document.createElement('div');
	createDiv.addClass('item');
	createDiv.setAttribute('id', `i-${id}`);

	createDiv.innerHTML = `
		<div class="icon">
			<svg width="28px" height="28px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<circle cx="12" cy="12" r="8" stroke="#fff" stroke-width="2"/>
				<path d="M18.572 7.20637C17.8483 8.05353 16.8869 8.74862 15.7672 9.23422C14.6475 9.71983 13.4017 9.98201 12.1326 9.99911C10.8636 10.0162 9.60778 9.78773 8.4689 9.33256C7.33002 8.87739 6.34077 8.20858 5.58288 7.38139" stroke="#fff" stroke-width="2"/>
				<path d="M18.572 16.7936C17.8483 15.9465 16.8869 15.2514 15.7672 14.7658C14.6475 14.2802 13.4017 14.018 12.1326 14.0009C10.8636 13.9838 9.60778 14.2123 8.4689 14.6674C7.33002 15.1226 6.34077 15.7914 5.58288 16.6186" stroke="#fff" stroke-width="2"/>
				<path d="M12 4V20" stroke="#fff" stroke-width="2"/>
			</svg>
		</div>
		<input type="text" name="redes[]" value="${val}">
		<div class="action">
			<button class="delete" data-id="i-${id}">
				<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
					<path stroke="none" d="M0 0h24v24H0z" fill="none"/>
					<line x1="4" y1="7" x2="20" y2="7" />
					<line x1="10" y1="11" x2="10" y2="17" />
					<line x1="14" y1="11" x2="14" y2="17" />
					<path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
					<path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
				</svg>
			</button>
		</div>
	`;
	
	$redes.list.append(createDiv);
	$redes.addInput.value = '';

});

document.addEventListener('click', function (e) {

	// If the clicked element does not have the .click-me class, ignore it
	if (!e.target.matches('.account__form .form__redes .list .item .delete')) return;
    e.preventDefault();

	let id = e.target.getAttribute('data-id');

	document.querySelector(`#${id}`).remove()

});

const formUser = document.querySelector('.account__change__password form');
if (formUser) {
	formUser.addEventListener('submit', async function (e) {
		
		// validateFields(e);
		let validated = validateFields();
		if(!validated){
			e.preventDefault();
			return false;
		}
	});
}

function validateFields(){
	let cuInputCover = document.querySelector('.account__form .form__cover input');
	if(cuInputCover.value != "" && cuInputCover.dataset.validated === "false"){
		cuInputCover.value = '';
	}

	return true;
}

// :FORM UPDATE PASSWORD

const formPasswordUpdate = document.querySelector('.account__change__password form');
if (formPasswordUpdate) {
	formPasswordUpdate.addEventListener('submit', async function (e) {
		let validated = validatePasswordFields();
		if(!validated){
			e.preventDefault();
			return false;
		}
	});
}

function validatePasswordFields(){
	let cuInputCurrentPassword = document.querySelector('.account__change__password form .form__item input[name="current_password"]');
	let cuInputPassword = document.querySelector('.account__change__password form .form__item input[name="password"]');
	let cuInputPasswordConfirm = document.querySelector('.account__change__password form .form__item input[name="password_confirmation"]');
	if(cuInputCurrentPassword && cuInputCurrentPassword.value == ""){
		errorAlert("Contraseña actual es requerida");
		cuInputCurrentPassword.focus();
		return false;
	}
	if (cuInputPassword && cuInputPassword.value == "") {
		errorAlert("Contraseña nueva es requerida");
		cuInputPassword.focus();
		return false;
	}
	if (!passwordValidated && !passwordValidated) {
		errorAlert("Contraseña invalida");
		return false;
	}
	if (cuInputPasswordConfirm && cuInputPasswordConfirm.value == "") {
		errorAlert("Confirmar nueva contraseña es requerido");
		cuInputPasswordConfirm.focus();
		return false;
	}
	if (cuInputPassword.value != cuInputPasswordConfirm.value) {
		errorAlert("Las contraseñas deben coincidir");
		cuInputPasswordConfirm.focus();
		return false;
	}

	return true;
}

let inputPassword = document.querySelector('.account__change__password form .form__item input[name="password"]');
const passwordFields = {
	'letter': document.querySelector(".password__validation #letter"),
	'capital': document.querySelector(".password__validation #capital"),
	'number': document.querySelector(".password__validation #number"),
	'length': document.querySelector(".password__validation #length")
};
let passwordValidated = false;

if (inputPassword) {
	inputPassword.addEventListener('keyup', function () {
		let lowerCaseLetters = /[a-z]/g;
		if (inputPassword.value.match(lowerCaseLetters)) {
			passwordFields.letter.removeClass("invalid");
			passwordFields.letter.addClass("valid");
			passwordValidated = true;
		} else {
			passwordFields.letter.removeClass("valid");
			passwordFields.letter.addClass("invalid");
			passwordValidated = false;
		}

		// Validate capital letters
		let upperCaseLetters = /[A-Z]/g;
		if (inputPassword.value.match(upperCaseLetters)) {
			passwordFields.capital.removeClass("invalid");
			passwordFields.capital.addClass("valid");
			passwordValidated = true;
		} else {
			passwordFields.capital.removeClass("valid");
			passwordFields.capital.addClass("invalid");
			passwordValidated = false;
		}

		// Validate numbers
		let numbers = /[0-9]/g;
		if (inputPassword.value.match(numbers)) {
			passwordFields.number.removeClass("invalid");
			passwordFields.number.addClass("valid");
			passwordValidated = true;
		} else {
			passwordFields.number.removeClass("valid");
			passwordFields.number.addClass("invalid");
			passwordValidated = false;
		}

		// Validate length
		if (inputPassword.value.length >= 8) {
			passwordFields.length.removeClass("invalid");
			passwordFields.length.addClass("valid");
			passwordValidated = true;
		} else {
			passwordFields.length.removeClass("valid");
			passwordFields.length.addClass("invalid");
			passwordValidated = false;
		}
	});
}