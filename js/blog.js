import Alpine from 'alpinejs';
import 'htmx.org';
import axios from "axios";

// get styles
import './../assets/css/blog.css'

// get scripts
import  {ajaxurl,goToTopList} from './../assets/js/scripts.js'
import {createStringParams} from './../assets/js/archive.js'

let page = 1;
let isCalling = false;

let currentPage = 1;
let isLoading = false;
let observer;

let loadTrigger;


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
    else{
        checkLoadMore();
    }

    page = 1;
});
  
document.body.addEventListener('htmx:configRequest', function(evt) {
    isCalling = true;

    if(evt.target.classList.contains("page-btn")){
        page = evt.target.getAttribute("data-page");
    }
    else
    if(evt.target.classList.contains("filter-btn")){

        if(evt.target.classList.contains("active") == false)
        {
            evt.target.closest('.filter-list').querySelectorAll('button').forEach(v => v.classList.remove('active'));
            evt.target.classList.toggle("active");  
            
        }

        page = 1;
    }

    evt.detail.path += "/"+page;
  
    if(isCalling){
      document.querySelector(".items-container").classList.remove("is-not-calling");
      document.querySelector(".items-container").classList.add("is-calling");
    }
});

let callback = () => {
    window.Alpine = Alpine;
    Alpine.start(); 
    document.body.classList.toggle("loaded");
}

let checkLoadMore = () => {

    if (document.querySelector('#load-trigger') === null) 
        return;

    const loadTrigger = document.querySelector('#load-trigger');
    const maxPages = loadTrigger.getAttribute('data-max-pages');

    observer = new IntersectionObserver(entries => {
        if (entries[0].isIntersecting && !isLoading && currentPage < maxPages) {
            observer.unobserve(loadTrigger);
            loadTrigger.remove();
            //loadTrigger = null;
        
            loadMoreArticles(maxPages);
        }
    }, {
        root: null,
        threshold: 0.1
    });

    observer.observe(loadTrigger);
}    

let loadMoreArticles = (maxPages) => {
    if (isLoading) return;
        isLoading = true;
        currentPage++;

        const data = new FormData();
        data.append('action', 'load_more_articles');
        data.append('page', currentPage);

        axios
        .post(ajaxurl, data)
        .then(function (response) {
        if (response.status >= 200 && response.status < 300) {
            return Promise.resolve(response);
        } else {
            return Promise.reject(new Error("Failed to load"));
        }
        })
        .then((response) => {
        
        if (response.data) {
            document.querySelector('#items-wrapper').insertAdjacentHTML('beforeend', response.data);

            isLoading = false;

            checkLoadMore();
        }
        })
        .catch(function (error) {
            console.error(error);
            isLoading = false;
        });

        if (currentPage >= maxPages) {
            observer.disconnect();

            if (loadTrigger) {
                loadTrigger.remove();
            }
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