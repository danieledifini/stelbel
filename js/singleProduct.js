import Alpine from 'alpinejs';
import { tns } from "tiny-slider"

// get styles
import './../assets/css/singleProduct.css'

// get scripts
import './../assets/js/scripts.js'  

let callback = () => {
    window.Alpine = Alpine;
    Alpine.start();

    if (document.querySelector(".woocommerce-product-gallery__wrapper") ) {
        const container = document.querySelector(".woocommerce-product-gallery__wrapper");
    
        var slider = tns({
          container: container,
          items: 1,
          gutter:15,
          slideBy: 'page',
          autoplay: false,
          controls:false,
          mouseDrag: true,
          autoplayButtonOutput: false,
          nav: false
        });
      }
}

if (
    document.readyState === "complete" ||
    (document.readyState !== "loading" && !document.documentElement.doScroll)
  ) {
    callback();
} else {
    document.addEventListener("DOMContentLoaded", callback);
}