import '../library';
import { addClass, removeClass, toggleClass } from '../helpers/helpers';

const urlOrigin = window.location.origin;
const buyForm = document.querySelector('form.buy-chapter-form');

document.addEventListener('DOMContentLoaded', () => {
    buyForm?.addEventListener('submit', async function(e){
        e.preventDefault();
        const chapterId = buyForm?.elements['chapter_id'].value;
        await axios.post(urlOrigin + "/users/buy-chapter", {
            headers:{
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            params: {
                chapter_id: chapterId
            }
        }).then(function(response){
            console.log(response);
            if(response.data.status == "success"){
                Toastify({
                    text: response.data.message,
                    className: "success",
                    duration: 5000,
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    position: "center",
                }).showToast();
                setTimeout(() => {
                    if(response.data.url && response.data.url != ""){
                        window.location.href = response.data.url;
                    }else{
                        location.reload();
                    }
                }, 1000);
            }
            if(response.data.status == "error"){
                Toastify({
                    text: response.data.message,
                    className: "error",
                    duration: 5000,
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    position: "center",
                }).showToast();
            }
        })
        .catch(function (error){
            console.log('error: ',error);
        });
    });

    // :PREMIUM CHAPTER
    const buttonPremium = document.querySelectorAll('.chapter__premium.buy');
    buttonPremium?.forEach(button => {
        button.addEventListener('click', function(e){
            const chapterId = button.getAttribute('data-id');
            const price = button.getAttribute('data-price');
            const title = document.querySelector('.chapter__buy h4');

            if(chapterId == ""){
                Toastify({
                    text: "ID requerido",
                    className: "error",
                    duration: 5000,
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    position: "center",
                }).showToast();
                return true;
            }else if(price == ""){
                Toastify({
                    text: "Precio requerido",
                    className: "error",
                    duration: 5000,
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    position: "center",
                }).showToast();
                return true;
            }
            buyForm?.elements['chapter_id'].setAttribute('value', chapterId);
            let chapterName = e.target.parentElement.parentElement.children[0].children[1].textContent;
            title.textContent = `${chapterName} x ${price} Monedas`;
            openModalBuyChapter();
        });
    });
    const overlay = document.querySelector('.overlay');
    overlay?.addEventListener('click', function(){
        closeModalBuyChapter();
    });
    const closeButton = document.querySelector('.modal .modal__head button');
    closeButton?.addEventListener('click', function(){
        closeModalBuyChapter();
    });
});

function openModalBuyChapter(){
    const modal = document.querySelector('.modal');
    const overlay = document.querySelector('.overlay');
    if(modal){
        if(overlay){
            addClass(overlay, 'show');
        }
        addClass(modal, 'show');
    }
}
function closeModalBuyChapter(){
    const modal = document.querySelector('.modal');
    const overlay = document.querySelector('.overlay');
    if(modal){
        if(overlay){
            removeClass(overlay, 'show');
        }
        removeClass(modal, 'show');
        buyForm?.elements['chapter_id'].setAttribute('value', '');
    }
}

// :BURGER MENu
const burger = document.querySelector('header.header nav.navbar .burger');
if(burger){
    const menu = document.querySelector('header.header nav.navbar .menu');
    window.addEventListener('click', function(){
        removeClass(burger, 'active');
        removeClass(menu, 'open');
        removeOverlay();
    });
    burger.addEventListener('click', function(e){
        e.stopPropagation();
        toggleClass(burger, 'active');
        toggleClass(menu, 'open');
        createOverlay();
    });
}
function createOverlay(){
    const bdy = document.querySelector('body');
    const ovl = document.createElement('div');
    addClass(ovl, 'mnu__overlay');

    bdy.append(ovl);
}
function removeOverlay(){
    const ovl = document.querySelector('.mnu__overlay');
    if(ovl){
        ovl.remove();
    }
}