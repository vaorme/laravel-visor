import Swiper from 'swiper';
import { Navigation, Pagination, EffectFade, Autoplay } from 'swiper/modules';

import SimpleBar from "simplebar";

import { removeClass, addClass, hasClass } from '../helpers/helpers';

// :NEW CHAPTERS SIMPLEBAR
const newChaptersList = document.querySelector('.new_manga .new__chapters');
if(newChaptersList){
    document.addEventListener('DOMContentLoaded', function(){
        new SimpleBar(newChaptersList, {
            autoHide: false
        });
    });
}

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

const viewMoreTopMonth = document.querySelector('aside.main__sidebar .section.tops button.tops__viewmore');
if(viewMoreTopMonth){
    const topList = document.querySelector('aside.main__sidebar .section.tops ul.tops__list');
    viewMoreTopMonth.addEventListener('click', function(){
        if(hasClass(topList, 'more')){
            removeClass(topList, 'more');
        }else{
            addClass(topList, 'more');
        }
    });
}