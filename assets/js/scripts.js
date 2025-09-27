import 'lazysizes';
import axios from 'axios';


export let ajaxurl = '/wp-admin/admin-ajax.php';
let ticking = false;

export let calling = false;
export let header;

// INIT CALLBACK

let callback = () => {

  setVw();

  if(document.querySelector('header.header')){
    header = document.querySelector('header.header');
  } 

  const logo = document.querySelector(".navbar-brand");
const upperHeader = document.querySelector(".upper-header");
const contentWrapper = document.querySelector(".content-wrapper");

const minWidth = 243;         // logo width at 150px scroll
const scrollLimit = 100;      // scroll distance for scaling

// Read CSS variable value (strip 'px')
const rootStyles = getComputedStyle(document.documentElement);
const standardMargin = parseFloat(rootStyles.getPropertyValue("--standardMargin"));

let lastScrollY = window.scrollY;

  window.addEventListener('resize', function() {
    setVw();
    updateLogo(window.scrollY);
  });  

  window.addEventListener('scroll', function() {
 
    const scrollY = window.scrollY;
  
    if (Math.abs(scrollY - lastScrollY) > 2) {
      lastScrollY = scrollY;

      if (!ticking) {
        window.requestAnimationFrame(() => {
          updateLogo(scrollY);
          ticking = false;
        });
        ticking = true;
      }
    }
  });  

  function updateLogo(scrollY) {
    const wrapperWidth = contentWrapper.offsetWidth; 
    const progress = Math.min(scrollY, scrollLimit) / scrollLimit;
  
    const currentWidth = Math.round(wrapperWidth - (wrapperWidth - minWidth) * progress);
    logo.style.width = currentWidth + "px";
    
  
    /*const currentPadding = standardMargin - (standardMargin / 2) * progress;
    upperHeader.style.paddingBlock = currentPadding + "px";*/
  } 

}




/* GENERAL */

let setVw = () => {
  let vw = document.documentElement.clientWidth / 100;
  document.documentElement.style.setProperty('--vw', `${vw}px`);
  let vh = window.innerHeight / 100;
  document.documentElement.style.setProperty('--vh', `${vh}px`);  

  let headerH = document.querySelector('.pre-nav-header').clientHeight;
  document.documentElement.style.setProperty('--headerH', `${headerH}px`);  
}

export let setCalling = (newValue = false) => {
  calling = newValue;
}



export let goToTopList = (target) => {
  let $target = document.querySelector(target);
  let top = $target.offsetTop ;
  let duration = 500;
  scrollTo(document.documentElement, top, duration);  
}

export let scrollTo = (element, to, duration) => {
  
  var start = element.scrollTop,
      change = to - start,
      currentTime = 0,
      increment = 20;
      
  var animateScroll = function(){     
      
      currentTime += increment;
      var val = Math.easeInOutQuad(currentTime, start, change, duration);

      element.scrollTop = val;

      if(currentTime < duration) {
          setTimeout(animateScroll, increment);
      }
      else{
          setTimeout(() => {
          }, 200);
      }
  };

  animateScroll();
}

Math.easeInOutQuad = function (t, b, c, d) {
  t /= d/2;
  if (t < 1) return c/2*t*t + b;
  t--;
  return -c/2 * (t*(t-2) - 1) + b;
};

let onScroll = () => {
  //No IE8
 requestTick();
}

let requestTick = () => {

  if(!ticking) {
      requestAnimationFrame(updateScroll);
  }

  ticking = true;
}

let updateScroll = () => {
  ticking = false;
}

export let convertRemToPixels = (rem) => {    
  return rem * parseFloat(getComputedStyle(document.documentElement).fontSize);
}

export let getGutter = () => {
  return convertRemToPixels(parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--grid-gutter')));
}

export let getWidth = () => {
  if (self.innerWidth) {
    return self.innerWidth;
  }

  if (document.documentElement && document.documentElement.clientWidth) {
    return document.documentElement.clientWidth;
  }

  if (document.body) {
    return document.body.clientWidth;
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