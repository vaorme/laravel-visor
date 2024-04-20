import '../../js/app';

import axios from "axios";

import OwnValidator from "./own-validator";
import { removeClass, formatDate } from "./own-helpers";

const urlAxios = window.location.origin;

axios.defaults.baseURL = urlAxios + '/space/orders';

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
            console.log(response);
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
	const urlSite = window.location.origin;
	let statusBadge = "";
	const formatPrice = new Intl.NumberFormat('en-US', {
		style: 'currency',
		currency: 'USD',
	})
	if(isItemEdit){
		statusBadge = `
			${(() => {
				switch(item.status) {
				case "CREATED":
					return `<span class="badge bg-yellow-lt p-2">${item.status}</span>`;
				case "COMPLETED":
					return `<span class="badge bg-green-lt p-2">${item.status}</span>`;
				case "CANCELLED":
					return `<span class="badge bg-red-lt p-2">${item.status}</span>`;
				case "PENDING":
					return `<span class="badge bg-pending-lt p-2">${item.status}</span>`;
				default:
					return `<span class="badge bg-muted-lt p-2">SIN ESTADO</span>`;
				}
			})()}
		`;
		
	}
	
	element.innerHTML = `
		<div class="modal-header">
			<h5 class="modal-title">Detalle orden</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<form action="" id="modalForm" class="frmo-modal${isItemEdit? ' update' : ''}" enctype="multipart/form-data">
				<div class="datagrid">
					<div class="datagrid-item">
						<div class="datagrid-title">Orden #</div>
						<div class="datagrid-content">
							${item && item.order_id? `<span class="badge bg-muted-lt p-2">${item.order_id}</span>` : ''}
						</div>
					</div>
					<div class="datagrid-item">
						<div class="datagrid-title">Transacción #</div>
						<div class="datagrid-content">
							${item && item.transaction_id? `<span class="badge bg-green-lt p-2">${item.transaction_id}</span>` : ''}
						</div>
					</div>
					<div class="datagrid-item">
						<div class="datagrid-title">Nombre</div>
						<div class="datagrid-content">${item && item.name? item.name : ''}</div>
					</div>
					<div class="datagrid-item">
						<div class="datagrid-title">Correo</div>
						<div class="datagrid-content">${item && item.email? item.email : ''}</div>
					</div>
					<div class="datagrid-item">
						<div class="datagrid-title">Estado</div>
						<div class="datagrid-content">
							${statusBadge}
						</div>
					</div>
					<div class="datagrid-item">
						<div class="datagrid-title">Total</div>
						<div class="datagrid-content">
							${formatPrice.format(item && item.total? item.total : 0)}
						</div>
					</div>
				</div>
				<div class="datagrid border-top mt-4 pt-4">
					<div class="datagrid-item">
						<div class="datagrid-title">Fecha</div>
						<div class="datagrid-content">${item && item.created_at? formatDate(item.created_at) : ''}</div>
					</div>
					<div class="datagrid-item">
						<div class="datagrid-title">Usuario</div>
						<div class="datagrid-content">
							<div class="d-flex align-items-center">
								<span class="avatar avatar-xs me-2 rounded" style="background-image: url(${item && item.user_avatar? urlSite+'/storage/'+item.user_avatar : ''})"></span>
								${item && item.username? item.username : ''}
							</div>
						</div>
					</div>
				</div>
                ${(item.product_id)?
                    `
                    <div class="datagrid border-top mt-4 pt-4">
                        <div class="datagrid-item">
                            <div class="datagrid-title">Producto</div>
                            <div class="datagrid-content">${item && item.product_name? item.product_name : ''}</div>
                        </div>
                        ${(item.product_coins)?
                            `
                            <div class="datagrid-item">
                                <div class="datagrid-title">Monedas</div>
                                <div class="datagrid-content">${item && item.product_coins? item.product_coins : ''}</div>
                            </div>
                            `
                            : ''}
                        ${(item.product_days)?
                            `
                            <div class="datagrid-item">
                                <div class="datagrid-title">Días sin Publicidad</div>
                                <div class="datagrid-content">${item && item.product_days? item.product_days : ''}</div>
                            </div>
                            `
                            : ''}
                        <div class="datagrid-item">
                            <div class="datagrid-title">Precio</div>
                            <div class="datagrid-content">${item && item.product_price? formatPrice.format(item && item.product_price? item.product_price : 0) : ''}</div>
                        </div>
                    </div>
                    `
                :
                ''}
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn me-auto" data-bs-dismiss="modal">Cerrar</button>
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

	// // ?: VALIDATE FORM
	// formValidator = new OwnValidator(frmo);
	// formValidator.comicValidateOnChange();
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
					text: data.message,
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
					text: data.message,
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
                text: data.message,
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