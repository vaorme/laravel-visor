import '../../js/app';

import axios from "axios";

import OwnValidator from "./own-validator";
import { removeClass, sluggify } from "./own-helpers";

const urlAxios = window.location.origin;

axios.defaults.baseURL = urlAxios + '/space/comics/categories';

let formValidator;
let currentItemID;

function resetForm(form){
	const elements = form?.elements;
	const elementsArray = elements ? Array.from(elements) : [];
	elementsArray?.forEach(item => {
		removeClass(item, 'is-invalid')
	});
	form.reset();
}

// ? CREATE/EDIT ITEM
let isItemEdit = false;

const itemModal = document.getElementById('itemModal');
const btnEditModal = itemModal? new bootstrap.Modal(itemModal) : '';
itemModal?.addEventListener('hide.bs.modal', () =>{
    const buttonConfirm = document.querySelector('#buttonModalSubmit');
    buttonConfirm?.removeEventListener('click', modalContentFormSubmit);
});
itemModal?.addEventListener('show.bs.modal', async (e) => {
    const id = e.relatedTarget.getAttribute('data-id');
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
			<h5 class="modal-title">${isItemEdit? 'Actualizar' : 'Agregar'} estado</h5>
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
							<input type="text" class="form-control" id="floating-input" name="slug" autocomplete="off" value="${item && item.slug? item.slug : ''}" required>
							<label for="floating-input">Slug</label>
							<div class="invalid-feedback">
								Campo <b>Slug</b> es requerido
							</div>
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

	// ?: GENERATE SLUG
	const inputName = document.querySelector('form#modalForm:not(.update) input[name="name"]');
	const inputSlug = document.querySelector('form#modalForm input[name="slug"]');
	inputName?.addEventListener('input', function(){
		inputSlug.value = sluggify(inputName.value);

		// *: MANUALLY TRIGGER INPUT EVENT ON INPUTSLUG
		const inputEvent = new Event('input', { bubbles: true });
		inputSlug.dispatchEvent(inputEvent);
	});
	inputSlug?.addEventListener('input', function(){
		inputSlug.value = sluggify(inputSlug.value);
	});

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
		const getSlug = formData.get('slug');

        await axios.put("/"+currentItemID, {
            name: getName,
            slug: getSlug,
		}).then(function (response){
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