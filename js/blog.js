import Alpine from 'alpinejs';

// get styles
import './../assets/css/blog.css'

// get scripts
import './../assets/js/scripts.js'

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