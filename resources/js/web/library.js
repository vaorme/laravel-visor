import SimpleBar from "simplebar";

const settings = {
    hidePlaceholder: false,
    allowEmptyOption: false,
    hideSelected: false,
    plugins: {
		'clear_button':{
			'title':'Remove all selected options',
		}
	},
};
const selectType = new TomSelect('#select-type',settings);
const selectDemography = new TomSelect('#select-demography',settings);
const selectBookStatus = new TomSelect('#select-bookstatus',settings);

const widgetScl = document.querySelectorAll('.filter__widget .widget__scl');
if(widgetScl){
    widgetScl.forEach(el => new SimpleBar(el));
}