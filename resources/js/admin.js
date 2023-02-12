import './library';
import './helper/helpers'
import {DataTable} from "simple-datatables"

const dataTable = new DataTable("#tablr", {
    perPage: 15,
    labels: {
        placeholder: "Buscar...",
        searchTitle: "Buscar dentro de la tabla",
        perPage: "Elementos por página",
        noRows: "No hay elementos",
        info: "Mostrando {start} a {end} de {rows} elementos",
        noResults: "Ningún resultado coincide con su consulta de búsqueda",
    }
});


const sltr = {
    menuItem: document.querySelectorAll('aside.aside ul.m-ul li.m-item')
}

document.addEventListener('DOMContentLoaded', function(){

});