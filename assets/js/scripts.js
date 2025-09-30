import 'lazysizes';
import axios from 'axios';


export let ajaxurl = '/wp-admin/admin-ajax.php';
let ticking = false;

export let calling = false;
export let header;
let lastLogoWidth = null;

// INIT CALLBACK

let callback = () => {

  setVw();

  if(document.querySelector('header.header')){
    header = document.querySelector('header.header');
  } 

  const logo = document.querySelector(".navbar-brand");
  const contentWrapper = document.querySelector(".content-wrapper");

  const minWidth = 243;
  const scrollLimit = 100;

  let wrapperWidth, widthDiff;
  let lastScrollY = window.scrollY;
  
  setTimeout(() => {
    wrapperWidth = contentWrapper.offsetWidth;
    widthDiff = wrapperWidth - minWidth;
  }, 100);

  let ticking = false;

  if (window.innerWidth >= 992) {
    window.addEventListener('scroll', function () {
      const scrollY = window.scrollY;
      const scrollingUp = scrollY < lastScrollY;

      if (!ticking) {
        window.requestAnimationFrame(() => {
          if (wrapperWidth && widthDiff) {
            
            const scrollY = Math.min(Math.max(window.scrollY, 0), scrollLimit);
            let progress = Math.min(scrollY, scrollLimit) / scrollLimit;;

            const currentWidth = Math.round(wrapperWidth - widthDiff * progress);
            logo.style.width = currentWidth + "px";
          }
          ticking = false;
        });
        ticking = true;
      }
      lastScrollY = scrollY;
    });
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