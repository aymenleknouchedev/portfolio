// Wire CSRF for axios requests issued outside Inertia (e.g., TinyMCE uploads).
import axios from 'axios';

const token = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content;
if (token) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
  axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
}

(window as any).axios = axios;
