import '../bootstrap';

// :ALPINE

import Alpine from 'alpinejs';

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

// :WYSIWYG
import EditorJS from '@editorjs/editorjs'; 
import header from "@editorjs/header"
import embed from "@editorjs/embed"
import list from "@editorjs/list"
import paragraph from "@editorjs/paragraph"
import table from "@editorjs/table"
import underline from "@editorjs/underline"
import checklist from "@editorjs/checklist"
import code from "@editorjs/code"
import inlineCode from "@editorjs/inline-code"
import link from "@editorjs/link"
import marker from "@editorjs/marker"
import personality from "@editorjs/personality"
import quote from "@editorjs/quote"
import raw from "@editorjs/raw"
import simpleImage from "@editorjs/simple-image"
import warning from "@editorjs/warning"
import ColorPlugin from "editorjs-text-color-plugin"

window.Alpine = Alpine;
window.Choices = Choices;
window.AirDatepicker = AirDatepicker;
window.localeEs = localeEs;
window.Toastify = Toastify;
window.Draggable = Draggable;
window.DgSortable = Sortable;
window.EditorJS = EditorJS;
window.EditorJsHeader = header;
window.EditorJsEmbed = embed;
window.EditorJsList = list;
window.EditorJsParagraph = paragraph;
window.EditorJsTable = table;
window.EditorJsUnderline = underline;
window.EditorJsChecklist = checklist;
window.EditorJsCode = code;
window.EditorJsInlineCode = inlineCode;
window.EditorJsLink = link;
window.EditorJsMarker = marker;
window.EditorJsPersonality = personality;
window.EditorJsQuote = quote;
window.EditorJsRaw = raw;
window.EditorJsSimpleImage = simpleImage;
window.EditorJsWarning = warning;
window.EditorJsColor = ColorPlugin;

Alpine.start();