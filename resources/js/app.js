require('./bootstrap');
window.Vue = require('vue');
// ProgressBar
import VueProgressBar from 'vue-progressbar';

Vue.use(VueProgressBar,{
  color: 'rgb(143,255,199)',
  failedColor:'red',
  height:'3px'
});

Vue.config.devtools = false


/* FOR MANAGING USER PERMISSIONS */
import {abilitiesPlugin} from '@casl/vue'
import {ability} from "./store/store";
Vue.use(abilitiesPlugin, ability);
/* FOR MANAGING USER PERMISSIONS */




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

const app = new Vue({
    el: '#app',
    router,
    components:{
      Master
    },
  store,
});
