import{a as S}from"./axios-4a70c6fc.js";import{i as C}from"./helpers-403e1058.js";import"./helpers-33874dd9.js";const i=e=>{Toastify({text:e,className:"error",duration:5e3,newWindow:!0,close:!0,gravity:"top",position:"center"}).showToast()},p=document.querySelectorAll(".form__avatares .list .item:not(#userAvatar) input");p&&p.forEach(e=>{e.addEventListener("change",t=>{const a=document.querySelector(".form__avatares .list .item#avatar");a&&a.removeClass("selected")})});document.addEventListener("click",function(e){if(!e.target.matches(".form__avatares .list .item#avatar"))return;e.preventDefault();const t=document.querySelector(".form__avatares .list .item input:checked");t&&(t.checked=!1),e.target.addClass("selected")});const _=async e=>{let t=new FormData,a;return t.append("avatar",e),await S.post("/users/validate-avatar",t,{headers:{"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content")}}).then(function(r){const{data:n,data:{status:o,msg:l}}=r;if(o==="error"){for(let v of l)i(v);return a=!1,!1}a=!0}).catch(function(r){console.log("error: ",r)}),a},k=function(e){const t=document.querySelector(e);let a;t&&(a=t.nextElementSibling,a.addEventListener("change",function(r){const n=r.target.files,o={...n};_(n[0]).then(l=>{l&&h(o)}),a.value=""}),t.addEventListener("click",()=>a.click()),t.addEventListener("dragover",r=>{r.preventDefault()}),t.addEventListener("drop",r=>{r.preventDefault();const n=r.dataTransfer.files,o={...n};_(n[0]).then(l=>{l&&h(o)}),a.value=""}))};k("#userAvatar #choose");function h(e){b();const t=document.querySelector(".form__avatares .list"),a=URL.createObjectURL(e[0]),r=document.createElement("div"),n=document.querySelector(".form__avatares .list .item input:checked");n&&(n.checked=!1),r.addClass("item selected"),r.setAttribute("id","avatar"),r.innerHTML=`
        <div class="avatar">
            <img src="${a}" alt="avatar">
			<input type="file" name="avatar_file" accept="image/jpg,image/png,image/jpeg,image/gif" hidden>
        </div>
    `,t.append(r);const o=new DataTransfer,l=document.querySelector(".form__avatares #avatar input");o.items.add(e[0]),l.files=o.files}function b(){const e=document.querySelector(".form__avatares #avatar");e&&e.remove()}const f=document.querySelector(".account__form .form__cover input");f&&f.addEventListener("input",E);async function E(e){const t=document.querySelector(".account__form .form__cover .preview img"),a=e.target.value;if(t.removeClass("show"),!C(a))return!1;t.src=a,t.onload=l=>{},t.onerror=l=>{i("Imagen invalida o enlace roto")};const n=await(l=>new Promise((v,y)=>{const m=new Image;m.onload=()=>v(m),m.onerror=q=>y(q),m.src=l}))(a);let o=!0;if(n.naturalWidth>1920&&(i("El ancho maximo es 1920"),f.setAttribute("data-validated",!1),o=!1),n.naturalHeight>548&&(i("El alto maximo es 548"),f.setAttribute("data-validated",!1),o=!1),!o)return!1;t.addClass("show"),f.setAttribute("data-validated",!0)}const d={list:document.querySelector(".account__form .form__redes .list"),listInputs:document.querySelectorAll(".account__form .form__redes .list .item:not(.add) input"),addInput:document.querySelector(".account__form .form__redes .list .add input")};d.listInputs&&d.listInputs.forEach(e=>{e.addEventListener("input",()=>{e.value===""?(i("Campo requerido"),e.focus(),e.addClass("error")):e.removeClass("error")})});document.addEventListener("click",function(e){if(!e.target.matches(".account__form .form__redes .list .add .new"))return;e.preventDefault();let t=d.addInput.value,a=Date.now();if(t==="")return i("Campo requerido"),d.addInput.focus(),d.addInput.addClass("error"),!1;if(d.addInput.removeClass("error"),C(t))d.addInput.removeClass("error");else return i("Debes insertar un enlace valido"),d.addInput.focus(),d.addInput.addClass("error"),!1;let r=document.createElement("div");r.addClass("item"),r.setAttribute("id",`i-${a}`),r.innerHTML=`
		<div class="icon">
			<svg width="28px" height="28px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<circle cx="12" cy="12" r="8" stroke="#fff" stroke-width="2"/>
				<path d="M18.572 7.20637C17.8483 8.05353 16.8869 8.74862 15.7672 9.23422C14.6475 9.71983 13.4017 9.98201 12.1326 9.99911C10.8636 10.0162 9.60778 9.78773 8.4689 9.33256C7.33002 8.87739 6.34077 8.20858 5.58288 7.38139" stroke="#fff" stroke-width="2"/>
				<path d="M18.572 16.7936C17.8483 15.9465 16.8869 15.2514 15.7672 14.7658C14.6475 14.2802 13.4017 14.018 12.1326 14.0009C10.8636 13.9838 9.60778 14.2123 8.4689 14.6674C7.33002 15.1226 6.34077 15.7914 5.58288 16.6186" stroke="#fff" stroke-width="2"/>
				<path d="M12 4V20" stroke="#fff" stroke-width="2"/>
			</svg>
		</div>
		<input type="text" name="redes[]" value="${t}">
		<div class="action">
			<button class="delete" data-id="i-${a}">
				<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
					<path stroke="none" d="M0 0h24v24H0z" fill="none"/>
					<line x1="4" y1="7" x2="20" y2="7" />
					<line x1="10" y1="11" x2="10" y2="17" />
					<line x1="14" y1="11" x2="14" y2="17" />
					<path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
					<path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
				</svg>
			</button>
		</div>
	`,d.list.append(r),d.addInput.value=""});document.addEventListener("click",function(e){if(!e.target.matches(".account__form .form__redes .list .item .delete"))return;e.preventDefault();let t=e.target.getAttribute("data-id");document.querySelector(`#${t}`).remove()});const w=document.querySelector(".account__change__password form");w&&w.addEventListener("submit",async function(e){I()});function I(){let e=document.querySelector(".account__form .form__cover input");return e.value!=""&&e.dataset.validated==="false"&&(e.value=""),!0}const g=document.querySelector(".account__change__password form");g&&g.addEventListener("submit",async function(e){if(!x())return e.preventDefault(),!1});function x(){let e=document.querySelector('.account__change__password form .form__item input[name="current_password"]'),t=document.querySelector('.account__change__password form .form__item input[name="password"]'),a=document.querySelector('.account__change__password form .form__item input[name="password_confirmation"]');return e&&e.value==""?(i("Contraseña actual es requerida"),e.focus(),!1):t&&t.value==""?(i("Contraseña nueva es requerida"),t.focus(),!1):!c&&!c?(i("Contraseña invalida"),!1):a&&a.value==""?(i("Confirmar nueva contraseña es requerido"),a.focus(),!1):t.value!=a.value?(i("Las contraseñas deben coincidir"),a.focus(),!1):!0}let u=document.querySelector('.account__change__password form .form__item input[name="password"]');const s={letter:document.querySelector(".password__validation #letter"),capital:document.querySelector(".password__validation #capital"),number:document.querySelector(".password__validation #number"),length:document.querySelector(".password__validation #length")};let c=!1;u&&u.addEventListener("keyup",function(){let e=/[a-z]/g;u.value.match(e)?(s.letter.removeClass("invalid"),s.letter.addClass("valid"),c=!0):(s.letter.removeClass("valid"),s.letter.addClass("invalid"),c=!1);let t=/[A-Z]/g;u.value.match(t)?(s.capital.removeClass("invalid"),s.capital.addClass("valid"),c=!0):(s.capital.removeClass("valid"),s.capital.addClass("invalid"),c=!1);let a=/[0-9]/g;u.value.match(a)?(s.number.removeClass("invalid"),s.number.addClass("valid"),c=!0):(s.number.removeClass("valid"),s.number.addClass("invalid"),c=!1),u.value.length>=8?(s.length.removeClass("invalid"),s.length.addClass("valid"),c=!0):(s.length.removeClass("valid"),s.length.addClass("invalid"),c=!1)});
