import { Modalerts } from './helpers/helpers';

let nn = new Modalerts();

// :DELETE PERMISSION

document.addEventListener('click', function (e) {
	if (!e.target.matches('.roleDelete')) return;

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
            axios.delete(route('roles.destroy', [id]), {
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
                    window.location.href = route('roles.index');
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

let botonAdministarPermisos = document.querySelector('#administrarPermisos');
let botonCerrarAdministrarPermisos = document.querySelector('#cerrarAdministrarPermisos');
let mdAdministrarPermisos = document.querySelector('.group.permisos .md-permissions');
if(botonAdministarPermisos){
	botonAdministarPermisos.addEventListener('click', function(){
		if(mdAdministrarPermisos){
			mdAdministrarPermisos.style.display = "block";
		}
	});
}
if(botonCerrarAdministrarPermisos){
	botonCerrarAdministrarPermisos.addEventListener('click', function(){
		if(mdAdministrarPermisos){
			mdAdministrarPermisos.style.display = "none";
		}
	});
}