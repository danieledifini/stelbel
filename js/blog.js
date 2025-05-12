import Alpine from 'alpinejs';
import 'htmx.org';

// get styles
import './../assets/css/blog.css'

// get scripts
import  {goToTopList} from './../assets/js/scripts.js'
import {createStringParams} from './../assets/js/archive.js'

let page = 1;
let isCalling = false;


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

    page = 1;
});
  
document.body.addEventListener('htmx:configRequest', function(evt) {
    isCalling = true;

    if(evt.target.classList.contains("page-btn")){
        page = evt.target.getAttribute("data-page");
    }
    else
    if(evt.target.classList.contains("filter-btn")){

        if(evt.target.classList.contains("active") == false)
        {
            evt.target.closest('.filter-list').querySelectorAll('button').forEach(v => v.classList.remove('active'));
            evt.target.classList.toggle("active");  
            
        }

        page = 1;
    }

    evt.detail.path += "/"+page;
  
    if(isCalling){
      document.querySelector(".items-container").classList.remove("is-not-calling");
      document.querySelector(".items-container").classList.add("is-calling");
    }
});

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