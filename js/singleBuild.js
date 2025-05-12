import Alpine from 'alpinejs';
import { tns } from "tiny-slider"
// get styles
import './../assets/css/singleBuild.css'

// get scripts
import {getWidth,getGutter} from './../assets/js/scripts.js'

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