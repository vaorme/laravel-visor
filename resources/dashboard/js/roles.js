import '../../js/app';

import axios from "axios";

import OwnValidator from "./own-validator";
import { removeClass, sluggify } from "./own-helpers";

const urlAxios = window.location.origin;

axios.defaults.baseURL = urlAxios + '/space/roles';

let formValidator;
let currentItemID;
let permissions;

// ? CREATE/EDIT ITEM
let isItemEdit = false;

const itemModal = document.getElementById('itemModal');
const btnEditModal = itemModal? new bootstrap.Modal(itemModal) : '';
itemModal?.addEventListener('hide.bs.modal', () =>{
	const element = document.querySelector('#itemModal .modal-content');
    const buttonConfirm = document.querySelector('#buttonModalSubmit');
    buttonConfirm?.removeEventListener('click', modalContentFormSubmit);
	setTimeout(() => {
		element.innerHTML = "";
	}, 500);
});
itemModal?.addEventListener('show.bs.modal', async (e) => {
    const id = e.relatedTarget.getAttribute('data-id');

	// * GET PERMISSIONS
	await axios.get("/permissions").then(function (response){
		let res = response.data;
		if(res.status){
			permissions = res.data;
		}else{
			console.log(response);
		}
	}).catch(function (error){
		console.log('error:', error);
		const data = error.response.data;
		if(data && data.status === "error"){
			Toastify({
				className: 'error',
				text: data.msg,
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
    if(id){
        isItemEdit = true;
        const element = document.querySelector('#itemModal .modal-content');
        element.innerHTML = `
            <div class="progress">
                <div class="progress-bar progress-bar-indeterminate bg-green"></div>
            </div>
        `;
        await axios.get("/"+id).then(function (response){
			let res = response.data;
			console.log(response);
			if(res.status){
				let { show } = res;
				modalContentForm(show);
			}else{
				console.log(response);
			}
        }).catch(function (error){
            console.log('error:', error);
            const data = error.response.data;
            if(data && data.status === "error"){
                Toastify({
                    className: 'error',
                    text: data.msg,
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
    }else{
        isItemEdit = false;
        modalContentForm();
    }

	// ? SEND CHAPTER FORM
    const button = document.querySelector('#buttonModalSubmit');
    button?.addEventListener('click', modalContentFormSubmit);
})

function modalContentForm(item){
	const element = document.querySelector('#itemModal .modal-content');
	element.innerHTML = `
		<div class="modal-header">
			<h5 class="modal-title">${isItemEdit? 'Actualizar' : 'Agregar'} rol</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<form action="" id="modalForm" class="frmo-modal${isItemEdit? ' update' : ''}" enctype="multipart/form-data">
				<div class="row row-cards">
					<div class="col-6">
						<div class="form-floating">
							<input type="text" class="form-control" id="floating-input" name="name" autocomplete="off" value="${item && item.name? item.name : ''}" required>
							<label for="floating-input">Nombre</label>  
							<div class="invalid-feedback">
								Campo <b>Nombre</b> es requerido
							</div>
						</div>
					</div>
					<div class="col-6">
						<div class="form-floating">
							<input type="text" class="form-control" id="floating-input" name="tipo" autocomplete="off" value="${item && item.name? item.guard_name : ''}" required>
							<label for="floating-input">Tipo</label>  
							<div class="invalid-feedback">
								Campo <b>Tipo</b> es requerido
							</div>
						</div>
					</div>
					<div class="col-12">
						<small class="form-hint">Sólo se permiten letras sin espacios y sin caracteres especiales.</small>
					</div>
					<div class="col-12">
						<label class="form-label">Permisos</label>
						<div class="accordion" id="accordion-example">
							${(permissions)? Object.keys(permissions).map((key) => {
								return `
									<div class="accordion-item">
										<h2 class="accordion-header" id="heading-${key}">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-${key}" aria-expanded="false">
											${key}
										</button>
										</h2>
										<div id="collapse-${key}" class="accordion-collapse collapse" data-bs-parent="#accordion-example">
											<div class="accordion-body pt-0">
												${(permissions && permissions[key].length > 0)? permissions[key].map((child) => {
													return `
														<div class="form-check">
															<input id="check-${child.id}" class="form-check-input" type="checkbox" value="${child.name}" name="permissions[]" ${item && item.permissions && item.permissions.some(permission => permission.name === child.name)? 'checked' : ''}>
															<label class="form-check-label" for="check-${child.id}">${child.name}</label>
														</div>
													`;
												}).join(''): ''}
											</div>
										</div>
									</div>
								`;
							}).join('') : ''}
						</div>
                    </div>
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn me-auto" data-bs-dismiss="modal">Cerrar</button>
			<button type="button" id="buttonModalSubmit" class="position-relative btn btn-${isItemEdit? 'primary' : 'success'}">${isItemEdit? 'Actualizar' : 'Crear'}</button>
		</div>
    `;

	if(isItemEdit){
		currentItemID = item.id;
	}

	// ?: PREVENT FROM SUBMISSION
	const frmo = document.querySelector('form#modalForm');
	frmo?.addEventListener('submit', function(e){
		e.preventDefault();
	});

	// ?: VALIDATE FORM
	formValidator = new OwnValidator(frmo);
	formValidator.comicValidateOnChange();
}

// ?: CREATE/UPDATE ITEM
async function modalContentFormSubmit(){
    if(!formValidator.validate()){
        return true;
    }
    const modalForm = document.querySelector('form#modalForm');
    const formData = new FormData(modalForm);

	let buttonText = this.textContent;
    this.disabled = true;
    this.innerHTML = `
        ${buttonText}
        <span class="position-relative ms-2 input-icon-addon min-width-auto" style="min-width: auto;">
            <div class="spinner-border spinner-border-sm text-white" role="status"></div>
        </span>
    `;

    if(isItemEdit){
		const getName = formData.get('name');

        await axios.put("/"+currentItemID, {
            name: getName,
		}).then(function (response){
			console.log(response);
            const data = response.data;
			if(data && data.status){
				Toastify({
					className: 'success',
					text: data.msg,
					duration: 1000,
					newWindow: false,
					close: true,
					gravity: "top",
					position: "right"
				}).showToast();
				setTimeout(() =>{
					window.location.reload(true);
				}, 500)
			}else if(data && !data.status){
                Toastify({
                    className: 'error',
                    text: data.msg,
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
                    text: data.msg,
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
    }else{
		currentItemID = "";
        await axios.post("/add", formData, {
            headers:{
                'Content-Type': 'multipart/form-data'
            }
        }).then(function (response){
			console.log(response);
            const data = response.data;
            if(data && data.status){
				Toastify({
					className: 'success',
					text: data.msg,
					duration: 1000,
					newWindow: false,
					close: true,
					gravity: "top",
					position: "right"
				}).showToast();
				setTimeout(() =>{
					window.location.reload(true);
				}, 500)
			}else if(data && !data.status){
                Toastify({
                    className: 'error',
                    text: data.msg,
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
            if(data && data.error === "error"){
                Toastify({
                    className: 'error',
                    text: data.msg,
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
    }
	this.disabled = false;
	this.innerHTML = `
        ${buttonText}
    `;
}

// ? DELETE ITEM
const modalDestroy = document.getElementById('modalDestroy')
let btnModalDestroy = modalDestroy? new bootstrap.Modal(modalDestroy) : '';
modalDestroy?.addEventListener('show.bs.modal', e => {
    const id = e.relatedTarget.getAttribute('data-id');
    const button = modalDestroy.querySelector('#buttonConfirm');
    button.setAttribute('data-id', id);
    button?.addEventListener('click', handlerModuleDestroy);
})
modalDestroy?.addEventListener('hide.bs.modal', () =>{
    const button = modalDestroy.querySelector('#buttonConfirm');
    button?.removeEventListener('click', handlerModuleDestroy);
});

async function handlerModuleDestroy(){
    let buttonText = this.textContent;
    let id = this.getAttribute('data-id');
    this.disabled = true;
    this.innerHTML = `
        ${buttonText}
        <span class="input-icon-addon">
            <div class="spinner-border spinner-border-sm text-white" role="status"></div>
        </span>
    `;
    await moduleDestroy(id);
    
    this.innerHTML = `
        ${buttonText}
    `;
	setTimeout(() =>{
		this.disabled = false;
    	btnModalDestroy.hide();
	}, 500);
}

async function moduleDestroy(id){
    await axios.delete("/"+id).then(function (response){
        //console.log(response);
        const data = response.data;
        if(data && data.status){
            Toastify({
                className: 'success',
                text: data.msg,
                duration: 1000,
                newWindow: false,
                close: true,
                gravity: "top",
                position: "right"
            }).showToast();
			setTimeout(() =>{
				window.location.reload(true);
			}, 500)
        }else{
            console.log(response);
        }
    }).catch(function (error){
        console.log('error:', error);
        const data = error.response.data;
        if(data && !data.status){
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
};