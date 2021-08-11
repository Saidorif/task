require('./bootstrap');
window.Vue = require('vue');
// chart
import VueApexCharts from 'vue-apexcharts'
Vue.use(VueApexCharts)

import { createPopper } from '@popperjs/core';
Vue.use(createPopper)

Vue.component('apexchart', VueApexCharts)

// VTooltip
import VTooltip from 'v-tooltip'
Vue.use(VTooltip)

// ProgressBar
import VueProgressBar from 'vue-progressbar';

Vue.use(VueProgressBar,{
  color: 'rgb(143,255,199)',
  failedColor:'red',
  height:'3px'
});

Vue.config.devtools = false

import VCalendar from 'v-calendar';
Vue.use(VCalendar, {componentPrefix: 'vc'});

/* FOR MANAGING USER PERMISSIONS */
import {abilitiesPlugin} from '@casl/vue'
import {ability} from "./store/store";
Vue.use(abilitiesPlugin, ability);
/* FOR MANAGING USER PERMISSIONS */
// datepicker
import 'vue2-datepicker/scss/index.scss'

// Global function
import {g} from './globalfunc.js'
Vue.prototype.$g = g

//Fire For Listers
window.Fire = new Vue();

// Router
import router from './routes'


//Vuex
import store from "./store/store"

// Vue pagination
Vue.component('pagination', require('laravel-vue-pagination'));

// Sweetalert2
import swal from 'sweetalert2';
window.swal = swal;

import CyrillicToTranslit from 'cyrillic-to-translit-js/CyrillicToTranslit';
window.cril = CyrillicToTranslit;



const toast = swal.mixin({
  toast: true,
  position: 'top',
  showConfirmButton: false,
  timer: 3000
});
window.toast = toast;

import Master from './components/layouts/Master'


// Some services
import {TokenService} from './services/storage.service'
import ApiService from './services/api.service'


// SET THE BASE_URL OF THE API
ApiService.init(process.env.VUE_APP_ROOT_API);
ApiService.mount401Interceptor();

// IF TOKEN EXISTS SET HEADER
if (TokenService.getToken()) {
  ApiService.setHeader();
  ApiService.mount401Interceptor();
}
Vue.filter('truncate', function (value, size) {
    if (!value) return '';
    value = value.toString();

    if (value.length <= size) {
      return value;
    }
    return value.substr(0, size) + '...';
  });

const app = new Vue({
    el: '#app',
    router,
    components:{
      Master
    },
  store,
});
