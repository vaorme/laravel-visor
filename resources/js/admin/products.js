import { addClass, removeClass, slideDown, slideUp } from '../helpers/helpers';
import { dropZone, Modalerts } from './helpers/helpers';

let nn = new Modalerts();

let allowTypes = ['jpg', 'jpeg', 'png','webp','gif'];
dropZone('.frmo .dropzone #choose', allowTypes);

// :CREATE | UPDATE
const mangaForm = document.querySelector('.frmo form');
if(mangaForm){
    mangaForm.addEventListener('submit', function(e){
        let status = validateFields(mangaForm);
        if(!status){
            e.preventDefault();
        }
    });
    const product_type = mangaForm?.elements['product_type'];
    product_type?.addEventListener('change', function(e){
        let value = e.target.value;
        let showOption;
        if(value == 1){
            hideOptions();
            showOption = document.querySelector('#option-'+value);
            setTimeout(() => {
                slideDown(showOption);
            }, 500);
        }else if(value == 2){
            hideOptions();
            showOption = document.querySelector('#option-'+value);
            setTimeout(() => {
                slideDown(showOption);
            }, 500);
        }else{
            hideOptions();
        }
    });
}

// :DELETE

document.addEventListener('click', function (e) {
	if (!e.target.matches('.productDelete')) return;

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
            axios.delete(route('products.destroy', [id]), {
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
                    window.location.href = route('products.index');
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

function hideOptions(){
    const options = document.querySelectorAll('.hidden');
    if(options){
        options.forEach(option =>{
            slideUp(option);
        });
    }
}

function validateFields(form){
    const fields = form?.elements;
    if(fields['name'] && fields['name'].value == ""){
        fields['name'].focus();
        addClass(fields['name'], 'error');

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
    }else if(fields['name']){
        removeClass(fields['name'], 'error');
    }
    if(fields['product_type'] && fields['product_type'].value == ""){
        fields['product_type'].focus();
        addClass(fields['product_type'], 'error');

        Toastify({
            text: "Campo tipo requerido",
            className: "error",
            duration: 2000,
            newWindow: true,
            close: true,
            gravity: "top",
            position: "center",
        }).showToast();
        return false;
    }else if(fields['product_type']){
        removeClass(fields['product_type'], 'error');
    }

    return true;
}