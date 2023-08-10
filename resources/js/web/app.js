import '../library';
import Swiper from 'swiper';
import { Navigation, Pagination, EffectFade, Autoplay } from 'swiper/modules';

// :MOVIL SLIDE SHORTCUTS
const movilShortcutSlider = document.querySelector('.main__shortcuts .shortcuts__list');
if(movilShortcutSlider){
    movilShortcuts();
    window.addEventListener('resize', function(){
        movilShortcuts();
    })
    const swiper = new Swiper('.sgsssa', {
        modules: [Navigation, Pagination, EffectFade, Autoplay],
        loop: false,
        speed: 500,
        autoplay: {
            delay: 5000,
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: false     // added(resolve the overlapping of the slides)
        }
    });
}

function movilShortcuts(){
    const windowWidth = window.innerWidth;
    if(windowWidth <= 1024){
        console.log('entro: ', windowWidth);
        
    }
}

document.addEventListener('DOMContentLoaded', function(){
    
});