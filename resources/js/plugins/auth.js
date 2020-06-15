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
          auth.register = (email, password, name, user_type) => {
            return store.dispatch('authentication/register', {
              email,
              password,
              name,
              user_type
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