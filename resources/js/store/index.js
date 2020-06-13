import Vue from 'vue';
import Vuex from 'vuex';
import VuexPersistence from 'vuex-persist';

import { authentication } from './auth.module';

Vue.use(Vuex);

export default new Vuex.Store({
  plugins: [
    new VuexPersistence({
      storage: window.localStorage,
      modules: [
        'authentication',
      ],
    }).plugin,
  ],
  modules: {
    authentication,
  },
});