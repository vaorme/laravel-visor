import { dropZone } from './helper/helpers';

// :AXIOS
import axios from 'axios';

// :CHOICES

import Choices from 'choices.js';

// :AIRDATEPICKER

import localeEs from 'air-datepicker/locale/es';
import AirDatepicker from 'air-datepicker';
import 'air-datepicker/air-datepicker.css';

// :TOASTIFY

import Toastify from 'toastify-js'
import "toastify-js/src/toastify.css";

let allowTypes = ['jpg', 'jpeg', 'png','webp','gif'];
dropZone('.fm-manga .dropzone #choose', allowTypes);

const choicesOptions = {
    silent: true,
    allowHTML: true,
    removeItems: true,
    removeItemButton: true,
    searchFloor: 1,
    searchResultLimit: 2,
    searchFields: ['label', 'value'],
    duplicateItemsAllowed: false,
    addItemText: (value) => {
        return `Presiona Enter para añadir <b>"${value}"</b>`;
    },
    position: 'auto',
    loadingText: 'Cargando...',
    noResultsText: 'No se han encontrado resultados',
    noChoicesText: 'No hay opciones para elegir',
    itemSelectText: 'Presione para seleccionar',
    uniqueItemText: 'Solo se pueden agregar valores únicos',
    customAddItemText: 'Solo se pueden agregar valores que coincidan con condiciones específicas',
};

const elementTags = document.querySelector('.input-tags');
const elementCategories = document.querySelector('.select-categories');
if(elementTags && elementCategories){
    new Choices(elementTags, choicesOptions);
    new Choices(elementCategories, choicesOptions);
}

let dateInput = document.querySelector('#field-date');
if(dateInput){
    new AirDatepicker('#field-date',{
        locale: localeEs,
    });
}

// Modal: Create Chapter
let modalCt = document.getElementById('create-chapter');
if(modalCt){
    let button = document.querySelector('main.main .box .frmo.fm-manga .main .section.chapters .buttons a#ct-chapter');
    let closeBtn = document.querySelector('main.main .box .frmo.fm-manga #create-chapter .content .box .bx-title #close-btn');
    button.addEventListener('click', function(e){
        e.preventDefault();
        modalCt.addClass('opn');
    });
    closeBtn.addEventListener('click', function(){
        modalCt.removeClass('opn');
    });

    let inputRdos = document.querySelectorAll('main.main .box .frmo.fm-manga #create-chapter .content .box .bx-body form .group.radios input');
    let tManga = document.querySelector('#t-manga');
    let tNovel = document.querySelector('#t-novel');
    inputRdos.forEach(item =>{
        item.addEventListener('click', function(e){
            if(e.target.value == 0){
                tManga.addClass('hidden');
                tNovel.removeClass('hidden');
            }
            if(e.target.value == 1){
                tNovel.addClass('hidden');
                tManga.removeClass('hidden');
            }
        })
    });

    // ? Create chapter
    let chapterForm = document.querySelector('#create-chapter form');

    let inputCtName = document.querySelector('#ct-name');
    let inputCtSlug = document.querySelector('#ct-slug');
    let inputCtContent = document.querySelector('#create-chapter form textarea');
    let inputCtImages = document.querySelector('#ct-images');
    let radioCtType = document.querySelectorAll('#create-chapter form input[name="chaptertype"]');
    let ctRegex = /[^a-zA-Z0-9]+/g;

    inputCtName.addEventListener('input', function(e){
        let str = e.target.value;
        inputCtSlug.value = str.replace(ctRegex, '-').toLowerCase();
    });
    inputCtSlug.addEventListener('input', function(e){
        let str = e.target.value;
        inputCtSlug.value = str.replace(ctRegex, '-').toLowerCase();
    });

    radioCtType.forEach(item => {
        item.addEventListener('change', function(e){
            let vl = e.target.value;
            if(vl == 1){
                inputCtContent.removeAttribute('required');
                inputCtImages.setAttribute('required', '');
            }
            if(vl == 0){
                inputCtImages.removeAttribute('required');
                inputCtContent.setAttribute('required', '');
            }
        });
    })

    chapterForm.addEventListener('submit', async function(e){
        e.preventDefault();

        let chapterForm = document.querySelector('#create-chapter form');
        let listChapters = document.querySelector('.fm-manga form .chapters .list .simplebar-content');
        let formData = new FormData(chapterForm);

        axios.post(chapterForm.action, formData, {
            headers:{
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(function (response){
            // handle success
            let data = response.data;
            if(data['excluded']){
                let excluded = data['excluded'];
                excluded.forEach(item => {
                    Toastify({
                        text: item,
                        className: "error",
                        duration: 5000,
                        newWindow: true,
                        close: true,
                        gravity: "bottom", // `top` or `bottom`
                        position: "right", // `left`, `center` or `right`
                    }).showToast();
                })
            }
            if(response.data.status == "success"){
                Toastify({
                    text: response.data.msg,
                    className: "success",
                    duration: 5000,
                    newWindow: true,
                    close: true,
                    gravity: "top", // `top` or `bottom`
                    position: "center", // `left`, `center` or `right`
                }).showToast();
                let item = response.data.item;
                let divItem = document.createElement('div');
                divItem.classList.add('item');
                divItem.setAttribute('id', 'm-'+item.id)
                divItem.innerHTML = `
                    <div class="name">
                        ${item.name}
                    </div>
                    <div class="actions">
                        <a href="#" data-id="${item.id}" class="botn view">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-player-play" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M7 4v16l13 -8z" />
                            </svg>
                        </a>
                        <a href="#" data-id="${item.id}" class="botn edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                            </svg>
                        </a>
                        <a href="#" data-id="${item.id}" class="botn delete">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="4" y1="7" x2="20" y2="7" />
                                <line x1="10" y1="11" x2="10" y2="17" />
                                <line x1="14" y1="11" x2="14" y2="17" />
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                            </svg>
                        </a>
                    </div>
                `;
                listChapters.append(divItem);
                setTimeout(function(){
                    chapterForm.reset();
                    modalCt.removeClass('opn');
                }, 1000)
            }else{
                let error = response.data.status;
                if(error == "error"){
                    Toastify({
                        text: response.data.msg,
                        className: "error",
                        duration: 5000,
                        newWindow: true,
                        close: true,
                        gravity: "bottom", // `top` or `bottom`
                        position: "right", // `left`, `center` or `right`
                    }).showToast();
                }
            }
        })
        .catch(function (error){
            // handle error
            console.log(error);
        });
    });
}


// ? Upload chapter
let fileAllow = ['zip'];
dropButton('.fm-manga .upload #upload-chapter', fileAllow);

function dropButton(zone, allowed){
    const drop = document.querySelector(zone);
    let inputElement;
    if(drop){
        inputElement = drop.nextElementSibling;

        inputElement.addEventListener('change', function (e) {
            const file = this.files[0];
            dropButtonFile(file, allowed);
        })
        drop.addEventListener('click', () => inputElement.click());
        drop.addEventListener('dragover', (e) => {
            e.preventDefault();
        });
        drop.addEventListener('drop', (e) => {
            e.preventDefault();
            const file = e.dataTransfer.files[0];
            dropButtonFile(file, allowed);
        });
    }
}
function dropButtonFile(file, allowed){
    if(!file) {
        console.log('error, no hay archivo');
        return 'error';
    }
    let extension = file.name.split('.').pop().toLowerCase();
    if(!allowed.includes(extension)){
        alert('Tipo de archivo no permitido');
        return 'no allowed';
    }

    let inputUpload = document.querySelector('#inpt-chapter');
    let mangaId = inputUpload.getAttribute('data-id');
    let dataForm = new FormData();

    let listChapters = document.querySelector('.fm-manga form .chapters .list .simplebar-content');

    dataForm.append("chapters", inputUpload.files[0]);

    axios.post(route('uploadChapter.store', [mangaId]), dataForm, {
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).then(function (response){
        // handle success
        console.log(response);
        let data = response.data;
        if(data['excluded']){
            let excluded = data['excluded'];
            excluded.forEach(item => {
                Toastify({
                    text: item,
                    className: "error",
                    duration: 5000,
                    newWindow: true,
                    close: true,
                    gravity: "bottom", // `top` or `bottom`
                    position: "right", // `left`, `center` or `right`
                }).showToast();
            })
        }
        let created = data.created;
        if(created){
            created.forEach(item => {
                Toastify({
                    text: item.msg,
                    className: "success",
                    duration: 5000,
                    newWindow: true,
                    close: true,
                    gravity: "top", // `top` or `bottom`
                    position: "center", // `left`, `center` or `right`
                }).showToast();
            });
            created.forEach(element => {
                let item = element.item;
                console.log(item);
                let divItem = document.createElement('div');
                divItem.classList.add('item');
                divItem.innerHTML = `
                    <div class="name">
                        ${item.name}
                    </div>
                    <div class="actions">
                        <a href="#" data-id="${item.id}" class="botn view">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-player-play" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M7 4v16l13 -8z" />
                            </svg>
                        </a>
                        <a href="#" data-id="${item.id}" class="botn edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                            </svg>
                        </a>
                        <a href="#" data-id="${item.id}" class="botn delete">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="4" y1="7" x2="20" y2="7" />
                                <line x1="10" y1="11" x2="10" y2="17" />
                                <line x1="14" y1="11" x2="14" y2="17" />
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                            </svg>
                        </a>
                    </div>
                `;
                listChapters.append(divItem);
            });
        }else{
            let errors = response.data['error'];
            if(errors){
                errors.forEach(item => {
                    Toastify({
                        text: item,
                        className: "error",
                        duration: 5000,
                        newWindow: true,
                        close: true,
                        gravity: "bottom", // `top` or `bottom`
                        position: "right", // `left`, `center` or `right`
                    }).showToast();
                })
            }
        }
        document.querySelector('#inpt-chapter').value = "";
    })
    .catch(function (error){
        // handle error
        console.log(error);
    });

}

class Modalerts{
    fire(opt){
        let tis = '';
        let app = document.querySelector('#app');
        let createAlert = document.createElement('div');
        let createOverlay = document.createElement('div');
        createOverlay.addClass('al-overlay');
        createAlert.addClass('alertify');
        createAlert.innerHTML = `
            <div class="md-icon">
                <svg aria-labelledby="errorIconTitle" color="#2329D6" fill="none" height="48px" role="img" stroke="#2329D6" stroke-linecap="square" stroke-linejoin="miter" stroke-width="1" viewBox="0 0 24 24" width="48px" xmlns="http://www.w3.org/2000/svg"><title id="errorIconTitle"/><path d="M12 8L12 13"/><line x1="12" x2="12" y1="16" y2="16"/><circle cx="12" cy="12" r="10"/></svg>
            </div>
            <div class="md-content">
                <h2>${opt.title}</h2>
                <p>${opt.text}</p>
            </div>
            <div class="md-buttons">
                ${(opt.confirmButtonText)? '<button class="confirm">'+opt.confirmButtonText+'</button>': '' }
                <button class="cancel">${(opt.cancelButtonText)? opt.cancelButtonText : 'Close' }</button>
            </div>
        `;
        app.append(createOverlay);
        app.append(createAlert);

        let alertify = document.querySelector('.alertify');
        let over = document.querySelector('.al-overlay');

        let buttonConfirm = document.querySelector('.alertify button.confirm');
        let buttonCancel = document.querySelector('.alertify button.cancel');
        tis = this;

        return new Promise((resolve, reject) => {
            if(buttonConfirm){
                buttonConfirm.addEventListener('click', function(){
                    resolve({confirmed: true});
                });
            }
            if(buttonCancel){
                buttonCancel.addEventListener('click', function(){
                    tis.close(alertify, over);
                });
            }
        })
        
    }
    close(alert, over){
        let element = alert;
        let overlay = over;
        element.addClass('closing');
        overlay.addClass('closing');
        setTimeout(function(){
            element.remove();
            overlay.remove();
        }, 300);

        clearTimeout();
    }
}

let nn = new Modalerts();

// :DELETE CHAPTER

let deleteButtons = document.querySelectorAll('main.main .box .frmo.fm-manga .main .section.chapters .list .item .actions .botn.delete');

// Listen to all click events on the document
document.addEventListener('click', function (e) {

	// If the clicked element does not have the .click-me class, ignore it
	if (!e.target.matches('.botn.delete')) return;

    e.preventDefault();

	let id = e.target.getAttribute('data-id');
    nn.fire({
        type: "error",
        title: "Eliminar capítulo?",
        text: "¡No podrás revertir esto!",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cancelar"
    }).then(res => {
        if(res.confirmed){
            axios.delete(route('chapters.destroy', [id]), {
                headers:{
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(function (response){
                Toastify({
                    text: response.data.msg,
                    className: "success",
                    duration: 5000,
                    newWindow: true,
                    close: true,
                    gravity: "top", // `top` or `bottom`
                    position: "center", // `left`, `center` or `right`
                }).showToast();
                let alertify = document.querySelector('.alertify');
                let over = document.querySelector('.al-overlay');
                nn.close(alertify, over);

                document.querySelector('#m-'+response.data.id).remove();
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

// deleteButtons.forEach(bt => {
    
//     bt.addEventListener('click', function(e){
//         e.stopPropagation();
//         e.preventDefault();
//         let id = e.target.getAttribute('data-id');
//         nn.fire({
//             type: "error",
//             title: "Eliminar capítulo?",
//             text: "¡No podrás revertir esto!",
//             confirmButtonText: "Si, eliminar",
//             cancelButtonText: "Cancelar"
//         }).then(res => {
//             if(res.confirmed){
//                 axios.delete(route('chapters.destroy', [id]), {
//                     headers:{
//                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//                     }
//                 }).then(function (response){
//                     console.log(response);    
//                 })
//                 .catch(function (error){
//                     // handle error
//                     console.log(error);
//                 });
//             }
//         }).catch(err => {
//             console.log(err);
//         })
//     });
// });