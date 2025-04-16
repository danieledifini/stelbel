import Alpine from 'alpinejs';
import 'htmx.org';
// get styles
import './../assets/css/models.css'

// get scripts
import  {goToTopList} from './../assets/js/scripts.js'
import {createStringParams,  getCheckedFromFilter} from './../assets/js/archive.js'

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
});
  
document.body.addEventListener('htmx:configRequest', function(evt) {
  isCalling = true;

  if(evt.target.classList.contains("filter-btn")){

    if(evt.target.classList.contains("active") == false)
    {
        evt.target.closest('.filter-list').querySelectorAll('button').forEach(v => v.classList.remove('active'));
        evt.target.classList.toggle("active");  
    }
  }
  

  let params = checkFilters(1, false);

  evt.detail.path += params;
  
  if(isCalling){
    document.querySelector(".items-container").classList.remove("is-not-calling");
    document.querySelector(".items-container").classList.add("is-calling");
  }
});
  
let checkFilters = (page = 0, scroll = false) => {  

  let types = [];

  if(document.getElementById('types-group') != null){
    types = getCheckedFromFilter(types,'types-group');
      return createStringParams(types);
  }
  
}

Alpine.data('models', () => ({
    selectTypesClosed:true,
    openCloseSelect(selectClass){
      if(selectClass == 'selectTypesClosed'){
        this.selectTypesClosed = !this.selectTypesClosed;
      }
    }
}))

let callback = () => {
  
  window.Alpine = Alpine;
  Alpine.start(); 
}

if (
  document.readyState === "complete" ||
  (document.readyState !== "loading" && !document.documentElement.doScroll)
) {
  callback();
} else {
  document.addEventListener("DOMContentLoaded", callback);
}