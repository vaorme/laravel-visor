// :SPLIDE

import '@splidejs/splide/css/core';
import Splide from '@splidejs/splide';

let homeSlide = new Splide('.home__slider .splide',{
    type: 'loop',
    height: 620,
    autoplay: true,
    interval: 10000,
    pauseOnHover: false,
    pauseOnFocus: false
}).mount();

let listSlide = new Splide('.list_slide .splide',{
    type: 'loop',
    perPage: 8,
    gap: '34px',
    pagination: false,
    autoplay: true,
    interval: 6000,
    pauseOnHover: false,
    pauseOnFocus: false
}).mount();