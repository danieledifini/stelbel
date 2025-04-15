import Alpine from 'alpinejs';

// get styles
import './../assets/css/singleProduct.css'

// get scripts
import './../assets/js/scripts.js'


document.addEventListener('alpine:init', () => {
  Alpine.data('singleProduct', () => ({

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