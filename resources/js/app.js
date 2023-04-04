require('./bootstrap');

import Vue from 'vue';
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue';

Vue.use(BootstrapVue);
Vue.use(BootstrapVueIcons)

// Formulario BPM
Vue.component('form-bpm', require('./components/FormBPM').default);

// Pages
Vue.component('page-certificates', require('./pages/Certificates').default);
Vue.component('page-validation', require('./pages/Validation').default);

new Vue({
    el: '#app'
});
