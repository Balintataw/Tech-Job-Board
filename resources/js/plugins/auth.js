import store from '@/store'

export default {
  install(Vue) {
    Vue.mixin({
      computed: {
        "$auth"() {
          var auth = store.getters['authentication/user'];
          auth.login = (email, password) => {
            return store.dispatch('authentication/login', {
              email,
              password
            });
          }
          auth.logout = () => {
            return store.dispatch('authentication/logout');
          }
          return auth;
        }
      }
    });
  }
}