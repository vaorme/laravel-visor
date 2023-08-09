import '../library';
import Swiper from 'swiper';
import { Navigation, Pagination, EffectFade, Autoplay } from 'swiper/modules';

// :SLIDES
const swiper = new Swiper('.home__swiper', {
    modules: [Navigation, Pagination, EffectFade, Autoplay],
    loop: false,
    speed: 500,
    autoplay: {
        delay: 5000,
    },
    effect: 'fade',
    fadeEffect: {
        crossFade: false     // added(resolve the overlapping of the slides)
    },
    pagination: {
        clickable: true,
        el: '.swiper-pagination',
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    }
});

document.addEventListener('DOMContentLoaded', function(){
    
});