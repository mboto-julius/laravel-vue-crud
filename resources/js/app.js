import './bootstrap';

import { createApp } from 'vue';
import App from './components/App.vue';
import router from './router';
import Swal from 'sweetalert2';


window.Swal = Swal
const toast = Swal.mixin({
    toast:true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
})
window.toast = toast


createApp(App)
    .use(router)
    .mount("#app")
