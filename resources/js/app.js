import Vue from 'vue';
import VueRouter from 'vue-router';
import Toasted from 'vue-toasted';

import App from '@/App.vue';
import { routes } from '@/routes';

import store from '@/store';
import AuthPlugin from '@/plugins/auth.js';

// you can also pass options, check options reference below
import { dateFormatTimeAgo, dateFormat } from './filters';
require('./bootstrap');

Vue.use(VueRouter);
Vue.use(Toasted, {
    position: 'bottom-right',
    duration: 3000
});
Vue.filter('formatDateTimeAgo', dateFormatTimeAgo);
Vue.filter('formatDate', dateFormat);
Vue.use(AuthPlugin);


export const router = new VueRouter({
    mode: 'history',
    routes: routes,
});

export const vm = new Vue({
    el: '#app',
    router,
    store,
    components: { App }
});