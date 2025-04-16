import Alpine from 'alpinejs';
import 'htmx.org';
// get styles
import './../assets/css/homepage.css'

// get scripts
import  './../assets/js/scripts.js'


document.body.addEventListener('htmx:afterSwap', function(evt) {
  
  let target = evt.detail.target;
  
  target.classList.remove("loading");
  target.classList.add("loaded");
  
});

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