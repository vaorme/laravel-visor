import { removeClass, addClass } from "../helpers/helpers";

const readerContent = document.querySelector('#viewer .view__reader .view__content');
const settings = {
    maxOptions: null,
    render: {
        option: function (data, escape) {
            return `<div data-url="${data.url}">${data.text}</div>`;
        },
        item: function (item, escape) {
            return `<div data-url="${item.url}">${item.text}</div>`;
        }
    }
};

const chapterLists = [
    document.querySelector('#slct_chapter_list'),
    document.querySelector('#slct_chapter_list_bottom')
];
chapterLists.forEach(item =>{
    if(item){
        const chapterList = new TomSelect(item, settings);
        chapterList.on('change', function(value){
            if(value == ""){
                return true;
            }

            const selected = chapterList.getOption(value)

            const url = selected.getAttribute('data-url');

            window.location.href = url;
        });
    }
});


const readerTypes = [
    document.querySelector('#slct_reader_type'),
    document.querySelector('#slct_reader_type_bottom')
];
readerTypes.forEach(item =>{
    if(item){
        const toms = new TomSelect(item, settings);
        toms.on('change', function(value){
            if(value == ""){
                return true;
            }
        
            const selected = toms.getOption(value)
        
            const url = selected.getAttribute('data-url');
        
            window.location.href = url;
        });
    }
});

// :READER SIZE

const readerSizes = [
    document.querySelector('#slct_reader_size'),
    document.querySelector('#slct_reader_size_bottom')
];
let readerToms = [];
readerSizes.forEach(item =>{
    if(item){
        const toms = new TomSelect(item);
        const valueReaderSie = sessionStorage.getItem('reader_size');

        readerToms.push(toms);

        if(toms && valueReaderSie == "full"){
            toms.setValue(1);
            addClass(readerContent, 'view__full');
        }else{
            toms.setValue(2);
            removeClass(readerContent, 'view__full');
        }
        toms.on('change', function(value){
            if(readerToms.length > 0){
                readerToms.forEach(el =>{
                    el.input.value = value;
                    el.sync();
                })
            }
            switch (value) {
                case "1":
                    sessionStorage.setItem('reader_size', 'full');
                    addClass(readerContent, 'view__full');
                    break;
                case "2":
                    sessionStorage.setItem('reader_size', 'deafult');
                    removeClass(readerContent, 'view__full');
                    break;
                default:
                    break;
            }
        });
        toms.clearCache();
    }
});


// :FONT SIZE

const readerFontSizes = [
    document.querySelector('#slct_font_size'),
    document.querySelector('#slct_font_size_bottom')
];
let fontSizeToms = [];
readerFontSizes.forEach(item =>{
    if(item){
        const toms = new TomSelect(item);
        fontSizeToms.push(toms);
        const valueFontSize = sessionStorage.getItem('reader_font_size');
        if(valueFontSize){
            toms.setValue(valueFontSize);
            readerContent.style.fontSize = valueFontSize + "px";
        }
        toms.on('change', function(value){
            if(fontSizeToms.length > 0){
                fontSizeToms.forEach(el =>{
                    el.input.value = value;
                    el.sync();
                })
            }
            sessionStorage.setItem('reader_font_size', value);
            readerContent.style.fontSize = value + "px";
        });
    }
});

// :FONT COLOR

const divColors = document.querySelector('.view__colors');
if(divColors){
    const contentFontColor = localStorage.getItem('content_font_color');
    if(contentFontColor){
        const fontColors = [
            document.querySelector('#choose_color'),
            document.querySelector('#choose_color_bottom')
        ];
        fontColors.forEach(item =>{
            if(item){
                item.value = contentFontColor;
            }
            readerContent.style.color = contentFontColor;
        })
    }
    
    const contentBodyColor = localStorage.getItem('content_body_color');
    if(contentBodyColor){
        const fontColors = [
            document.querySelector('#choose_background'),
            document.querySelector('#choose_background_bottom')
        ];
        fontColors.forEach(item =>{
            if(item){
                item.value = contentBodyColor;
            }
            readerContent.style.backgroundColor = contentBodyColor;
        })
    }
}

// :PAGED LIST

const pagedLists = [
    document.querySelector('#slct_paged_list'),
    document.querySelector('#slct_paged_list_bottom')
];
pagedLists.forEach(item =>{
    if(item){
        const pagedList = new TomSelect(item, settings);
        pagedList.on('change', function(value){
            if(value == ""){
                return true;
            }
        
            const selected = pagedList.getOption(value)
        
            const url = selected.getAttribute('data-url');
        
            window.location.href = url;
        });
    }
});

// :PAGED NEXT

const divPaged = document.querySelector('.view__paged'); 
if(divPaged){
    const readerItem = document.querySelector('.reader__item');
    if(readerItem){
        readerItem.addEventListener('click', function(){
            if(divPaged.children[2].classList.contains('paged__next')){
                divPaged.children[2].click();
            }
        });
    }
}