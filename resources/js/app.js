/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("es6-promise/auto");
require("./bootstrap");

const axios = require("axios");
const Vue = require("vue");
const VueAuth = require("@websanova/vue-auth");
const VueAxios = require("vue-axios");
const VueRouter = require("vue-router").default;
const Index = require("./Index").default;
const auth = require("./auth");
const router = require("./router");

// Set Vue globally
window.Vue = Vue;

// Set Vue router
Vue.router = router;

Vue.use(VueRouter);

// Set Vue authentication
Vue.use(VueAxios, axios);

axios.defaults.baseURL = `${process.env.MIX_APP_URL}/api`;

Vue.use(VueAuth, auth);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

/**
 * Components
 */
Vue.component("index", Index);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const app = new Vue({
  el: "#app",
  router
});
