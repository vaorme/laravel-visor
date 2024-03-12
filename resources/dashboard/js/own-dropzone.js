import { hasClass, addClass, removeClass } from "./own-helpers";

function ownDropZone(zone, allowed){
    const elements = document.querySelectorAll(zone);
    elements?.forEach(drop => {
        let inputElement, img;
        if(drop){
            inputElement = drop.nextElementSibling;
            Object.values(drop.children).forEach(val => {
                if(hasClass(val, 'dz-preview')){
                    img = val.children[0];
                }    
            });

            inputElement.addEventListener('change', function (e) {
                const file = this.files[0];
                dropFile(file, img, allowed);
            })
            drop.addEventListener('click', () => inputElement.click());
            drop.addEventListener('dragover', (e) => {
                e.preventDefault();
            });
            drop.addEventListener('drop', (e) => {
                e.preventDefault();
                const file = e.dataTransfer.files[0];
                inputElement.files = e.dataTransfer.files;
                dropFile(file, img, allowed);
            });
        }
    })
}
function dropFile(file, preview, allowed){
    if(!file) {
        return false;
    }
    const extension = file.name.split('.').pop().toLowerCase();
    if(!allowed.includes(extension)){
		Toastify({
			text: "Tipo de archivo no permitido.",
			className: "error",
			duration: 5000,
			newWindow: true,
			close: true,
			gravity: "top",
			position: "center",
		}).showToast();
        return false;
    }
    removeClass(preview, 'show');
    removeClass(preview.nextElementSibling, 'visually-hidden')
    setTimeout(() => {
        addClass(preview, 'show');
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onloadend = function () {
            const result = reader.result;
            let src = this.result;
            preview.src = src;
            preview.alt = file.name
        }
    }, 200);
}

export default ownDropZone;