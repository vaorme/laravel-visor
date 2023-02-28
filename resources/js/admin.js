import './library';

// :SIMPLE BAR
import 'simplebar';
import 'simplebar/dist/simplebar.css';

// :DATATABLE
import { DataTable } from "simple-datatables";

// :MANGA
import './manga'

let tabla = document.querySelector('.table');
if(tabla){
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
}