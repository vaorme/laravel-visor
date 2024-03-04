import '../../js/app';

import axios from "axios";
import { Sortable, Plugins } from '@shopify/draggable';

import ownDropZone from "./own-dropzone";
import OwnValidator from "./own-validator";
import { sluggify } from "./own-helpers";

const urlAxios = window.location.origin;

axios.defaults.baseURL = urlAxios + '/space/comics';

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

// * GENERATE SLUG
const inputTitle = document.querySelector('form.frmo:not(.update) input[name="title"]');
const inputSlug = document.querySelector('form.frmo input[name="slug"]');
inputTitle?.addEventListener('input', function(){
    inputSlug.value = sluggify(inputTitle.value);

	// *: MANUALLY TRIGGER INPUT EVENT ON INPUT SLUG
	const inputEvent = new Event('input', { bubbles: true });
	inputSlug.dispatchEvent(inputEvent);
});
inputSlug?.addEventListener('input', function(){
    inputSlug.value = sluggify(inputSlug.value);
});


// ? CREATE COMIC

const frmo = document.querySelector('form.frmo');
frmo?.addEventListener('submit', function(e){
    e.preventDefault();
});
let validator;
if(frmo){
    validator = new OwnValidator(frmo);
    validator.comicValidateOnChange();
}

/* SUBMIT BUTTON FORM */
const btnSubmit = document.querySelector('button.btn-submit');
btnSubmit?.addEventListener('click', function(){
    const formData = new FormData(frmo);
    if(validator.comicValidate()){
        frmo?.submit();
    }
});

// ? ELIMINAR COMIC
const modalDestroy = document.getElementById('modal-destroy')
let btpModalDestroy = modalDestroy? new bootstrap.Modal(modalDestroy) : '';
modalDestroy?.addEventListener('show.bs.modal', e => {
    const id = e.relatedTarget.getAttribute('data-id');
    const button = document.querySelector('#buttonConfirm');
    button.setAttribute('data-id', id);
    button?.addEventListener('click', comicDestroy);
})

async function comicDestroy(){
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