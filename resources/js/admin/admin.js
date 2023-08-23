import './library';

// :SIMPLE BAR
import 'simplebar';

// :DATATABLE
import { DataTable } from "simple-datatables";

// :USERS
import './users'

// :PERMISSIONS
import './permissions'

// :ROLES
import './roles';

import { removeClass, addClass } from '../helpers/helpers';

let tabla = document.querySelector('.table');
if(tabla){
    const dataTable = new DataTable("#tablr", {
        paging: false,
        searchable: false,
        ordering:  false,
        labels: {
            placeholder: "Buscar...",
            searchTitle: "Buscar dentro de la tabla",
            perPage: "Elementos por página",
            noRows: "No hay elementos",
            info: "Mostrando {start} a {end} de {rows} elementos",
            noResults: "Ningún resultado coincide con su consulta de búsqueda",
        }
    });
}

// Template 1 Validation Form

const templateOneForm = document.querySelector('.template-1 form.form:not(.editing)');
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
        }else if(fieldName){
            removeClass(fieldName, 'error');
        }
        if(fieldSlug && fieldSlug.value == ""){
            e.preventDefault();
            
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
        }else if(fieldSlug){
            removeClass(fieldSlug, 'error');
        }
    });
}