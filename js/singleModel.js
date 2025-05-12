import Alpine from 'alpinejs';
import 'htmx.org';
import { tns } from "tiny-slider"
// get styles
import './../assets/css/singleModel.css'

// get scripts
import  './../assets/js/scripts.js'


document.body.addEventListener('htmx:afterSwap', function(evt) {
  
  let target = evt.detail.target;
  
  target.classList.remove("loading");
  target.classList.add("loaded");

  addSlider(target);
  
});

let addSlider = (target) => {
  var slider = tns({
    container: target,
    items: 1,
    gutter:15,
    slideBy: 'page',
    autoplay: true,
    controls:false,
    mouseDrag: true,
    autoplayButtonOutput: false,
    nav: false,
    responsive: {
    480: {
      items: 2,
    },
    768: {
      items: 3,
    },
    992: {
      disable: true 
    }
  }});
}



let callback = () => {
  
  window.Alpine = Alpine;
  Alpine.start(); 

  if (document.querySelector(".article-body  .img-slider") ) {
    const container = document.querySelector(".article-body  .img-slider .inner-img-slider");

    var slider = tns({
      container: container,
      items: 1,
      gutter:15,
      slideBy: 'page',
      autoplay: true,
      controls:false,
      mouseDrag: true,
      autoplayButtonOutput: false,
      nav: false,
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