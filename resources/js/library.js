import './bootstrap';

// :ALPINE

import Alpine from 'alpinejs';

// :AXIOS

import axios from 'axios';

// :CHOICES

import Choices from 'choices.js';

// :AIRDATEPICKER

import localeEs from 'air-datepicker/locale/es';
import AirDatepicker from 'air-datepicker';
import 'air-datepicker/air-datepicker.css';

// :TOASTIFY

import Toastify from 'toastify-js'
import "toastify-js/src/toastify.css";

// :DRAGGABLE
import { Draggable, Sortable } from '@shopify/draggable';

window.Alpine = Alpine;
window.Choices = Choices;
window.AirDatepicker = AirDatepicker;
window.localeEs = localeEs;
window.Toastify = Toastify;
window.Draggable = Draggable;
window.DgSortable = Sortable;

Alpine.start();