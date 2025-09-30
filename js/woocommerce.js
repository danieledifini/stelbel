import Alpine from 'alpinejs';
import axios from "axios";
import 'htmx.org';
// get styles
import './../assets/css/woocommerce.css'

// get scripts
import {ajaxurl} from './../assets/js/scripts.js'

let currentPage = 1;
let isLoading = false;
let observer;


let callback = () => {
    window.Alpine = Alpine;
    Alpine.start();

    if(document.querySelector('#load-trigger')) {
    const maxPages = document.querySelector('#load-trigger').getAttribute('data-max-pages');

    observer = new IntersectionObserver(entries => {
        if (entries[0].isIntersecting && !isLoading && currentPage < maxPages) {
            loadMoreProducts(maxPages);
        }
    }, {
        root: null,
        threshold: 0.1
    });

    observer.observe(document.querySelector('#load-trigger'));
    }

    document.body.classList.toggle("loaded");
}

let loadMoreProducts = (maxPages) => {
    if (isLoading) return;
        isLoading = true;
        currentPage++;

        const data = new FormData();
        data.append('action', 'load_more_products');
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
            document.querySelector('#shop-products').insertAdjacentHTML('beforeend', response.data);

            isLoading = false;

        }
        })
        .catch(function (error) {
            console.error(error);
            isLoading = false;
        });

        if (currentPage >= maxPages) {
            observer.disconnect();
            document.querySelector('#load-trigger').remove();
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