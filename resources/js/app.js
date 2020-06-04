/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import "es6-promise/auto"
import "../css/lux.min.css"
import "vue-multiselect/dist/vue-multiselect.min.css"

import axios from "axios"
import Vue from "vue"
import VueAuth from "@websanova/vue-auth"
import VueAxios from "vue-axios"
import VueRouter from "vue-router"
import VueApexCharts from 'vue-apexcharts'
import VueNotifications from 'vue-notification'
import VueMultiselect from 'vue-multiselect'
import Index from "./Index"
import auth from "./auth"
import router from "./router"
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

// Install BootstrapVue
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)
Vue.use(VueNotifications)

// Set Vue globally
window.Vue = Vue;

// Set Vue router
Vue.router = router;

Vue.use(VueRouter);

// Set Vue authentication
Vue.use(VueAxios, axios);

axios.defaults.baseURL = `${process.env.MIX_APP_URL}/api`;

Vue.use(VueAuth, auth);

Vue.component('apexchart', VueApexCharts)
Vue.component('multiselect', VueMultiselect)

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
new Vue({
  el: "#app",
  router
});
