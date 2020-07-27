require('./bootstrap');

import Vue from 'vue';
import { BootstrapVue } from 'bootstrap-vue';

Vue.use(BootstrapVue);

// Formulario BPM
Vue.component('form-bpm', require('./components/FormBPM').default);

new Vue({
  el: '#app'
});