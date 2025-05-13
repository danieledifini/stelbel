import Alpine from 'alpinejs';
import 'htmx.org';
import 'fslightbox';

// get styles
import './../assets/css/register.css'

// get scripts
import  {goToTopList} from './../assets/js/scripts.js'
import {createStringParams,  getCheckedFromFilter} from './../assets/js/archive.js'

let isCalling = false;

let page  = 1;
let called = 0;

document.body.addEventListener('htmx:afterSwap', function(evt) {
  
  let target = evt.detail.target;
  
  target.classList.remove("loading");
    target.classList.add("loaded");  
  
    document.querySelector(".items-container").classList.remove("is-calling");
    document.querySelector(".items-container").classList.add("is-not-calling");
  
    called++;
    
    if(called > 1){

        goToTopList(".items-container");
        
    }
    refreshFsLightbox();
    page = 1;
  
});

document.body.addEventListener('htmx:configRequest', function(evt) {
    isCalling = true;

    if(evt.target.classList.contains("page-btn")){
        page = evt.target.getAttribute("data-page");
    }

    evt.detail.path += "/"+page;
  
    if(isCalling){
      document.querySelector(".items-container").classList.remove("is-not-calling");
      document.querySelector(".items-container").classList.add("is-calling");
    }
});

Alpine.data('register', () => ({
    
}))

let callback = () => {
    window.Alpine = Alpine;
    Alpine.start(); 
    document.body.classList.toggle("loaded");

    
}

if (
    document.readyState === "complete" ||
    (document.readyState !== "loading" && !document.documentElement.doScroll)
  ) {
    callback();
} else {
    document.addEventListener("DOMContentLoaded", callback);
}