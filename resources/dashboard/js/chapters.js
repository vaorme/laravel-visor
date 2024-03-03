import { Sortable, Plugins } from "@shopify/draggable";

import OwnValidator from "./own-validator";
import { sluggify } from "./own-helpers";
import { hasClass, addClass, removeClass } from "./own-helpers";

let previewImages = [];
let chapterImages = [];
let chaptersToDelete = [];
let validator;
let isChapterEdit = false;

const chapterModal = document.getElementById('chapter-modal');
let btnChapterModal = chapterModal? new bootstrap.Modal(chapterModal) : '';
chapterModal?.addEventListener('hide.bs.modal', () =>{
    const buttonConfirm = document.querySelector('#chapterButtonConfirm');
    buttonConfirm?.removeEventListener('click', chapterContentFormSubmit);
    document.removeEventListener('click', removeImageChapter);
    setTimeout(() => {
        tinyMCE.remove('#tinymce-chapter-content');
    }, 200);
});
chapterModal?.addEventListener('show.bs.modal', async (e) => {
    previewImages = [];
    const id = e.relatedTarget.getAttribute('data-id');
    if(id){
        isChapterEdit = true;
        const element = document.querySelector('#chapter-modal .modal-content');
        element.innerHTML = `
            <div class="progress">
                <div class="progress-bar progress-bar-indeterminate bg-green"></div>
            </div>
        `;
        await axios.get("chapters/"+id).then(function (response){
            let { chapter } = response.data;
            chapterContentForm(id, chapter);
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
        isChapterEdit = false;
        chapterContentForm();
    }

    // ? ASSIGN COMIC ID TO INPUT HIDDEN
    // const inputComic = document.querySelector('form.frmo-chapter input[name="comic_id"]');
    // inputComic.setAttribute('value', id || "");

    // ? SEND CHAPTER FORM
    const button = document.querySelector('#chapterButtonConfirm');
    button?.addEventListener('click', chapterContentFormSubmit);

    // ? REMOVE IMAGE OF CHAPTER
    document.addEventListener('click', removeImageChapter);
})

// ? FUNCTION REMOVE IMAGE OF CHAPTER
function removeImageChapter(e){
    if (!e.target.matches('.i-remove')) return;
    e.preventDefault();
    const index = e.target.getAttribute('data-index');
    if(isChapterEdit){
        deleteImageChapter(chapterImages, index)
    }else{
        deleteImageChapter(previewImages, index);
    }
}

// ? DELETE IMAGE CHAPTER
async function deleteImageChapter(files, index){
    if(isChapterEdit){
        const chapter_id = document.querySelector('form.frmo-chapter input[name="chapter_id"]');
        await axios.post('chapters/images-delete/'+chapter_id.value, {
            index
        },{
            headers:{
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(function (response){
            const data = response.data;
            const clearInput = document.querySelector('.comics #chapter-modal .modal-body .chapter-images .add-more input#add-input');
            if(clearInput){
                clearInput.value = "";
            }
            if(data && data.status === "success"){
                Toastify({
                    text: data.msg,
                    className: "success",
                    duration: 2000,
                    newWindow: true,
                    close: true,
                    gravity: "top", // `top` or `bottom`
                    position: "center", // `left`, `center` or `right`
                }).showToast();
            }
            
        }).catch(function (error){
            console.log('error: ',error);
        });
    }
    const imageDelete = document.querySelector('form.frmo-chapter .chapter-images #image-'+index);
    imageDelete?.remove();

    const listOfImages = document.querySelectorAll('form.frmo-chapter .chapter-images .image');
    if(files.length > 0){
        files.splice(index, 1);
        listOfImages?.forEach((item, key) =>{
            item?.setAttribute('id', 'image-'+key);
            item?.setAttribute('index', key);
            item?.querySelector('.i-remove').setAttribute('data-index', key);
        })
    }
    return files;
}

function chapterContentForm(manga_id, chapter){
    const element = document.querySelector('#chapter-modal .modal-content');
    chapterImages = [];
    if(chapter && chapter.images){
        chapterImages = JSON.parse(chapter.images);
    }

    let lastItemDiv = document.querySelector('#chapter-modal .chapter-images .image:last-of-type');
    let itemIndexCount;
    if(lastItemDiv){
        itemIndexCount = Number(lastItemDiv.getAttribute('index')) + 1;
    }else{
        itemIndexCount = 0;
    }

    // * REMOVE TINYMCE
    tinyMCE.remove('#tinymce-chapter-content');

    // * CHAPTER DISK
    const disk = chapter? chapter.disk: '';
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
		case "bunnycdn":
			url = import.meta.env.VITE_BUNNY_FTP_URL;
			break;
    
        default:
            url = import.meta.env.VITE_STORAGE_LOCAL;
            break;
    }
    element.innerHTML = `
        <div class="modal-header">
            <h5 class="modal-title">${isChapterEdit? 'Actualizar' : 'Agregar'} capítulo</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" x-data="{
            chapter_type: ['${chapter && chapter.type? chapter.type : 'comic'}'],
            chapter_disk: ['${chapter && chapter.disk? chapter.disk : 'public'}']
        }">
            <form action="/" method="POST" class="frmo-chapter${isChapterEdit? ' update' : ''}" enctype="multipart/form-data">
                <input type="hidden" name="chapter_id" value="${chapter && chapter.id? chapter.id : ''}">
                <div class="row row-cards">
                    <div class="col-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="name" required value="${chapter && chapter.name? chapter.name : ''}"/>
                        <div class="invalid-feedback">
                            Campo <b>Nombre</b> es requerido
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Slug</label>
                        <input type="text" class="form-control" name="slug" required value="${chapter && chapter.slug? chapter.slug : ''}"/>
                        <div class="invalid-feedback">
                            Campo <b>Slug</b> es requerido
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Precio</label>
                        <input type="number" class="form-control" name="price" value="${chapter && chapter.price? chapter.price : ''}"/>
                        <div class="invalid-feedback">
                            Campo <b>Precio</b> es requerido
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Tipo:</label>
                        <div class="chapter-type form-selectgroup-boxes row mb-3">
                            <div class="col-lg-6">
                            <label class="form-selectgroup-item">
                                    <input type="radio" name="type" value="comic" x-model="chapter_type" class="form-selectgroup-input" checked>
                                    <span class="form-selectgroup-label d-flex align-items-center p-3">
                                        <span class="me-3">
                                            <span class="form-selectgroup-check"></span>
                                        </span>
                                        <span class="form-selectgroup-label-content">
                                            <span class="form-selectgroup-title strong mb-1">Comic</span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-selectgroup-item">
                                    <input type="radio" name="type" value="novel" x-model="chapter_type" class="form-selectgroup-input">
                                    <span class="form-selectgroup-label d-flex align-items-center p-3">
                                        <span class="me-3">
                                            <span class="form-selectgroup-check"></span>
                                        </span>
                                        <span class="form-selectgroup-label-content">
                                            <span class="form-selectgroup-title strong mb-1">Novela</span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12" x-show="chapter_type.includes('comic')">
                        <label class="form-label">Subir a:</label>
                        <div class="form-selectgroup-boxes row mb-3">
                            <div class="col-lg-4">
                            	<label class="form-selectgroup-item">
                                    <input type="radio" name="disk" value="public" x-model="chapter_disk" class="form-selectgroup-input" checked>
                                    <span class="form-selectgroup-label d-flex align-items-center p-3">
                                        <span class="me-3">
                                            <span class="form-selectgroup-check"></span>
                                        </span>
                                        <span class="form-selectgroup-label-content">
                                            <span class="form-selectgroup-title strong mb-1">Local</span>
                                        </span>
                                    </span>
                                </label>
                            </div>
							${import.meta.env.VITE_FTP_URL? `
								<div class="col-lg-4">
									<label class="form-selectgroup-item">
										<input type="radio" name="disk" value="ftp" x-model="chapter_disk" class="form-selectgroup-input">
										<span class="form-selectgroup-label d-flex align-items-center p-3">
											<span class="me-3">
												<span class="form-selectgroup-check"></span>
											</span>
											<span class="form-selectgroup-label-content">
												<span class="form-selectgroup-title strong mb-1">FTP</span>
											</span>
										</span>
									</label>
								</div>
							` : ''}
							${import.meta.env.VITE_BUNNY_FTP_URL? `
								<div class="col-lg-4">
									<label class="form-selectgroup-item">
										<input type="radio" name="disk" value="bunnycdn" x-model="chapter_disk" class="form-selectgroup-input">
										<span class="form-selectgroup-label d-flex align-items-center p-3">
											<span class="me-3">
												<span class="form-selectgroup-check"></span>
											</span>
											<span class="form-selectgroup-label-content">
												<span class="form-selectgroup-title strong mb-1">BUNNY.NET</span>
											</span>
										</span>
									</label>
								</div>
							` : ''}
                        </div>
                    </div>
                    <div class="col-12" x-show="chapter_type.includes('comic')">
                        <label class="form-label">Imagenes</label>
                        <div class="chapter-images">
                            <div class="add-more">
                                <div class="add-choose">Agregar más</div>
                                <input type="file" id="add-input" name="images[]" accept="image/*" multiple>
                            </div>
                            ${chapter && chapter.images ? chapterImages.map((item, index) => `
                                <div class="image" id="image-${itemIndexCount + index}" index="${itemIndexCount + index}">
                                    <div class="i-remove" data-index="${itemIndexCount + index}">
                                        <svg aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </div>
                                    <div class="i-drag">
                                        <img src="${url+item}" alt="preview" />
                                    </div>
                                </div>
                            `).join("") : ''}
                        </div>
                    </div>
                    <div class="col-12" x-show="chapter_type.includes('novel')">
                        <label class="form-label">Contenido</label>
                        <textarea class="form-control" rows="42" name="content" id="tinymce-chapter-content">${chapter && chapter.content? JSON.parse(chapter.content) : ''}</textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn me-auto" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" id="chapterButtonConfirm" class="btn btn-${isChapterEdit? 'primary' : 'success'}">${isChapterEdit? 'Actualizar' : 'Crear'}</button>
        </div>
    `;
    // * TINYMCE
    let chapterOptions = {
        selector: '#tinymce-chapter-content',
        height: 500,
        menubar: false,
        statusbar: false,
        plugins: ["advlist", "autolink", "lists", "link", "image", "charmap", "preview", "anchor", "searchreplace", "visualblocks", "code", "fullscreen","insertdatetime","media", "table", "code", "help", "wordcount"],
        toolbar: 'undo redo | formatselect | ' +
            'bold italic forecolor backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; -webkit-font-smoothing: antialiased; }'
    }
    if (localStorage.getItem("tablerTheme") === 'dark') {
        options.skin = 'oxide-dark';
        options.content_css = 'dark';
    }
    tinyMCE.init(chapterOptions);

    // * INITIALIZE FORM VALIDATION
    const frmo = document.querySelector('form.frmo-chapter');
    frmo?.addEventListener('submit', function(e){
        e.preventDefault();
    });
    if(frmo){
        validator = new OwnValidator(frmo);
        validator.validateOnChange();
    }

    // * GENERATE CHAPTER SLUG
    const inputTitle = document.querySelector('form.frmo-chapter:not(.update) input[name="name"]');
    const inputSlug = document.querySelector('form.frmo-chapter input[name="slug"]');
    inputTitle?.addEventListener('input', function(){
        inputSlug.value = sluggify(inputTitle.value);
    });
    inputSlug?.addEventListener('input', function(){
        inputSlug.value = sluggify(inputSlug.value);
    });

    // * DRAGGABLE IMAGES
    const sortableImages = new Sortable(document.querySelectorAll('#chapter-modal .modal-body .chapter-images'), {
        draggable: '#chapter-modal .modal-body .chapter-images .image',
        handle: '#chapter-modal .modal-body .chapter-images .i-drag',
        mirror: {
            constrainDimensions: true
        },
        delay: 0,
        plugins: [Plugins.SortAnimation],
        swapAnimation: {
            duration: 200,
            easingFunction: 'ease-in-out',
        },
    });

    Array.prototype.orderImages = function (from, to) {
        this.splice(to, 0, this.splice(from, 1)[0]);
    };

    sortableImages.on('sortable:stop', (e) => {
        if(isChapterEdit){
            let to = e.data.newIndex;
            let from = e.data.oldIndex;
            if(chapterImages.length > 0 && from !== to){
                chapterImages.orderImages(from, to);
                setTimeout(() => {
                    const items = document.querySelectorAll('#chapter-modal .modal-body .chapter-images .image');
                    for (let index = 0; index < items.length; index++) {
                        items[index].setAttribute('id', 'image-'+index);
                        items[index].setAttribute('index', index);
                        items[index].children[1].setAttribute('data-index', index);
                    }
                    if(items.length > 1){
                        axios.post('chapters/images-reorder/'+chapter.id, {
                            images: chapterImages
                        },{
                            headers:{
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        }).then(function (response){
                            console.log(response);
                            const data = response.data;
                            if(data && data.status === "success"){
                                Toastify({
                                    text: "Orden actualizado",
                                    className: "success",
                                    duration: 2000,
                                    newWindow: true,
                                    close: true,
                                    gravity: "top", // `top` or `bottom`
                                    position: "center", // `left`, `center` or `right`
                                }).showToast();
                            }
                        })
                        .catch(function (error){
                            console.log('error: ',error);
                        });
                    }
                }, 300);
            }
        }else{
            let to = e.data.newIndex;
            let from = e.data.oldIndex;
            if(previewImages.length > 0 && from !== to){
                previewImages.orderImages(from, to);
                setTimeout(() => {
                    const items = document.querySelectorAll('#chapter-modal .modal-body .chapter-images .image');
                    for (let index = 0; index < items.length; index++) {
                        items[index].setAttribute('id', 'image-'+index);
                        items[index].setAttribute('index', index);
                        items[index].querySelector('.i-remove').setAttribute('data-index', index);
                    }
                }, 300);
            }
        }
    });

    //* DROPZONE CHAPTER IMAGES
    let chapterFileAllow = ['jpeg', 'jpg', 'png', 'gif'];
    const addMoreDropZone = function(zone, allowed){
        const drop = document.querySelector(zone);
        let inputElement;
		let currentImages;
        if(drop){
            inputElement = drop.nextElementSibling;
            inputElement.addEventListener('change', function (e) {
                currentImages = [];
                previewImages.push(...e.target.files);
				currentImages.push(...e.target.files);
                if(previewImages.length > 0){
                    previewImages = addMoreButton(previewImages, allowed);
					currentImages = addMoreButton(currentImages, allowed);
                    if(isChapterEdit){
                        generateImages(currentImages, manga_id, chapter.id);
                    }else{
                        generateImages(currentImages);
                    }
                }
				console.log('preview1: ', previewImages);
            })
            drop.addEventListener('click', () => inputElement.click());
            drop.addEventListener('dragover', (e) => {
                e.preventDefault();
            });
            drop.addEventListener('drop', (e) => {
                e.preventDefault();
                currentImages = [];
                previewImages.push(...e.dataTransfer.files);
				currentImages.push(...e.dataTransfer.files);
                if(previewImages.length > 0){
                    previewImages = addMoreButton(previewImages, allowed);
					currentImages = addMoreButton(currentImages, allowed);
                    if(isChapterEdit){
                        generateImages(currentImages, manga_id, chapter.id);
                    }else{
                        generateImages(currentImages);
                    }
                }
				console.log('preview2: ', previewImages);
            });
        }
    }
    const addMoreButton = function(files, allowed){
        let arrayPermitidos = [];
        if(!files) {
            console.log('error, no hay archivos');
            return [];
        }
        for (let i = 0; i < files.length; i++) {
            let extension = files[i].name.split('.').pop().toLowerCase();
            if(!allowed.includes(extension)){
                Toastify({
                    text: `.${extension} Tipo de archivo no permitido`,
                    className: "error",
                    duration: 2000,
                    newWindow: true,
                    close: true,
                    gravity: "top", // `top` or `bottom`
                    position: "center", // `left`, `center` or `right`
                }).showToast();
                continue;
            }
            arrayPermitidos.push(files[i]);
        }
        return arrayPermitidos;
    }
    addMoreDropZone('#chapter-modal .add-choose', chapterFileAllow);
}

async function chapterContentFormSubmit(){
    if(!validator.validate()){
        return true;
    }
    const chapterForm = document.querySelector('form.frmo-chapter');
    const comicID = document.querySelector('form.frmo input[name="comic_id"]');
    const chapterID = document.querySelector('form.frmo-chapter input[name="chapter_id"]');
    const formData = new FormData(chapterForm);

    const getEditorContent = tinymce.get('tinymce-chapter-content').getContent();
    const setFieldContent = formData.set('content', JSON.stringify(getEditorContent));

    const getComicName = formData.get('name');
    const getComicSlug = formData.get('slug');
    const getComicPrice = formData.get('price');
    const getComicDisk = formData.get('disk');
    const getComicType = formData.get('type');
    const getComicContent = formData.get('content');
    if(getComicType === "novel"){
        previewImages = [];
    }

    if(isChapterEdit){
        await axios.patch("chapters/"+chapterID.value, {
            name: getComicName,
            slug: getComicSlug,
            price: getComicPrice,
            disk: getComicDisk,
            type: getComicType,
            content: getComicContent
        }).then(function (response){
            const data = response.data;
            if(data && data.status === "success"){
                const chapter = data.item;
                Toastify({
                    className: 'success',
                    text: data.msg,
                    duration: 1000,
                    newWindow: false,
                    close: true,
                    gravity: "top",
                    position: "right"
                }).showToast();
                const currentChapter = document.querySelector('#m-'+chapter.id+' .name');
                currentChapter.textContent = chapter.name;

                setTimeout(() => {
                    btnChapterModal.hide();
                }, 400);
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
    }else{
        await axios.post("chapters/"+comicID.value, {
            name: getComicName,
            slug: getComicSlug,
            price: getComicPrice,
            disk: getComicDisk,
            type: getComicType,
            content: getComicContent
        }, {
            headers:{
                'Content-Type': 'multipart/form-data'
            }
        }).then(function (response){
            const data = response.data;
            if(data && data.status === "success"){
                const chapter = data.item;
                const reponseUpload = new Promise(async (resolve, reject) => {
                    const arrayImages = Object.values(previewImages);
                    if(arrayImages.length > 0){
                        let count = 0;
                        for(const item of arrayImages){
                            const currentStatus = document.querySelector('#image-'+ count + ' .i-status .i-loader');
                            addClass(currentStatus, 'uploading');
                            await axios.post("chapters/upload-single-image", {
                                chapter_id: chapter.id,
                                manga_id: chapter.manga_id,
                                disk: chapter.disk,
                                image: item
                            }, {
                                headers:{
                                    'Content-Type': 'multipart/form-data'
                                }
                            }).then(function (response){
                                console.log(response);
                                const data = response.data;
                                if(data && data.status == "success"){
                                    addClass(currentStatus, 'uploaded');
                                }
                            }).catch(function (error){
                                // handle error
                                console.log('error: ',error);
                                reject();
                            });
                            if(count === arrayImages.length -1){
                                resolve()
                            }
                            count++;
                        }
                    }else{
                        resolve()
                    }
                });
                reponseUpload.then(() =>{
                    Toastify({
                        className: 'success',
                        text: data.msg,
                        duration: 1000,
                        newWindow: false,
                        close: true,
                        gravity: "top",
                        position: "right"
                    }).showToast();
                    appendChapter(data);
                    setTimeout(() => {
                        btnChapterModal.hide();
                    }, 800);
                }).catch((error) =>{
                    console.log(error);
                })
            }else if(data && data.status === "error"){
                Toastify({
                    className: 'error',
                    text: data.msg,
                    duration: 1000,
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
}

const currentOrigin = window.location.origin;

// * APPEND CHAPTER HTML
function appendChapter(data){
    const chapter = data.item;
    const divChapters = document.querySelector('.comics .chapters .card-body');
    const createItem = document.createElement('div');
    const existsInactive = divChapters.children[0]?.classList.contains('card-inactive');
    
    if(existsInactive){
        divChapters.innerHTML = "";
    }

    addClass(divChapters, 'grid g-4');
    addClass(createItem, 'item g-col-12 border d-flex justify-content-between align-items-center rounded-2');
    createItem.setAttribute('id', "m-"+chapter.id)
    createItem.setAttribute('data-id', chapter.id)
    createItem.innerHTML = `
        <div class="lft d-flex g-4 w-full">
            <div class="inpt-select d-flex align-items-center">
                <input class="form-check-input input-del-chapters" type="checkbox" name="delete_chapters[]" value="${chapter.id}">
            </div>
            <button class="drag">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-grip-horizontal" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M5 9m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                    <path d="M5 15m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                    <path d="M12 9m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                    <path d="M12 15m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                    <path d="M19 9m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                    <path d="M19 15m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                </svg>
            </button>
            <div class="name col-8">${chapter.name}</div>
        </div>
        <div class="rig w-full d-flex justify-content-end">
            <div class="actions">
                <a href="${currentOrigin}/v/${data.manga_slug}/${chapter.slug}" data-id="${chapter.id}" class="botn view btn btn-lime btn-icon" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-player-play" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M7 4v16l13 -8z"></path>
                    </svg>
                </a>
                <button data-id="${chapter.id}" class="botn edit btn btn-bitbucket btn-icon" data-bs-toggle="modal" data-bs-target="#chapter-modal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"></path>
                        <path d="M13.5 6.5l4 4"></path>
                    </svg>
                </button>
                <button data-id="${chapter.id}" class="botn delete btn btn-pinterest btn-icon" data-bs-toggle="modal" data-bs-target="#chapter-destroy">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 6l-12 12"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    `;

    divChapters.append(createItem);
}

// ? DELETE CHAPTER
const modalChapterDestroy = document.getElementById('chapter-destroy')
let btpModalChapterDestroy = modalChapterDestroy? new bootstrap.Modal(modalChapterDestroy) : '';
modalChapterDestroy?.addEventListener('show.bs.modal', e => {
    const id = e.relatedTarget.getAttribute('data-id');
    const button = modalChapterDestroy.querySelector('#buttonConfirm');
    button.setAttribute('data-id', id);
    button?.addEventListener('click', handlerChapterDestroy);
})
modalChapterDestroy?.addEventListener('hide.bs.modal', () =>{
    const button = modalChapterDestroy.querySelector('#buttonConfirm');
    button?.removeEventListener('click', handlerChapterDestroy);
});

async function handlerChapterDestroy(){
    let buttonText = this.textContent;
    let id = this.getAttribute('data-id');
    this.disabled = true;
    this.innerHTML = `
        ${buttonText}
        <span class="input-icon-addon">
            <div class="spinner-border spinner-border-sm text-white" role="status"></div>
        </span>
    `;
    await chapterDestroy(id);
    this.disabled = false;
    this.removeAttribute('data-id');
    this.innerHTML = `
        ${buttonText}
    `;
    btpModalChapterDestroy.hide();
}

async function chapterDestroy(chapter_id){
    const id = chapter_id;
    await axios.delete("chapters/"+id).then(function (response){
        //console.log(response);
        const data = response.data;
        if(data && data.status == "success"){
            Toastify({
                className: 'success',
                text: data.msg,
                duration: 1000,
                newWindow: false,
                close: true,
                gravity: "top",
                position: "right"
            }).showToast();
            document.querySelector('#m-'+response.data.id).remove();
        }else{
            console.log(response);
        }
    }).catch(function (error){
        console.log('error:', error);
        const data = error.response.data;
        if(data && data.status === "error"){
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
    checkChaptersList();
};

// ? SELECT MULTIPLE CHAPTERS TO DELETE IT
const modalMultipleChapterDestroy = document.getElementById('md-delete-chapters');
let btpModalMultipleChapterDestroy = modalMultipleChapterDestroy? new bootstrap.Modal(modalMultipleChapterDestroy) : '';
document.addEventListener('DOMContentLoaded', function() {
    const buttonGroup = document.querySelector('form.frmo .chapters .botn-group');
    document.addEventListener('change', function(event) {
        if (event.target.tagName === 'INPUT' && event.target.classList.contains('input-del-chapters')) {
            let changedValue = event.target.value;
            const existBtn = document.querySelector('.dl-chapters');

            if (event.target.checked) {
                if (!chaptersToDelete.includes(changedValue)) {
                    chaptersToDelete.push(changedValue);
                }
            } else {
                let index = chaptersToDelete.indexOf(changedValue);
                if (index !== -1) {
                    chaptersToDelete.splice(index, 1);
                }
            }
            if(chaptersToDelete.length > 0){
                if(existBtn) return true;
                buttonGroup.insertAdjacentHTML('afterbegin', `
                    <a href="javascript:void(0)" class="botn view btn btn-pinterest dl-chapters" data-bs-toggle="modal" data-bs-target="#md-delete-chapters">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M18 6l-12 12"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                        Eliminar selecionado/s
                    </a>
                `);
            }else{
                if(existBtn){
                    existBtn.remove();
                }
            }
        }
    });
    const button = modalMultipleChapterDestroy?.querySelector('#buttonConfirm');
    modalMultipleChapterDestroy?.addEventListener('show.bs.modal', () => {
        button?.addEventListener('click', handlerMultipleDeleteChapters);
    });
    modalMultipleChapterDestroy?.addEventListener('hide.bs.modal', () =>{
        const existBtn = document.querySelector('.dl-chapters');
        if(existBtn){
            existBtn.remove();
        }
        button?.removeEventListener('click', handlerMultipleDeleteChapters);
    });
});

async function handlerMultipleDeleteChapters(){
    let buttonText = this.textContent;
    this.disabled = true;
    this.innerHTML = `
        ${buttonText}
        <span class="input-icon-addon">
            <div class="spinner-border spinner-border-sm text-white" role="status"></div>
        </span>
    `;
    await deleteMultipleChapters(chaptersToDelete);
    chaptersToDelete = [];
    this.disabled = false;
    this.innerHTML = `
        ${buttonText}
    `;
    btpModalMultipleChapterDestroy.hide();
}

async function deleteMultipleChapters(chapters){
    for(const key in chapters){
        await chapterDestroy(chapters[key]);
    }
}

// * CHECK IF LIST OF CHAPTERS IS ZERO
function checkChaptersList(){
    const divItemChapters = document.querySelectorAll('.comics .chapters .card-body .item');
    const divChapters = document.querySelector('.comics .chapters .card-body');
    if(divItemChapters.length <= 0){
        removeClass(divChapters, 'grid gap-4');
        divChapters.innerHTML = `
            <div class="card card-inactive">
                <div class="card-body text-center">
                    <p>No hay capítulos</p>
                </div>
            </div>
        `;
    }
}

async function generateImages(images, manga_id, chapter_id){
    // clearImages();

    let disk = document.querySelector('form.frmo-chapter input[name="disk"]:checked').value;
    let previewBox = document.querySelector('form.frmo-chapter .chapter-images');
    
    let lastItem = document.querySelector('form.frmo-chapter .chapter-images .image:last-of-type');
    let cuentaIndex = 0;
    if(lastItem){
        cuentaIndex = Number(lastItem.getAttribute('index')) + 1;
    }
    for(let i = 0; i < images.length; i++){
        let imageUrl = URL.createObjectURL(images[i]);
        let imageDiv = document.createElement('div');
        let cuentaFinal = cuentaIndex + i;
        addClass(imageDiv, 'image');
        imageDiv.setAttribute('id', 'image-'+cuentaFinal);
        imageDiv.setAttribute('index', cuentaFinal);
        imageDiv.innerHTML = `
            <div class="i-remove" data-index="${cuentaFinal}">
                <svg aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </div>
            <div class="i-drag">
                <div class="i-status">
                    <span class="i-loader"></span>
                </div>
                <img src="${imageUrl}" alt="preview" />
            </div>
        `;

        previewBox.append(imageDiv);
    }

    if(isChapterEdit){
        let res = new Promise(async (resolve, reject) => {
            const arrayImages = Object.values(images);
            let count = 0;
            let altCount = 0;
            if(lastItem){
                count = Number(lastItem.getAttribute('index')) + 1;
            }
            if(images.length > 0){
                for(const item of arrayImages){
                    console.log(item);
                    const imageStatus = document.querySelector('#image-'+ count);
                    const currentStatus = document.querySelector('#image-'+ count + ' .i-status .i-loader');
                    addClass(currentStatus, 'spin');
                    await axios.post('chapters/upload-single-image', {
                        chapter_id,
                        manga_id,
                        disk,
                        image: item
                    }, {
                        headers:{
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(function (response){
                        const data = response.data;
                        console.log(data);
                        if(data && data.status === "success"){
                            if(data.excluded.length > 0){
                                const excluded = data.excluded;
                                Toastify({
                                    text: excluded,
                                    className: "error",
                                    duration: 5000,
                                    newWindow: true,
                                    close: true,
                                    gravity: "bottom", // `top` or `bottom`
                                    position: "right", // `left`, `center` or `right`
                                }).showToast();
                            }
                            chapterImages = data.data;
                            addClass(currentStatus, 'uploaded');
                            setTimeout(() =>{
                                currentStatus.parentElement.remove();
                            }, 500)
                        }
                    })
                    .catch(function (error){
                        // handle error
                        console.log('error: ',error);
                    });
                    if(altCount === arrayImages.length -1){
                        resolve()
                    }
                    altCount++;
                    count++;
                }
            }
        });
        res.then(() => {
            previewImages = [];
            const clearInput = document.querySelector('.comics #chapter-modal .modal-body .chapter-images .add-more input#add-input');
            if(clearInput){
                clearInput.value = "";
            }
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
}

function clearImages(){
    let items = document.querySelectorAll('#chapter-modal .modal-body .chapter-images .image');
    if(items){
        items.forEach(item =>{
            item.remove();
        })
    }
}

// ? IMPORT CHAPTERS FROM FILE

const importChapterModal = document.getElementById('modal-chapter-import');
let btnImpotChapterModal = importChapterModal? new bootstrap.Modal(importChapterModal) : '';
let importFiles = [];
importChapterModal?.addEventListener('hide.bs.modal', () =>{
    const fileList = document.querySelector('form.frmo-import .file-list');
    importFiles = [];
    if(fileList){
        fileList.innerHTML = "";
    }
});
dropButton('form.frmo-import .dz-choose');
function dropButton(zone){
    const drop = document.querySelector(zone);
    let inputElement;
    if(drop){
        inputElement = drop.nextElementSibling;
        inputElement.addEventListener('change', function (e) {
            const file = this.files[0];
            const fileExists = importFiles.some(item => item.name === file.name);
            if(fileExists){
                Toastify({
                    text: "El archivo ya se encuentra agregado",
                    className: "error",
                    duration: 3000,
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    position: "center",
                }).showToast();
                return true;
            }
            importFiles.push(file);
            generatePreviewFile(file);
        })
        drop.addEventListener('click', () => inputElement.click());
        drop.addEventListener('dragover', (e) => {
            e.preventDefault();
        });
        drop.addEventListener('drop', (e) => {
            e.preventDefault();
            const file = e.dataTransfer.files[0];
            const fileExists = importFiles.some(item => item.name === file.name);
            if(fileExists){
                Toastify({
                    text: "El archivo ya se encuentra agregado",
                    className: "error",
                    duration: 3000,
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    position: "center",
                }).showToast();
                return true;
            }
            importFiles.push(file);
            generatePreviewFile(file);
        });
    }
}

const importButton = document.querySelector('#modal-chapter-import button#buttonChapterImportConfirm');
importButton?.addEventListener('click', async function(e){
    e.preventDefault();
    if(importFiles < 1){
        Toastify({
            text: "Debes seleccionar un archivo",
            className: "error",
            duration: 3000,
            newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "center", // `left`, `center` or `right`
        }).showToast();
        return true;
    }

    this.disabled = true;
    await importUploadFiles(importFiles);
    this.disabled = false;
});

async function importUploadFiles(files) {
    // const uploadPromises = files.map((file, index) => importUploadFile(file, index));
    // await Promise.all(uploadPromises);

    // for (const index in files) {
	// 	console.log('cuantas veces', files);
    //     await importUploadFile(files[index], index);
    // }

	for (const file of files) {
		const index = files.indexOf(file);
        await importUploadFile(file, index);
    }

    // * CLEAN FILES WATING ROOM
    importFiles = [];
}
function importUploadFile(file, index){
    const allowed = ['zip'];
    const extension = file.name.split('.').pop().toLowerCase();
    if(!allowed.includes(extension)){
        Toastify({
            text: "Tipo de archivo no permitido",
            className: "error",
            duration: 3000,
            newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "center", // `left`, `center` or `right`
        }).showToast();
        return true;
    }

    const completedItems = document.querySelectorAll('form.frmo-import .file-list .list-group-item.item-completed');
    index = (completedItems.length > 0)? completedItems.length : index;

    const inputComic = document.querySelector('form.frmo input[name="comic_id"]');
    let inputUpload = document.querySelector('form.frmo-import input.dz-input');
    let mangaId = inputUpload.getAttribute('data-id');
    let dataForm = new FormData();
    
    let listChapters = document.querySelector('.comics .chapters .card-body');
    let disk = document.querySelector('form.frmo-import input[name="import_disk"]:checked');

    dataForm.set('disk', disk.value);
    dataForm.append("chapters", file);

    let currentItem = document.querySelector(`#file-${index}`);
    let dotStatus = document.querySelector(`#file-${index} .badge`);
    let uploadBar = document.querySelector(`#file-${index} .progress`);
    let progressBar = document.querySelector(`#file-${index} .progress .progress-bar`);
    let buttonDelete = document.querySelector(`#file-${index} .removeItemFromList`);
    return new Promise((resolve, reject) => {
        axios.post("chapters/upload/"+inputComic.value, dataForm, {
            headers:{
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            onUploadProgress: (progressEvent) =>{
                const progress = (progressEvent.loaded / progressEvent.total) * 95;
                uploadBar.style.display = "block";
                progressBar.style.width = progress + "%";
            }
        }).then(function (response){
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
                        gravity: "top", // `top` or `bottom`
                        position: "center", // `left`, `center` or `right`
                    }).showToast();
                })
            }
            if(data['file_excluded']){
                let excluded = data['file_excluded'];
                excluded.forEach(item => {
                    Toastify({
                        text: item,
                        className: "warning",
                        duration: 5000,
                        newWindow: true,
                        close: true,
                        gravity: "top", // `top` or `bottom`
                        position: "center", // `left`, `center` or `right`
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
                    appendChapter(element);
                });
            }else{
                let errors = response.data['error'];
                if(errors){
                    errors.forEach(item => {
                        Toastify({
                            text: item.msg,
                            className: "error",
                            duration: 5000,
                            newWindow: true,
                            close: true,
                            gravity: "top", // `top` or `bottom`
                            position: "center", // `left`, `center` or `right`
                        }).showToast();
                    })
                }
            }

            inputUpload.value = "";
            currentItem.classList.add('item-completed');
            progressBar.style.width = "100%";
            progressBar.classList.add('bg-green');
            dotStatus.classList.add('bg-green');
            buttonDelete?.remove();
            resolve();
        })
        .catch(function (error){
            console.log(error);
            reject();
        });
    });
}

document.addEventListener('click', function(e){
    if (!e.target.matches('.removeItemFromList')) return;
    e.preventDefault();
    const inputDrop = document.querySelector('form.frmo-import .im-dropzone input.dz-input');
    const target = e.target;
    const targetId = target.getAttribute('data-id');
    const currentItem = document.querySelector(`#file-${targetId}`);

    inputDrop.value = "";
    importFiles.splice(targetId, 1);
    currentItem.remove();
})

function generatePreviewFile(file) {
    if(!file) return true;

    const fileList = document.querySelector('form.frmo-import .file-list')
    const existen = document.querySelector('form.frmo-import .file-list .list-group-item:last-of-type');
    let existeIndex = 0;
    if(existen){
        existeIndex = existen.getAttribute('id').split('-')[1];
    }
    const listDiv = document.createElement('div');
    listDiv.classList.add('list-group-item', 'col-12', 'px-2', 'bg-transparent');
    let idItem = (existen)? Number(existeIndex) + 1 : existeIndex;
    listDiv.setAttribute('id', 'file-'+idItem);
    listDiv.innerHTML = `
        <div class="row align-items-center">
            <div class="col-auto"><span class="badge bg-gray"></span></div>
            <div class="col-auto">
                <a href="javascript:void(0);">
                    <span class="avatar">ZIP</span>
                </a>
            </div>
            <div class="col text-truncate">
                <a href="javascript:void(0);" class="text-reset d-block">${file.name}</a>
                <div class="progress">
                    <div class="progress-bar" style="width: 0%">
                        <span class="visually-hidden">0% Complete</span>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <a href="javascript:void(0)" class="list-group-item-actions removeItemFromList" data-id="${idItem}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-secondary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                </a>
            </div>
        </div>
    `;
    fileList.appendChild(listDiv);
}