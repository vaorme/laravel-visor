import { dropZone, removeBodyScroll, clearBodyScroll, Modalerts } from './helpers/helpers';
import { sluggify, removeClass, addClass, slideToggle } from '../helpers/helpers';
let allowTypes = ['jpg', 'jpeg', 'png','webp','gif'];
dropZone('.fm-manga .dropzone #choose', allowTypes);

const choicesOptions = {
    silent: true,
    allowHTML: true,
    removeItems: true,
    removeItemButton: true,
    searchEnabled: false,
    searchFloor: 1,
    position: 'auto',
    resetScrollPosition: true,
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

const elementTags = document.querySelector('#m-tags');
const elementCategories = document.querySelector('#m-categories');
const elementMangaType = document.querySelector('#m-mangatype');
const elementBookStatus = document.querySelector('#m-bookstatus');
const elementDemography = document.querySelector('#m-demography');
if(elementTags && elementCategories && elementMangaType && elementBookStatus && elementDemography){
    new Choices(elementTags, choicesOptions);
    new Choices(elementCategories, choicesOptions);
    new Choices(elementMangaType, choicesOptions);
    new Choices(elementBookStatus, choicesOptions);
    new Choices(elementDemography, choicesOptions);
}

let dateInput = document.querySelector('#field-date');
if(dateInput){
    new AirDatepicker('#field-date',{
        locale: localeEs,
        dateFormat: "yyyy-MM-dd"
    });
}

// Create manga

let fmCreate = document.querySelector('.fm-create');
if(fmCreate){
    let inputFmName = document.querySelector('#m-name');
    let inputFmSlug = document.querySelector('#m-slug');

    inputFmName.addEventListener('input', function(e){
        inputFmSlug.value = sluggify(e.target.value);
    });
    inputFmSlug.addEventListener('input', function(e){
        inputFmSlug.value = sluggify(e.target.value);
    });
}

// Update manga

let fmUpdate = document.querySelector('.fm-update');
if(fmUpdate){
    let inputFmSlug = document.querySelector('#m-slug');
    inputFmSlug.addEventListener('input', function(e){
        inputFmSlug.value = sluggify(e.target.value);
    });
}



// Modal: Create Chapter
let createChapter = document.getElementById('modalChapter');
if(createChapter){
    let inputCtName = document.querySelector('#ct-name');
    let inputCtSlug = document.querySelector('#ct-slug');
    let inputCtImages = document.querySelector('#ct-images');
    let radioCtType = document.querySelectorAll('#modalChapter form input[name="chaptertype"]');
    let button = document.querySelector('main.main .box .frmo.fm-manga .main .section.chapters .buttons a#ct-chapter');

    button.addEventListener('click', function(e){
        e.preventDefault();
        tinymce.init({
            selector: '#createChapterContent',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize backcolor | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            menubar: false,
            height: 600,
        });
        addClass(createChapter, 'opn');
        removeBodyScroll();
    });
    document.addEventListener('click', function(e){
        if (!e.target.matches('#modalChapter .md-close')) return;
        e.preventDefault();
    
        tinymce.remove();
    
        addClass(createChapter, 'clg');
        removeClass(createChapter, 'opn');
        clearBodyScroll();
    })
    let previewBox = document.querySelector('#t-preview');
    let inputRdos = document.querySelectorAll('main.main .box #modalChapter .md-content form .group .radios label input');
    let tManga = document.querySelector('#t-manga');
    let tNovel = document.querySelector('#t-novel');
    
    inputRdos.forEach(item =>{
        item.addEventListener('click', function(e){
            if(e.target.value == "novel"){
                addClass(tManga, 'hidden');
                removeClass(tNovel, 'hidden');
            }
            if(e.target.value == "manga"){
                addClass(tNovel,'hidden');
                removeClass(tManga, 'hidden');
            }
        })
    });

    // ? Create chapter
    let chapterForm = document.querySelector('#modalChapter form');

    inputCtName.addEventListener('input', function(e){
        inputCtSlug.value = sluggify(e.target.value);
    });
    inputCtSlug.addEventListener('input', function(e){
        inputCtSlug.value = sluggify(e.target.value);
    });

    let previewFiles = [];

    // Dropzone chapter images
    let chapterFileAllow = ['jpeg', 'jpg', 'png', 'gif'];
    const chapterDropZone = function(zone, allowed){
        const drop = document.querySelector(zone);
        let inputElement;
        if(drop){
            inputElement = drop.nextElementSibling;

            inputElement.addEventListener('change', function (e) {
                previewFiles.push(...e.target.files);
                if(previewFiles.length > 0){
                    previewFiles = chapterDropButtonFile(previewFiles, allowed);
                    generarImagenesPreview(previewFiles);
                    console.log(previewFiles);
                }
            })
            drop.addEventListener('click', () => inputElement.click());
            drop.addEventListener('dragover', (e) => {
                e.preventDefault();
            });
            drop.addEventListener('drop', (e) => {
                e.preventDefault();
                previewFiles.push(...e.dataTransfer.files);
                if(previewFiles.length > 0){
                    previewFiles = chapterDropButtonFile(previewFiles, allowed);
                    generarImagenesPreview(previewFiles);
                }
            });
        }
    }
    const chapterDropButtonFile = function(files, allowed){
        let arrayPermitidos = [];
        if(!files) {
            console.log('error, no hay archivos');
            return [];
        }
        for (let i = 0; i < files.length; i++) {
            let extension = files[i].name.split('.').pop().toLowerCase();
            if(!allowed.includes(extension)){
                console.log(files[i].name, 'Tipo de archivo no permitido');
                continue;
            }
            arrayPermitidos.push(files[i]);
        }
        return arrayPermitidos;
    }
    chapterDropZone('#modalChapter #t-preview .choose', chapterFileAllow);

    const sortable = new DgSortable(previewBox, {
        draggable: '.item',
        mirror: {
            constrainDimensions: true,
        }
    });
    Array.prototype.movePreview = function (from, to) {
        console.log(this);
        this.splice(to, 0, this.splice(from, 1)[0]);
        for (let i = 0; i < this.length; i++) {
            let ext = this[i].name.split('.');
            let numGenerator = Date.now() + i;
            let name = ext[0].replace(/[0-9-]/g, `${i}-`);
            this[i] = new File([this[i]], `${i}-${numGenerator}.${ext[1]}`);
        }
    };
    sortable.on('sortable:sorted', (e) => {
        let to = e.data.newIndex;
        let from = e.data.oldIndex;

        previewFiles.movePreview(from, to);
        console.log(previewFiles);
    });
    sortable.on('sortable:stop', () =>{
        setTimeout(() => {
            let items = document.querySelectorAll('#modalChapter .md-content form .group #t-preview .item');
            for (let index = 0; index < items.length; index++) {
                items[index].setAttribute('id', 'image-'+index);
                items[index].setAttribute('index', index);
                items[index].children[1].setAttribute('data-index', index);
            }
        }, 300);
    });

    const eliminarImagen = function(files, index){
        let itemDelete = document.querySelector('#image-'+index);
        itemDelete.remove();
        let items = document.querySelectorAll('#modalChapter .md-content form .group #t-preview .item');
        files.splice(index, 1);
        for (let i = 0; i < files.length; i++) {
            let ext = files[i].name.split('.');
            let numGenerator = Date.now() + i;
            let name = ext[0].replace(/[0-9-]/g, `${i}-`);
            items[i].setAttribute('id', 'image-'+i);
            items[i].setAttribute('index', i);
            items[i].children[1].setAttribute('data-index', i);
            files[i] = new File([files[i]], `${i}-${numGenerator}.${ext[1]}`);
        }

        return files;
    }
    document.addEventListener('click', function(e){
        if (!e.target.matches('.c-remove')) return;
        e.preventDefault();

        let indx = e.target.getAttribute('data-index');

        previewFiles = eliminarImagen(previewFiles, indx);
    })

    chapterForm.addEventListener('submit', async function(e){
        e.preventDefault();

        if(inputCtName.value == ""){
            inputCtName.focus();
            addClass(inputCtName, 'error');

            Toastify({
                text: "Campo nombre requerido",
                className: "error",
                duration: 5000,
                newWindow: true,
                close: true,
                gravity: "top", // `top` or `bottom`
                position: "center", // `left`, `center` or `right`
            }).showToast();

            return true;
        }else{
            removeClass(inputCtName, 'error');
        }
        if(inputCtSlug.value == ""){
            inputCtSlug.focus();
            addClass(inputCtSlug, 'error');

            Toastify({
                text: "Campo slug requerido",
                className: "error",
                duration: 5000,
                newWindow: true,
                close: true,
                gravity: "top", // `top` or `bottom`
                position: "center", // `left`, `center` or `right`
            }).showToast();

            return true;
        }else{
            removeClass(inputCtSlug, 'error');
        }

        let chapterForm = document.querySelector('#modalChapter form');
        let listChapters = document.querySelector('.fm-manga form .chapters .list .simplebar-content');
        let formData = new FormData(chapterForm);

        let fieldToken = formData.get('_token')
        let fieldMangaId = formData.get('manga_id');
        let fieldName = formData.get('name');
        let fieldSlug = formData.get('slug');
        let fieldDisk = formData.get('disk');

        let fieldType = formData.get('chaptertype');
        let fieldPrice = formData.get('price');

        let editorContent = tinymce.get('createChapterContent').getContent();

        let rdo = document.querySelector('#modalChapter .radios input:checked');
        if(rdo.value == "manga"){
            if(previewFiles.length < 1){
                Toastify({
                    text: "Debes agregar una imagen por lo menos.",
                    className: "error",
                    duration: 5000,
                    newWindow: true,
                    close: true,
                    gravity: "top", // `top` or `bottom`
                    position: "center", // `left`, `center` or `right`
                }).showToast();
                return true;
            }
        }else{
            if(rdo.value == "novel"){
                if(editorContent == ""){
                    Toastify({
                        text: "Contenido no debe ir vacio",
                        className: "error",
                        duration: 5000,
                        newWindow: true,
                        close: true,
                        gravity: "top", // `top` or `bottom`
                        position: "center", // `left`, `center` or `right`
                    }).showToast();
                    return true;
                }
            }
        }

        formData.append('content', JSON.stringify(editorContent));
        let fieldContent = formData.get('content');
        
        await axios.post(route('createChapter.store', [fieldMangaId]), {
            _token: fieldToken,
            name: fieldName,
            slug: fieldSlug,
            price: fieldPrice,
            disk: fieldDisk,
            chaptertype: fieldType,
            content: fieldContent
        }, {
            headers:{
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'multipart/form-data'
            }
        }).then(function (response){
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
                const { id, manga_id } = response.data.item;
                let res = new Promise(async (resolve, reject) => {
                    const arrayImages = Object.values(previewFiles);
                    if(arrayImages.length > 0){
                        let count = 0;
                        for(const item of arrayImages){
                            const imageStatus = document.querySelector('#image-'+ count);
                            addClass(imageStatus.children[1], 'spin');
                            await axios.post(route('uploadSingle.store'), {
                                chapter_id: id,
                                manga_id,
                                disk: fieldDisk,
                                image: item
                            }, {
                                headers:{
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'multipart/form-data'
                                }
                            }).then(function (response){
                                console.log(response);
                                if(response.data.status == "success"){
                                    addClass(imageStatus.children[1], 'uploaded');
                                    imageStatus.children[1].innerHTML = '<i class="fa-solid fa-check"></i>';
                                }
                            })
                            .catch(function (error){
                                // handle error
                                console.log('error: ',error);
                            });
                            console.log(count);
                            if(count === arrayImages.length -1){
                                resolve()
                            }
                            count++;
                        }
                    }else{
                        resolve()
                    }
                });
                res.then(() => {
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
                    let manga_slug = response.data.manga_slug;
                    let divItem = document.createElement('div');
                    divItem.classList.add('item');
                    divItem.setAttribute('id', 'm-'+item.id)
                    divItem.innerHTML = `
                        <div class="name">
                            ${item.name}
                        </div>
                        <div class="actions">
                            <a href="${route('chapter_viewer.index', {manga_slug,chapter_slug: item.slug})}" data-id="${item.id}" class="botn view" target="_blank">
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
                        previewFiles = [];
                        limpiarPreview();
                        clearBodyScroll();
                        chapterForm.reset();

                        tinymce.remove();

                        removeClass(tManga, 'hidden');
                        addClass(tNovel, 'hidden');

                        removeClass(createChapter, 'opn');
                    }, 1000)
                });
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
            console.log('error: ',error);
        });
    });
}
function generarImagenesPreview(images){
    limpiarPreview();
    let previewBox = document.querySelector('#t-preview');
    let lastItem = document.querySelector('#t-preview .item:last-of-type');
    let cuentaIndex;
    if(lastItem){
        cuentaIndex = Number(lastItem.getAttribute('index')) + 1;
    }else{
        cuentaIndex = 0;
    }
    for(let i = 0; i < images.length; i++){
        let imageUrl = URL.createObjectURL(images[i]);
        let imageDiv = document.createElement('div');
        let cuentaFinal = cuentaIndex + i;
        addClass(imageDiv, 'item');
        imageDiv.setAttribute('id', 'image-'+cuentaFinal);
        imageDiv.setAttribute('index', cuentaFinal);
        imageDiv.innerHTML = `
            <img src="${imageUrl}" alt="preview" />
            <div class="up-status">
                <i class="fa-solid fa-circle-notch"></i>
            </div>
            <div class="c-remove" data-index="${cuentaFinal}">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </div>
        `;

        previewBox.append(imageDiv);
    }
}

function limpiarPreview(){
    let items = document.querySelectorAll('#t-preview .item');
    if(items){
        items.forEach(item =>{
            item.remove();
        })
    }
}


// ? Upload chapter


// :OPEN
const openPopButton = document.querySelector('.frmo.fm-manga .main .section.chapters .buttons a#popup-chapter');
if(openPopButton){
    const divPop = document.querySelector('.frmo.fm-manga .main .section.chapters .chapter__upload');
    openPopButton.addEventListener('click', function(e){
        e.preventDefault();
        slideToggle(divPop);
    });
    
}

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
            console.log(e.dataTransfer.files);
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
    let disk = document.querySelector('.frmo.fm-manga .main .section.chapters .chapter__upload .disks input:checked');

    dataForm.set('disk', disk.value);
    dataForm.append("chapters", file);

    let uploadBar = document.querySelector('.fm-manga .u__bar');
    let progressBar = document.querySelector('.fm-manga .u__bar .u__progress')

    axios.post(route('uploadChapter.store', [mangaId]), dataForm, {
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        onUploadProgress: (progressEvent) =>{
            const progress = (progressEvent.loaded / progressEvent.total) * 95;
            uploadBar.style.display = "block";
            progressBar.style.width = progress + "%";
        } 
    }).then(function (response){
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
                let manga_slug = element.manga_slug;
                let divItem = document.createElement('div');
                divItem.classList.add('item');
                divItem.setAttribute('id', 'm-'+item.id);
                divItem.innerHTML = `
                    <div class="name">
                        ${item.name}
                    </div>
                    <div class="actions">
                        <a href="${route('chapter_viewer.index', {manga_slug,chapter_slug: item.slug})}" data-id="${item.id}" class="botn view" target="_blank">
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
        progressBar.style.width = "100%";
        setTimeout(() =>{
            uploadBar.style.display = "none";
            progressBar.style.width = 0;
        }, 300);
    })
    .catch(function (error){
        // handle error
        console.log(error);
    });

}

let nn = new Modalerts();

// :DELETE MANGA

// Listen to all click events on the document
document.addEventListener('click', function (e) {

	// If the clicked element does not have the .click-me class, ignore it
	if (!e.target.matches('.mangaDelete')) return;

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
            axios.delete(route('manga.destroy', [id]), {
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
                    window.location.href = route('manga.index');
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

// :DELETE CHAPTER

let deleteButtons = document.querySelectorAll('main.main .box .frmo.fm-manga .main .section.chapters .list .item .actions .botn.delete');

// Listen to all click events on the document
document.addEventListener('click', function (e) {

	// If the clicked element does not have the .click-me class, ignore it
	if (!e.target.matches('.botn.delete')) return;

    e.preventDefault();

	let id = e.target.getAttribute('data-id');
    nn.fire({
        type: "warning",
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

// :UPDATE CHAPTER

document.addEventListener('click', function (e) {

	if (!e.target.matches('.botn.edit')) return;

    e.preventDefault();

	let id = e.target.getAttribute('data-id');
    let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    axios.post(route('chapters.show', [id]), {
        headers:{
            'X-CSRF-TOKEN': csrf
        }
    }).then(function (response){
        removeBodyScroll();
        if(response.status == 200){
            let { chapter } = response.data;
            let siteUrl = window.location.origin;
            let box = document.querySelector('.main.main .box');
            let divChapter = document.createElement('div');
            const disk = chapter.disk;

            let images = [];
            if(chapter.images){
                images = JSON.parse(chapter.images);
            }

            let lastItemDiv = document.querySelector('#u-preview .item:last-of-type');
            let cuentaItemIndex;
            if(lastItemDiv){
                cuentaItemIndex = Number(lastItemDiv.getAttribute('index')) + 1;
            }else{
                cuentaItemIndex = 0;
            }
            divChapter.setAttribute('id', 'updateChapter');
            addClass(divChapter, 'modal-chapter opn');

            let url;
            switch (disk) {
                case "ftp":
                    url = import.meta.env.VITE_FTP_URL;
                    break;
                case "sftp":
                    url = import.meta.env.VITE_SFTP_URL;
                    break;
                case "s3":
                    url = import.meta.env.VITE_S3_URL;
                    break;
            
                default:
                    url = import.meta.env.VITE_STORAGE_LOCAL;
                    break;
            }

            divChapter.innerHTML = `
                <div class="md-title">
                    <h4>Actualizar capítulo</h4>
                    <div class="md-close">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>
                <div class="md-content">
                    <form action="${route('chapters.update', [chapter.id])}" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="${csrf}">
                        <input type="hidden" name="manga_id" value="${chapter.manga_id}">
                        <div class="group">
                            <label for="ct-name">Name</label>
                            <input type="text" name="name" id="ct-name" value="${chapter.name}">
                        </div>
                        <div class="group">
                            <label for="ct-slug">Slug</label>
                            <input type="text" name="slug" id="ct-slug" value="${chapter.slug}">
                        </div>
                        <div class="group range">
                            <label>Price</label>
                            <input type="number" name="price" id="ct-range" value="${chapter.price ? Number(chapter.price) : ''}">
                        </div>
                        <div class="group type">
                            <label>Tipo</label>
                            <div class="radios rdos">
                                <label for="rd-1">
                                    <input type="radio" name="chaptertype" value="manga" id="rd-1" ${chapter.type == 'manga'? 'checked': ''}>
                                    <div class="rdo">
                                        <div class="inpt"></div>
                                        <div class="name">Manga</div>
                                    </div>
                                </label>
                                <label for="rd-0">
                                    <input type="radio" name="chaptertype" value="novel" id="rd-0" ${chapter.type == 'novel'? 'checked': ''}>
                                    <div class="rdo">
                                        <div class="inpt"></div>
                                        <div class="name">Novela</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="group upload">
                            <label>Subir a</label>
                            <div class="disks rdos">
                                <label for="erdo-1">
                                    <input type="radio" name="disk" value="public" id="erdo-1" ${chapter.disk == 'public'? 'checked': ''}>
                                    <div class="rdo">
                                        <div class="inpt"></div>
                                        <div class="name">Local</div>
                                    </div>
                                </label>
                                ${(import.meta.env.VITE_FTP_HOST && import.meta.env.VITE_FTP_HOST != "")?
                                `
                                    <label for="erdo-2">
                                        <input type="radio" name="disk" value="ftp" id="erdo-2" ${chapter.disk == 'ftp'? 'checked': ''}>
                                        <div class="rdo">
                                            <div class="inpt"></div>
                                            <div class="name">FTP</div>
                                        </div>
                                    </label>
                                `
                                : ''}
                                ${(import.meta.env.VITE_SFTP_HOST && import.meta.env.VITE_SFTP_HOST != "")?
                                `
                                    <label for="erdo-3">
                                        <input type="radio" name="disk" value="sftp" id="erdo-3" ${chapter.disk == 'sftp'? 'checked': ''}>
                                        <div class="rdo">
                                            <div class="inpt"></div>
                                            <div class="name">FTP</div>
                                        </div>
                                    </label>
                                `
                                : ''}
                                ${(import.meta.env.VITE_S3_HOST && import.meta.env.VITE_S3_HOST != "")?
                                `
                                    <label for="erdo-4">
                                        <input type="radio" name="disk" value="s3" id="erdo-4" ${chapter.disk == 's3'? 'checked': ''}>
                                        <div class="rdo">
                                            <div class="inpt"></div>
                                            <div class="name">Amazon S3</div>
                                        </div>
                                    </label>
                                `
                                : ''}
                            </div>
                        </div>
                        <div class="group ${chapter.type == 'novel'? '': 'hidden'}" id="u-novel">
                            <label for="ct-content">Contenido</label>
                            <div id="chapterContent"></div>
                        </div>
                        <div class="group ${chapter.type == 'manga'? '': 'hidden'}" id="u-manga">
                            <label for="ct-iamges">Images</label>
                            <div id="u-preview">
                                <div class="file">
                                    <div class="choose">Agregar</div>
                                    <input type="file" name="images[]" accept="image/*" multiple id="ct-images"/>
                                </div>
                                ${chapter.images ? images.map((item, index) => `
                                    <div class="item" id="image-${cuentaItemIndex + index}" index="${cuentaItemIndex + index}">
                                        <img src="${url+item}" alt="preview" />
                                        <div class="u-remove" data-index="${cuentaItemIndex + index}">
                                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </div>
                                    </div>
                                `).join("") : ''}
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="md-buttons flex items-center space-x-2">
                            <button type="submit" class="text-white bg-vo-green font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:bg-vo-green-over" id="updateChapter">Guardar</button>
                        </div>
                    </form>
                </div>
            `;
            box.append(divChapter);
            let conten = "";
            if(chapter.content && chapter.content != ""){
                conten = JSON.parse(chapter.content);
                conten = conten.replace(/\n/g, '<br>');
            }

            tinymce.init({
                selector: '#chapterContent',
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                toolbar: 'undo redo | blocks fontfamily backcolor | bold italic underline strikethrough | link image table | align lineheight | numlist bullist indent outdent | emoticons charmap',
                menubar: false,
                height: 600,
                setup: function (editor) {
                    editor.on('init', function (e) {
                        editor.setContent(conten);
                    });
                }
            });
            let inputCtSlug = document.querySelector('#updateChapter #ct-slug');

            inputCtSlug.addEventListener('input', function(e){
                inputCtSlug.value = sluggify(e.target.value);
            });

            let previewImages = [];

            // Dropzone chapter images
            let chapterFileAllow = ['jpeg', 'jpg', 'png', 'gif'];
            const updateChapterDropZone = function(zone, allowed){
                const drop = document.querySelector(zone);
                let inputElement;
                if(drop){
                    inputElement = drop.nextElementSibling;

                    inputElement.addEventListener('change', function (e) {
                        previewImages = [];
                        previewImages.push(...e.target.files);
                        if(previewImages.length > 0){
                            previewImages = updateChapterDropButtonFile(previewImages, allowed);
                            subirGenerarImagenes(previewImages, id, chapter.id);
                        }
                    })
                    drop.addEventListener('click', () => inputElement.click());
                    drop.addEventListener('dragover', (e) => {
                        e.preventDefault();
                    });
                    drop.addEventListener('drop', (e) => {
                        e.preventDefault();
                        previewImages = [];
                        previewImages.push(...e.dataTransfer.files);
                        if(previewImages.length > 0){
                            previewImages = updateChapterDropButtonFile(previewImages, allowed);
                            subirGenerarImagenes(previewImages, id, chapter.id);
                        }
                    });
                }
            }
            const updateChapterDropButtonFile = function(files, allowed){
                let arrayPermitidos = [];
                if(!files) {
                    console.log('error, no hay archivos');
                    return [];
                }
                for (let i = 0; i < files.length; i++) {
                    let extension = files[i].name.split('.').pop().toLowerCase();
                    if(!allowed.includes(extension)){
                        console.log(files[i].name, 'Tipo de archivo no permitido');
                        continue;
                    }
                    arrayPermitidos.push(files[i]);
                }
                return arrayPermitidos;
            }
            updateChapterDropZone('#updateChapter #u-preview .choose', chapterFileAllow);

            let draggableZone = document.querySelector('#u-preview');
            const sortable = new DgSortable(draggableZone, {
                draggable: '.item',
                mirror: {
                    constrainDimensions: true,
                }
            });
            Array.prototype.organizarImagenes = function (from, to) {
                this.splice(to, 0, this.splice(from, 1)[0]);
            };
            sortable.on('sortable:sorted', (e) => {
                let to = e.data.newIndex;
                let from = e.data.oldIndex;
                images.organizarImagenes(from, to);
            });
            sortable.on('sortable:stop', () =>{
                setTimeout(() => {
                    let items = document.querySelectorAll('#updateChapter .md-content form .group #u-preview .item');
                    for (let index = 0; index < items.length; index++) {
                        items[index].setAttribute('id', 'image-'+index);
                        items[index].setAttribute('index', index);
                        items[index].children[1].setAttribute('data-index', index);
                    }
                    if(items.length > 1){
                        axios.post(route('actualizarOrdenChapter.imagenes', [id]), {
                            images
                        },{
                            headers:{
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        }).then(function (response){
                            console.log(response);
                            Toastify({
                                text: "Orden actualizado",
                                className: "success",
                                duration: 2000,
                                newWindow: true,
                                close: true,
                                gravity: "top", // `top` or `bottom`
                                position: "center", // `left`, `center` or `right`
                            }).showToast();
                        })
                        .catch(function (error){
                            console.log('error: ',error);
                        });
                    }
                }, 300);
            });

            const eliminarImagen = function(files, index, chapterid){

                axios.post(route('eliminarChapter.imagenes', [chapterid]), {
                    index
                },{
                    headers:{
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(function (response){
                    console.log(response);
                    let clearInput = document.querySelector('#updateChapter #ct-images');
                    previewImages = [];
                    clearInput.value = "";
                    Toastify({
                        text: "Imagen eliminada",
                        className: "success",
                        duration: 2000,
                        newWindow: true,
                        close: true,
                        gravity: "top", // `top` or `bottom`
                        position: "center", // `left`, `center` or `right`
                    }).showToast();
                })
                .catch(function (error){
                    console.log('error: ',error);
                });

                let itemDelete = document.querySelector('#updateChapter #u-preview #image-'+index);
                itemDelete?.remove();
                let items = document.querySelectorAll('#updateChapter .md-content form .group #u-preview .item');
                files.splice(index, 1);
                for (let i = 0; i < files.length; i++) {
                    items[i]?.setAttribute('id', 'image-'+i);
                    items[i]?.setAttribute('index', i);
                    items[i]?.children[1].setAttribute('data-index', i);
                }
                return files;
            }    
            document.addEventListener('click', function(e){
                if (!e.target.matches('.u-remove')) return;
                e.preventDefault();
                let indx = e.target.getAttribute('data-index');
        
                images = eliminarImagen(images, indx, chapter.id);
            });

            document.addEventListener('click', function(e){
                if (!e.target.matches('#updateChapter .md-close')) return;
                e.preventDefault();
                if(tinymce){
                    tinymce.remove();
                }
                closeUpdateModal()
            })
            document.addEventListener('click', function(e){
                if (!e.target.matches('#updateChapter .md-content form .radios label input')) return;
            
                let uManga = document.querySelector('#u-manga');
                let uNovel = document.querySelector('#u-novel');
            
                if(e.target.value == "novel"){
                    addClass(uManga, 'hidden');
                    removeClass(uNovel, 'hidden');
                }
                if(e.target.value == "manga"){
                    addClass(uNovel, 'hidden');
                    removeClass(uManga, 'hidden');
                }
            });

            // Update chapter
            document.addEventListener('click', async function(e){
                if (!e.target.matches('#updateChapter')) return;
                e.preventDefault();

                let inputCtName = document.querySelector('#updateChapter #ct-name');
                let inputCtSlug = document.querySelector('#updateChapter #ct-slug');

                if(inputCtName.value == ""){
                    inputCtName.focus();
                    addClass(inputCtName, 'error');
        
                    Toastify({
                        text: "Campo nombre requerido",
                        className: "error",
                        duration: 5000,
                        newWindow: true,
                        close: true,
                        gravity: "top", // `top` or `bottom`
                        position: "center", // `left`, `center` or `right`
                    }).showToast();
        
                    return true;
                }else{
                    removeClass(inputCtName, 'error');
                }
                if(inputCtSlug.value == ""){
                    inputCtSlug.focus();
                    addClass(inputCtSlug, 'error');
        
                    Toastify({
                        text: "Campo slug requerido",
                        className: "error",
                        duration: 5000,
                        newWindow: true,
                        close: true,
                        gravity: "top", // `top` or `bottom`
                        position: "center", // `left`, `center` or `right`
                    }).showToast();
        
                    return true;
                }else{
                    removeClass(inputCtSlug, 'error');
                }
                let rdo = document.querySelector('#updateChapter .radios input:checked');
                let editorContent = tinymce.get('chapterContent').getContent();
                
                if(rdo.value == "manga"){
                    if(previewImages.length < 1 && images.length < 1){
                        Toastify({
                            text: "Debes agregar una imagen por lo menos.",
                            className: "error",
                            duration: 5000,
                            newWindow: true,
                            close: true,
                            gravity: "top", // `top` or `bottom`
                            position: "center", // `left`, `center` or `right`
                        }).showToast();
                        return true;
                    }
                }
                if(rdo.value == "novel"){
                    if(editorContent == ""){
                        Toastify({
                            text: "Contenido no debe ir vacio",
                            className: "error",
                            duration: 5000,
                            newWindow: true,
                            close: true,
                            gravity: "top", // `top` or `bottom`
                            position: "center", // `left`, `center` or `right`
                        }).showToast();
                        return true;
                    }
                }
                let form = document.querySelector('#updateChapter form');
                let formData = new FormData(form);
                
                let fieldName = formData.get('name');
                let fieldSlug = formData.get('slug');
                let fieldType = formData.get('chaptertype');
                let fieldPrice = formData.get('price');
                let fieldDisk = formData.get('disk');

                formData.append('content', JSON.stringify(editorContent));
                let fieldContent = formData.get('content');

                await axios.patch(route('chapters.update', [chapter.id]), {
                    name: fieldName,
                    slug: fieldSlug,
                    price: fieldPrice,
                    type: fieldType,
                    disk: fieldDisk,
                    content: fieldContent
                },{
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
                        let updateName = document.querySelector('#m-'+id+' .name');
                        let item = response.data.item;

                        updateName.textContent = item.name;
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
                    console.log(response);
                })
                .catch(function (error){
                    console.log('error: ',error);
                });
            });
            
        }
    })
    .catch(function (error){
        // handle error
        console.log(error);
    });

});

function isJson(json) {
    try {
        JSON.parse(json);
    } catch (e) {
        return false;
    }
    return true;
}

function closeUpdateModal(){
    let updateChapter = document.getElementById('updateChapter');
    addClass(updateChapter, 'clg');
    removeClass(updateChapter, 'opn');
    setTimeout(() => {
        updateChapter.remove();
    }, 300);
    clearBodyScroll();
}

async function subirGenerarImagenes(images, mangaid, chapterid){
    limpiarPreview();

    let disk = document.querySelector('#updateChapter.modal-chapter .md-content form .upload input:checked').value;
    let previewBox = document.querySelector('#u-preview');
    let lastItem = document.querySelector('#u-preview .item:last-of-type');
    let cuentaIndex;
    if(lastItem){
        cuentaIndex = Number(lastItem.getAttribute('index')) + 1;
    }else{
        cuentaIndex = 0;
    }
    for(let i = 0; i < images.length; i++){
        let imageUrl = URL.createObjectURL(images[i]);
        let imageDiv = document.createElement('div');
        let cuentaFinal = cuentaIndex + i;
        addClass(imageDiv, 'item');
        imageDiv.setAttribute('id', 'image-'+cuentaFinal);
        imageDiv.setAttribute('index', cuentaFinal);
        imageDiv.innerHTML = `
            <img src="${imageUrl}" alt="preview" />
            <div class="up-status">
                <i class="fa-solid fa-circle-notch"></i>
            </div>
            <div class="u-remove" data-index="${cuentaFinal}">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </div>
        `;

        previewBox.append(imageDiv);
    }
    let res = new Promise(async (resolve, reject) => {
        const arrayImages = Object.values(images);
        let count = 0;
        let altCount = 0;
        if(lastItem){
            count = Number(lastItem.getAttribute('index')) + 1;
        }
        for(const item of arrayImages){
            const imageStatus = document.querySelector('#image-'+ count);
            addClass(imageStatus.children[1], 'spin');
            await axios.post(route('uploadSingle.store'), {
                chapter_id: chapterid,
                manga_id: mangaid,
                disk,
                image: item
            }, {
                headers:{
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'multipart/form-data'
                }
            }).then(function (response){
                console.log(response);
                if(response.data.status == "success"){
                    addClass(imageStatus.children[1], 'uploaded');
                    imageStatus.children[1].innerHTML = '<i class="fa-solid fa-check"></i>';
                }
            })
            .catch(function (error){
                // handle error
                console.log('error: ',error);
            });
            console.log(altCount, arrayImages.length);
            if(altCount === arrayImages.length -1){
                resolve()
            }
            altCount++;
            count++;
        }
    });
    res.then(() => {
        Toastify({
            text: "Imagenes agregadas",
            className: "success",
            duration: 5000,
            newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "center", // `left`, `center` or `right`
        }).showToast();
    });
}

// :VALIDATE Update & Create Manga

const mangaForm = document.querySelector('.fm-manga > form');
if(mangaForm){
    mangaForm.addEventListener('submit', function(e){
        let status = validarFormulario();
        if(!status){
            e.preventDefault();
        }
    });

    let chapterDateInput = document.querySelector('#ch-date');
    if(chapterDateInput){
        new AirDatepicker(chapterDateInput,{
            locale: localeEs,
            dateFormat: "yyyy-MM-dd"
        });
    }
}

function validarFormulario(){
    let fieldName = document.querySelector('.fm-manga > form #m-name');
    let fieldSlug = document.querySelector('.fm-manga > form #m-slug');
    let fieldAltname = document.querySelector('.fm-manga > form #m-altname');
    let fieldDescription = document.querySelector('.fm-manga > form #m-description');
    let fieldImage = document.querySelector('.fm-manga > form #m-image');
    let fieldPublished = document.querySelector('.fm-manga > form #m-published');
    let divCategories = document.querySelector('.fm-manga > form .categories .choices');
    let fieldCategories = document.querySelector('.fm-manga > form .categories .choices .choices__input');
    let divTags = document.querySelector('.fm-manga > form .tags .choices');
    let fieldTags = document.querySelector('.fm-manga > form .tags .choices .choices__input');
    let fieldBookStatus = document.querySelector('.fm-manga > form #m-bookstatus');
    let fieldDemography = document.querySelector('.fm-manga > form #m-demography');

    if(fieldName && fieldName.value == ""){
        fieldName.focus();
        addClass(fieldName, 'error');

        Toastify({
            text: "Campo nombre requerido",
            className: "error",
            duration: 2000,
            newWindow: true,
            close: true,
            gravity: "top",
            position: "center",
        }).showToast();
        return false;
    }else if(fieldName){
        removeClass(fieldName, 'error');
    }
    if(fieldSlug && fieldSlug.value == ""){
        fieldSlug.focus();
        addClass(fieldSlug, 'error');

        Toastify({
            text: "Campo slug requerido",
            className: "error",
            duration: 2000,
            newWindow: true,
            close: true,
            gravity: "top",
            position: "center",
        }).showToast();
        return false;
    }else if(fieldSlug){
        removeClass(fieldSlug, 'error');
    }
    if(fieldImage && fieldImage.value == ""){
        let dropImage = document.querySelector('.fm-manga > form .dropzone #choose');
        addClass(dropImage, 'error');
        setTimeout(() => {
            removeClass(dropImage, 'error');
        }, 1000);

        Toastify({
            text: "Imagen destacada es requerida",
            className: "error",
            duration: 2000,
            newWindow: true,
            close: true,
            gravity: "top",
            position: "center",
        }).showToast();

        return false;
    }else if(fieldImage){
        removeClass(fieldImage, 'error');
    }
    if(divCategories && fieldCategories.value == ""){
        fieldCategories.focus();
        addClass(divCategories, 'error');

        Toastify({
            text: "Campo descripcion requerido",
            className: "error",
            duration: 2000,
            newWindow: true,
            close: true,
            gravity: "top",
            position: "center",
        }).showToast();
        return false;
    }else if(divCategories){
        removeClass(divCategories, 'error');
    }

    return true;
}