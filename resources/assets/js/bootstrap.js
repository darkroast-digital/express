/*
| -------------------------------------------------------------------------
| #SETUP
| -------------------------------------------------------------------------
*/



// #JQUERY
// ========================================================================

try {
    window.$ = window.jQuery = require('jquery');
} catch (e) {}



// #AXIOS
// ========================================================================

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';



// #VUE
// ========================================================================

window.Vue = require('vue');

Vue.config.productionTip = false

Vue.component('column', require('./components/Column.vue'));
Vue.component('print-details', require('./components/PrintDetails.vue'));

const app = new Vue({
    el: '#app'
});
