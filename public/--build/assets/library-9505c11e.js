import{S as P}from"./index-88830fa7.js";import{r as m,t as w}from"./helpers-cf980081.js";import"./_commonjsHelpers-de833af9.js";const o={hidePlaceholder:!1,allowEmptyOption:!1,hideSelected:!1,plugins:{clear_button:{title:"Remove all selected options"}}},f={hidePlaceholder:!0,allowEmptyOption:!1,hideSelected:!0,plugins:{remove_button:{title:"Remove this item"}}};new TomSelect("#select-type",o);new TomSelect("#select-demography",o);new TomSelect("#select-bookstatus",o);new TomSelect("#select-categories",f);new TomSelect("#select-excategories",f);const p=document.querySelectorAll(".filter__widget .widget__scl");p&&p.forEach(a=>new P(a,{autoHide:!1}));const g=document.querySelector("aside.filter form.filter__form");if(g){const a=document.querySelector(".search__bar input");g.addEventListener("submit",function(s){s.preventDefault();const t=new FormData(s.target),r=a.value,c=t.get("type"),n=t.get("demography"),l=t.get("bookstatus"),i=t.getAll("categories[]"),d=t.getAll("excategories[]");let e=new URL(document.location);r!=""?(e.searchParams.delete("s"),e.searchParams.append("s",r)):e.searchParams.delete("s"),c!=""?(e.searchParams.delete("type"),e.searchParams.append("type",c)):e.searchParams.delete("type"),n!=""?(e.searchParams.delete("demography"),e.searchParams.append("demography",n)):e.searchParams.delete("demography"),l!=""?(e.searchParams.delete("bookstatus"),e.searchParams.append("bookstatus",l)):e.searchParams.delete("bookstatus"),i.length>0?(e.searchParams.delete("categories"),e.searchParams.append("categories",i.toString())):e.searchParams.delete("categories"),d.length>0?(e.searchParams.delete("excategories"),e.searchParams.append("excategories",d.toString())):e.searchParams.delete("excategories"),window.location.href=e.href})}const h=document.querySelector("section.library button.button__filter");if(h){const a=document.querySelector("aside.filter"),s=document.querySelector("aside.filter .filter__close");window.addEventListener("click",function(){m(a,"open")}),s.addEventListener("click",function(){m(a,"open")}),a.addEventListener("click",function(t){t.stopPropagation()}),h.addEventListener("click",function(t){t.stopPropagation(),w(a,"open")})}const u=document.querySelectorAll("section.library .paginator .paginator__links a.page__link");u&&u.forEach(a=>{a.addEventListener("click",function(s){s.preventDefault();let t=new URL(s.target.href),r=new URL(window.location.href);r.searchParams.get("page"),r.searchParams.delete("page"),r.searchParams.append("page",t.searchParams.get("page")),window.location.href=r.href})});
