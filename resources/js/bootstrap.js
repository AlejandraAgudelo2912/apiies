import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.deafults.withCredentials = true;
axios.deafults.withXSRFToken = true;
