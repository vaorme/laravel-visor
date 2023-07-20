const readerContent = document.querySelector('#viewer .view__reader .view__content');

const settings = {
    render: {
        option: function (data, escape) {
            return `<div data-url="${data.url}">${data.text}</div>`;
        },
        item: function (item, escape) {
            return `<div data-url="${item.url}">${item.text}</div>`;
        }
    }
};
const chapterList = new TomSelect('#slct_chapter_list', settings);
chapterList.on('change', function(value){
    if(value == ""){
        return true;
    }

    const selected = chapterList.getOption(value)

    const url = selected.getAttribute('data-url');

    window.location.href = url;
});

const selectReaderType = document.querySelector('#slct_reader_type');
if(selectReaderType){
    const readerType = new TomSelect(selectReaderType, settings);
    readerType.on('change', function(value){
        if(value == ""){
            return true;
        }
    
        const selected = readerType.getOption(value)
    
        const url = selected.getAttribute('data-url');
    
        window.location.href = url;
    });
}

// :READER SIZE

const readerSize = new TomSelect('#slct_reader_size');
const valueReaderSie = sessionStorage.getItem('reader_size');
if(valueReaderSie && valueReaderSie == "full"){
    readerSize.setValue(1);
    readerContent.addClass('view__full');
}else{
    readerSize.setValue(2);
    readerContent.removeClass('view__full');
}
readerSize.on('change', function(value){
    switch (value) {
        case "1":
            sessionStorage.setItem('reader_size', 'full');
            readerContent.addClass('view__full');
            break;
        case "2":
            sessionStorage.setItem('reader_size', 'deafult');
            readerContent.removeClass('view__full');
            break;
        default:
            break;
    }
});

// :FONT SIZE

const selectReaderFontSize = document.querySelector('#slct_font_size');
if(selectReaderFontSize){
    const readerFontSize = new TomSelect(selectReaderFontSize);
    const valueFontSize = sessionStorage.getItem('reader_font_size');
    if(valueFontSize){
        readerFontSize.setValue(valueFontSize);
        readerContent.style.fontSize = valueFontSize + "px";
    }
    readerFontSize.on('change', function(value){
        sessionStorage.setItem('reader_font_size', value);
        readerContent.style.fontSize = value + "px";
    });
}

// :FONT COLOR

const divColors = document.querySelector('.view__colors');
if(divColors){
    const contentFontColor = localStorage.getItem('content_font_color');
    if(contentFontColor){
        const inputFontColor = document.querySelector('#choose_color');
        if(inputFontColor){
            inputFontColor.value = contentFontColor;
        }
        readerContent.style.color = contentFontColor;
    }
    
    const contentBodyColor = localStorage.getItem('content_body_color');
    if(contentBodyColor){
        const inputBackgroundColor = document.querySelector('#choose_background');
        if(inputBackgroundColor){
            inputBackgroundColor.value = contentBodyColor;
        }
        readerContent.style.backgroundColor = contentBodyColor;
    }
}

// :PAGED LIST

const selectPagedList = document.querySelector('#slct_paged_list');
if(selectPagedList){
    const pagedList = new TomSelect(selectPagedList, settings);
    pagedList.on('change', function(value){
        if(value == ""){
            return true;
        }
    
        const selected = pagedList.getOption(value)
    
        const url = selected.getAttribute('data-url');
    
        window.location.href = url;
    });
}

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