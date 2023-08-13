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
const categorySettings = {
    hidePlaceholder: true,
    allowEmptyOption: false,
    hideSelected: true,
    plugins: {
        remove_button:{
			title:'Remove this item',
		}
	},
};
const selectType = new TomSelect('#select-type',settings);
const selectDemography = new TomSelect('#select-demography',settings);
const selectBookStatus = new TomSelect('#select-bookstatus',settings);
const selectCategories = new TomSelect('#select-categories',categorySettings);
const selectExCategories = new TomSelect('#select-excategories',categorySettings);

const widgetScl = document.querySelectorAll('.filter__widget .widget__scl');
if(widgetScl){
    widgetScl.forEach(el => new SimpleBar(el, {
        autoHide: false
    }));
}
const formFilter = document.querySelector('aside.filter form.filter__form');
if(formFilter){
    const searchInput = document.querySelector('.search__bar input')
    formFilter.addEventListener('submit', function(e){
        e.preventDefault();
        const data = new FormData(e.target);
        const s = searchInput.value;
        const type = data.get('type');
        const demography = data.get('demography');
        const bookstatus = data.get('bookstatus');
        const categories = data.getAll('categories[]');
        const excategories = data.getAll('excategories[]');

        let validated = false;
        let newUrl = new URL(document.location);
        if(s != ""){
            newUrl.searchParams.delete("s");
            newUrl.searchParams.append("s", s);
        }else{
            newUrl.searchParams.delete("s");
        }
        if(type != ""){
            newUrl.searchParams.delete("type");
            newUrl.searchParams.append("type", type);
        }else{
            newUrl.searchParams.delete("type");
        }
        if(demography != ""){
            newUrl.searchParams.delete("demography");
            newUrl.searchParams.append("demography", demography);
        }else{
            newUrl.searchParams.delete("demography");
        }
        if(bookstatus != ""){
            newUrl.searchParams.delete("bookstatus");
            newUrl.searchParams.append("bookstatus", bookstatus);
        }else{
            newUrl.searchParams.delete("bookstatus");
        }
        if(categories.length > 0){
            newUrl.searchParams.delete("categories");
            newUrl.searchParams.append("categories", categories.toString());
        }else{
            newUrl.searchParams.delete("categories");
        }
        if(excategories.length > 0){
            newUrl.searchParams.delete("excategories");
            newUrl.searchParams.append("excategories", excategories.toString());
        }else{
            newUrl.searchParams.delete("excategories");
        }

        window.location.href = newUrl.href;
    });
}

const buttonFilter = document.querySelector('section.library button.button__filter');
if(buttonFilter){
    const asideFilter = document.querySelector('aside.filter');
    const asideButtonClose = document.querySelector('aside.filter .filter__close');
    window.addEventListener('click', function(){
        asideFilter.removeClass('open');
    })
    asideButtonClose.addEventListener('click', function(){
        asideFilter.removeClass('open');
    })
    asideFilter.addEventListener('click', function(e){
        e.stopPropagation();
    })
    buttonFilter.addEventListener('click', function(e){
        e.stopPropagation();
        asideFilter.toggleClass('open');
    });
}
const paginationLinks = document.querySelectorAll('section.library .paginator .paginator__links a.page__link');
if(paginationLinks){
    paginationLinks.forEach(item => {
        item.addEventListener('click', function(e){
            e.preventDefault();
            let currentUrl = new URL(e.target.href);
            let newUrl = new URL(window.location.href);
            let getPage = newUrl.searchParams.get('page');
            newUrl.searchParams.delete('page');
            newUrl.searchParams.append('page', currentUrl.searchParams.get('page'));
            window.location.href = newUrl.href;
        });
    });
}

// :COLLPASE FILTER
const titlesWidget = document.querySelectorAll('aside.filter form.filter__form .filter__widget.filter__categories h2.widget__title');
if(titlesWidget){
    titlesWidget.forEach(item =>{
        item.addEventListener('click', function(){
            slideToggle(item.nextElementSibling)
        });
    });
}