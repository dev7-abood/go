/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

import Vue from 'vue'
import  App from  './components/Viewer.vue'

import VueProgressBar from 'vue-progressbar'
import router from './router'

Vue.use(VueProgressBar , {
    color: 'rgb(0,255,26)',
    failedColor: 'red',
    height: '2px',
    transition: {
        speed: '.5s',
        opacity: '0.2s',
        termination: 100
    },
});

Vue.prototype.$isadmin = document.querySelector("meta[name='isadmin']").getAttribute('content');
if(document.querySelector("meta[name='exturl']"))
Vue.prototype.baseurl = document.querySelector("meta[name='url']").getAttribute('content');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

new Vue({
    router,
    render:h=>h(App)
}).$mount('#app');
