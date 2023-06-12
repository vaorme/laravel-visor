import axios from 'axios';
import { dropZone, validateEmail, isUrl, Modalerts } from './helper/helpers';

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

const formUser = document.querySelector('.form:not(.update) form#formUser');

// Create User Form

let inputPassword = document.querySelector('#formUser .group input[name="password"]');
const passwordFields = {
	'letter': document.querySelector(".password-validation #letter"),
	'capital': document.querySelector(".password-validation #capital"),
	'number': document.querySelector(".password-validation #number"),
	'length': document.querySelector(".password-validation #length")
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

// Username check
const inputRegex = /[^a-zA-Z0-9]+/g;
let userNameValidate = document.querySelector('#formUser .group input[name="username"]');
if (userNameValidate) {
	userNameValidate.addEventListener('input', function (e) {
		let str = e.target.value;
		userNameValidate.value = str.replace(inputRegex, '');
	});
}

// Input's Avatares

const listInputAvatares = document.querySelectorAll('.avatares .list .item:not(#userAvatar) input');
if (listInputAvatares) {
	listInputAvatares.forEach(item => {
		item.addEventListener('change', (e) => {
			const avatarSelected = document.querySelector('.avatares .list .item#avatar');
			if (avatarSelected) {
				avatarSelected.removeClass('selected');
			}

		});
	});
}

document.addEventListener('click', function (e) {
	if (!e.target.matches('.avatares .list .item#avatar')) return;
	e.preventDefault();

	const currentInput = document.querySelector('.avatares .list .item input:checked');
	if (currentInput) {
		currentInput.checked = false;
	}

	e.target.addClass('selected');


});

// User Avatar DropZone

let allowTypes = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

const validateAvatar = async (file) =>{
	let formData = new FormData();
	let sts;
	formData.append("avatar", file);
	await axios.post(route('validateAvatar.store'), formData,{
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

// Generate preview user avatar

function previewUserAvatar(image) {
	limpiarPreviewUserAvatar();
	
	const previewBox = document.querySelector('.avatares .list');
	const imageUrl = URL.createObjectURL(image[0]);
	const imageDiv = document.createElement('div');
	const currentInput = document.querySelector('.avatares .list .item input:checked');
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
	const currentAvatar = document.querySelector('.avatares #avatar input');
	
	createFiles.items.add(image[0]);
	currentAvatar.files = createFiles.files;
}

function limpiarPreviewUserAvatar() {
	const deleteUserAvatarPreview = document.querySelector('.avatares #avatar');
	if (deleteUserAvatarPreview) {
		deleteUserAvatarPreview.remove();
	}
}

const $coverPreview = document.querySelector('#formUser .cover input');

if($coverPreview){
	$coverPreview.addEventListener('input', userCoverPreview);
}

function userCoverPreview(e){
	const $imagePreview = document.querySelector('#formUser .cover .preview img');
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
	(async() => {
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
	})();
}

// Usuario Redes

const $redes = {
	list: document.querySelector('#formUser .redes .list'),
	listInputs: document.querySelectorAll('#formUser .redes .list .item:not(.add) input'),
	addInput: document.querySelector('#formUser .redes .list .add input')
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

	// If the clicked element does not have the .click-me class, ignore it
	if (!e.target.matches('#formUser .redes .list .add .new')) return;
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
	if (!e.target.matches('#formUser .redes .list .item .delete')) return;
    e.preventDefault();

	let id = e.target.getAttribute('data-id');

	document.querySelector(`#${id}`).remove()

});

if (formUser) {
	formUser.addEventListener('submit', async function (e) {
		e.preventDefault();

		// validateFields(e);
		let validated = validateFields(e);
		if(!validated){
			return false;
		}
		
		const formData = new FormData(this);
		await axios.post(formUser.action, formData,{
			headers:{
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
				'Content-Type': 'multipart/form-data'
			}
		}).then(function (response){
			// handle success
			const { data: { success: success } } = response;
			if(success.status === 200){
				Toastify({
					text: "Usuario creado correctamente",
					className: "success",
					duration: 5000,
					newWindow: true,
					close: true,
					gravity: "top",
					position: "center",
				}).showToast();
				setTimeout(() => {
					window.location.href = route('users.index');
				}, 1000);
			}
			console.log(response);
		})
		.catch(function (error){
			console.log(error);
		});
	});
}
const formUserUpdate = document.querySelector('.frmo.update form#formUser');
if (formUserUpdate) {
	formUserUpdate.addEventListener('submit', async function (e) {
		
		// validateFields(e);
		let validated = validateUpdateFields(e);
		if(!validated){
			e.preventDefault();
			return false;
		}

		// const formData = new FormData();

		// let name = formData.get('name');
		// let username = formData.get('username');
		// let email = formData.get('email');
		// let password = formData.get('password');
		// let password_confirmation = formData.get('password_confirmation');
		// let country = formData.get('country');
		// let cover = formData.get('cover');
		// let message = formData.get('message');
		// let public_profile = formData.get('public_profile');
		// let roles = formData.get('roles');
		// let redes = formData.getAll('redes[]');
		// let default_avatar = formData.get('default_avatar');
		// let avatar_file = formData.get('avatar_file');
		// formData.append('custom_files', e.target[19].files[0]);

		// await axios.patch(formUserUpdate.action, formData, {
		// 	headers: {
		// 		'Content-Type': 'multipart/form-data'
		// 	}
		// }).then(function (response){
		// 	//handle success
		// 	// if(response.status === 200 && response.data.length > 0){
		// 	// 	const { data: { success: success } } = response;
		// 	// 	if(success.status === 200){
		// 	// 		Toastify({
		// 	// 			text: "Usuario actualizado correctamente",
		// 	// 			className: "success",
		// 	// 			duration: 5000,
		// 	// 			newWindow: true,
		// 	// 			close: true,
		// 	// 			gravity: "top",
		// 	// 			position: "center",
		// 	// 		}).showToast();
		// 	// 	}
		// 	// }
		// 	console.log(response);
		// })
		// .catch(function (error){
		// 	console.log(error);
		// });
	});
}
function validateFields(e){
	let cuInputName = document.querySelector('#formUser .group input[name="username"]');
	
	if (cuInputName && cuInputName.value == "") {
		errorAlert("Nombre de usuario es requerido");
		cuInputName.focus();
		return false;
	}

	let cuInputMail = document.querySelector('#formUser .group input[name="email"]');
	if (cuInputMail && cuInputMail.value == "") {
		errorAlert("Correo es requerido");
		cuInputMail.focus();
		return false;
	}
	if (!validateEmail(cuInputMail.value)) {
		errorAlert("Email invalido");
		return false;
	}

	let cuInputPassword = document.querySelector('#formUser .group input[name="password"]');
	let cuInputPasswordConfirm = document.querySelector('#formUser .group input[name="password_confirmation"]');
	if (cuInputPassword && cuInputPassword.value == "") {
		errorAlert("Contraseña es requerida");
		cuInputPassword.focus();
		return false;
	}
	if (!passwordValidated) {
		errorAlert("Contraseña invalida");
		return false;
	}
	if (cuInputPasswordConfirm && cuInputPasswordConfirm.value == "") {
		errorAlert("Confirmar Contraseña es requerido");
		cuInputPasswordConfirm.focus();
		return false;
	}
	if (cuInputPassword.value != cuInputPasswordConfirm.value) {
		errorAlert("Las contraseñas deben coincidir");
		cuInputPasswordConfirm.focus();
		return false;
	}

	let cuInputCover = document.querySelector('#formUser .cover input');
	if(cuInputCover.value != "" && cuInputCover.dataset.validated === "false"){
		cuInputCover.value = '';
	}

	return true;
}

function validateUpdateFields(e){
	let cuInputName = document.querySelector('#formUser .group input[name="username"]');
	
	if (cuInputName && cuInputName.value == "") {
		errorAlert("Nombre de usuario es requerido");
		cuInputName.focus();
		return false;
	}

	let cuInputMail = document.querySelector('#formUser .group input[name="email"]');
	if (cuInputMail && cuInputMail.value == "") {
		errorAlert("Correo es requerido");
		cuInputMail.focus();
		return false;
	}
	if (!validateEmail(cuInputMail.value)) {
		errorAlert("Email invalido");
		return false;
	}

	// let cuInputPassword = document.querySelector('#formUser .group input[name="password"]');
	// let cuInputPasswordConfirm = document.querySelector('#formUser .group input[name="password_confirmation"]');
	// if (cuInputPassword && cuInputPassword.value == "") {
	// 	errorAlert("Contraseña es requerida");
	// 	cuInputPassword.focus();
	// 	return false;
	// }
	// if (!passwordValidated) {
	// 	errorAlert("Contraseña invalida");
	// 	return false;
	// }
	// if (cuInputPasswordConfirm && cuInputPasswordConfirm.value == "") {
	// 	errorAlert("Confirmar Contraseña es requerido");
	// 	cuInputPasswordConfirm.focus();
	// 	return false;
	// }
	// if (cuInputPassword.value != cuInputPasswordConfirm.value) {
	// 	errorAlert("Las contraseñas deben coincidir");
	// 	cuInputPasswordConfirm.focus();
	// 	return false;
	// }

	let cuInputCover = document.querySelector('#formUser .cover input');
	if(cuInputCover.value != "" && cuInputCover.dataset.validated === "false"){
		cuInputCover.value = '';
	}

	return true;
}

// :DELETE USER

document.addEventListener('click', function (e) {
	if (!e.target.matches('.userDelete')) return;

    e.preventDefault();

	let id = e.target.getAttribute('data-id');

    nn.fire({
        type: "warning",
        title: "Seguro que deseas eliminar?",
        text: "¡No podrás revertir esto!",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cancelar"
    }).then(res => {
        if(res.confirmed){
            axios.delete(route('users.destroy', [id]), {
                headers:{
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(function (response){
                Toastify({
                    text: response.data.msg,
                    className: "success",
                    duration: 1000,
                    newWindow: true,
                    close: true,
                    gravity: "top", // `top` or `bottom`
                    position: "center", // `left`, `center` or `right`
                }).showToast();
                let alertify = document.querySelector('.alertify');
                let over = document.querySelector('.al-overlay');
                nn.close(alertify, over);

                setTimeout(() => {
                    window.location.href = route('users.index');
                }, 1000);
            })
            .catch(function (error){
                // handle error
                console.log(error);
            });
        }
    }).catch(err => {
        console.log(err);
    })

});