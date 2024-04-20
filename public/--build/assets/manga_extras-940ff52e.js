import{M as c}from"./helpers-27e9ade0.js";import"./helpers-cf980081.js";let r=new c;document.addEventListener("click",function(e){if(!e.target.matches(".mangaTypeDelete"))return;e.preventDefault();let n=e.target.getAttribute("data-id");r.fire({type:"warning",title:"Seguro que deseas eliminar?",text:"¡No podrás revertir esto!",confirmButtonText:"Si, eliminar",cancelButtonText:"Cancelar"}).then(t=>{t.confirmed&&axios.delete(route("manga_types.destroy",[n]),{headers:{"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content")}}).then(function(o){Toastify({text:o.data.message,className:"success",duration:1e3,newWindow:!0,close:!0,gravity:"top",position:"center"}).showToast();let a=document.querySelector(".alertify"),i=document.querySelector(".al-overlay");r.close(a,i),setTimeout(()=>{window.location.href=route("manga_types.index")},1e3)}).catch(function(o){console.log(o)})}).catch(t=>{console.log(t)})});document.addEventListener("click",function(e){if(!e.target.matches(".mangaStatusDelete"))return;e.preventDefault();let n=e.target.getAttribute("data-id");r.fire({type:"warning",title:"Seguro que deseas eliminar?",text:"¡No podrás revertir esto!",confirmButtonText:"Si, eliminar",cancelButtonText:"Cancelar"}).then(t=>{t.confirmed&&axios.delete(route("manga_book_status.destroy",[n]),{headers:{"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content")}}).then(function(o){Toastify({text:o.data.message,className:"success",duration:1e3,newWindow:!0,close:!0,gravity:"top",position:"center"}).showToast();let a=document.querySelector(".alertify"),i=document.querySelector(".al-overlay");r.close(a,i),setTimeout(()=>{window.location.href=route("manga_book_status.index")},1e3)}).catch(function(o){console.log(o)})}).catch(t=>{console.log(t)})});document.addEventListener("click",function(e){if(!e.target.matches(".mangaDemographyDelete"))return;e.preventDefault();let n=e.target.getAttribute("data-id");r.fire({type:"warning",title:"Seguro que deseas eliminar?",text:"¡No podrás revertir esto!",confirmButtonText:"Si, eliminar",cancelButtonText:"Cancelar"}).then(t=>{t.confirmed&&axios.delete(route("manga_demography.destroy",[n]),{headers:{"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content")}}).then(function(o){Toastify({text:o.data.message,className:"success",duration:1e3,newWindow:!0,close:!0,gravity:"top",position:"center"}).showToast();let a=document.querySelector(".alertify"),i=document.querySelector(".al-overlay");r.close(a,i),setTimeout(()=>{window.location.href=route("manga_demography.index")},1e3)}).catch(function(o){console.log(o)})}).catch(t=>{console.log(t)})});document.addEventListener("click",function(e){if(!e.target.matches(".categoriesDelete"))return;e.preventDefault();let n=e.target.getAttribute("data-id");r.fire({type:"warning",title:"Seguro que deseas eliminar?",text:"¡No podrás revertir esto!",confirmButtonText:"Si, eliminar",cancelButtonText:"Cancelar"}).then(t=>{t.confirmed&&axios.delete(route("categories.destroy",[n]),{headers:{"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content")}}).then(function(o){Toastify({text:o.data.message,className:"success",duration:1e3,newWindow:!0,close:!0,gravity:"top",position:"center"}).showToast();let a=document.querySelector(".alertify"),i=document.querySelector(".al-overlay");r.close(a,i),setTimeout(()=>{window.location.href=route("categories.index")},1e3)}).catch(function(o){console.log(o)})}).catch(t=>{console.log(t)})});document.addEventListener("click",function(e){if(!e.target.matches(".tagsDelete"))return;e.preventDefault();let n=e.target.getAttribute("data-id");r.fire({type:"warning",title:"Seguro que deseas eliminar?",text:"¡No podrás revertir esto!",confirmButtonText:"Si, eliminar",cancelButtonText:"Cancelar"}).then(t=>{t.confirmed&&axios.delete(route("tags.destroy",[n]),{headers:{"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content")}}).then(function(o){Toastify({text:o.data.message,className:"success",duration:1e3,newWindow:!0,close:!0,gravity:"top",position:"center"}).showToast();let a=document.querySelector(".alertify"),i=document.querySelector(".al-overlay");r.close(a,i),setTimeout(()=>{window.location.href=route("tags.index")},1e3)}).catch(function(o){console.log(o)})}).catch(t=>{console.log(t)})});
