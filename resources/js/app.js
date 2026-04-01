import './bootstrap';

import { createApp } from 'vue';
import FormBPM from './components/FormBPM.vue';
import PageCertificates from './pages/Certificates.vue';
import PageValidation from './pages/Validation.vue';

const app = createApp({});

app.component('form-bpm', FormBPM);
app.component('page-certificates', PageCertificates);
app.component('page-validation', PageValidation);

app.mount('#app');
