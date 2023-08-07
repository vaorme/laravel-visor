import { dropZone, Modalerts } from './helpers/helpers';

let nn = new Modalerts();

let allowTypes = ['jpg', 'jpeg', 'png','webp','gif'];
dropZone('.slider .frmo .logo .dropzone #choose', allowTypes);
dropZone('.slider .frmo .background .dropzone #choose', allowTypes);
const settings = {
    placeholder: "Buscar",
    hidePlaceholder: false,
    allowEmptyOption: false,
    hideSelected: true,
    render: {
        option: function (data, escape) {
            if(data.src != ""){
                return `<div data-url="${data.url}"><img class="me-2" src="${data.src}">${data.text}</div>`;
            }
            return `<div data-url="${data.url}">${data.text}</div>`;
        },
        item: function (item, escape) {
            if(item.src != ""){
                return `<div data-url="${item.url}"><img class="me-2" src="${item.src}">${item.text}</div>`;
            }
            return `<div data-url="${item.url}">${item.text}</div>`;
        }
    }
};
const tom = new TomSelect('#tom-select-it',settings);

const templateOneForm = document.querySelector('.template-1 form.form');
if(templateOneForm){
    let ctRegex = /[^a-zA-Z0-9]+/g;
    let fieldName = document.querySelector('.template-1 form input[name="name"]');
    let fieldSlug = document.querySelector('.template-1 form input[name="slug"]');
    fieldName.addEventListener('input', function(e){
        let str = e.target.value;
        fieldSlug.value = str.replace(ctRegex, '-').toLowerCase();
    });
	if(fieldSlug){
		fieldSlug.addEventListener('input', function(e){
			let str = e.target.value;
			fieldSlug.value = str.replace(ctRegex, '-').toLowerCase();
		});
	}
    templateOneForm.addEventListener('submit', function(e){
        fieldName = document.querySelector('.template-1 form input[name="name"]');
        fieldSlug = document.querySelector('.template-1 form input[name="slug"]');
        if(fieldName && fieldName.value == ""){
            e.preventDefault();
            
            fieldName.focus();
            fieldName.addClass('error');
    
            Toastify({
                text: "Campo nombre requerido",
                className: "error",
                duration: 2000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "center",
            }).showToast();
        }else if(fieldName){
            fieldName.removeClass('error');
        }
        if(fieldSlug && fieldSlug.value == ""){
            e.preventDefault();
            
            fieldSlug.focus();
            fieldSlug.addClass('error');
    
            Toastify({
                text: "Campo slug requerido",
                className: "error",
                duration: 2000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "center",
            }).showToast();
        }else if(fieldSlug){
            fieldSlug.removeClass('error');
        }
    });
}

// :DELETE CATEGORIES

document.addEventListener('click', function (e) {
	if (!e.target.matches('.elementDelete')) return;

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
            axios.delete(route('slider.destroy', [id]), {
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
                    window.location.href = route('slider.index');
                }, 1000);
            })
            .catch(function (error){
                // handle error
                console.log(error);
                Toastify({
                    text: error.response.data.message,
                    className: "error",
                    duration: 4000,
                    newWindow: true,
                    close: true,
                    gravity: "top", // `top` or `bottom`
                    position: "center", // `left`, `center` or `right`
                }).showToast();
            });
        }
    }).catch(err => {
        console.log(err);
    })

});