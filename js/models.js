import Alpine from 'alpinejs';
import 'htmx.org';
// get styles
import './../assets/css/models.css'

// get scripts
import {getWidth,getGutter} from './../assets/js/scripts.js'


document.body.addEventListener('htmx:afterSwap', function(evt) {
  
  let target = evt.detail.target;
  
  target.classList.remove("loading");
  target.classList.add("loaded");
  
});

document.addEventListener('alpine:init', () => {
  Alpine.data('homepage', () => ({
      
  }))    
})

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