import Auth from "@/api/auth";
import { router } from '@/app.js';

const user = JSON.parse(localStorage.getItem('user'));
let initialState = user
  ? { status: { loggedIn: true }, user }
  : { status: { loggedIn: false }, user: null };

export const authentication = {
  namespaced: true,
  state: initialState,
  getters: {
    user: (state) => {
      return state;
    },
  },
  mutations: {
    loginRequest(state) {
      state.status = null;
      state.user = null;
    },
    loginSuccess(state, user) {
      state.status = { loggedIn: true };
      state.user = user;
    },
    loginFailure(state) {
      state.status = { loggedIn: false };
      state.user = null;
    },
    logout(state) {
      state.status = { loggedIn: false };
      state.user = null;
    }
  },
  actions: {
    async login({ commit }, { email, password }) {
      console.log(email, password)
      commit('loginRequest');
      try {
        const { data } = await Auth.login({
          email,
          password
        });
        console.log("LOGIN resp", data);
        const expires = "expires=" + data.token.original.expires_in;
        window.localStorage.setItem('Token', data.token.original.access_token);
        window.localStorage.setItem('expires', data.token.original.expires_in);
        document.cookie =
          "Token=" +
          data.token.original.access_token +
          ";" +
          expires +
          ";path=/";

        commit('loginSuccess', data.user);
        router.replace(data.redirect);
      } catch (error) {
        const msg = error.messages[0];
        this.$toasted.error(msg);
        console.log("Login error:", error);
      }
      // return firebase.auth().signInWithEmailAndPassword(employeeEmail, password)
      //     .then(user => {
      //         const newUser = {
      //             email: user.user.email,
      //             verified: user.user.emailVerified,
      //             id: user.user.uid,
      //         }
      //         window.localStorage.setItem('token', newUser.id)
      //         commit('loginSuccess', newUser);
      //         return newUser;
      //     })
      //     .catch(error => {
      //         commit('loginFailure', error);
      //         return { e: error, status: 500 };
      //     })
      // return api.login(employeeEmail, password)
      //     .then(user => {
      //         commit('loginSuccess', user.data);
      //         return user.data;
      //     },
      //     error => {
      //         commit('loginFailure', error);
      //         dispatch('alert/error', error, { root: true });
      //     }
      // );
    },
    async logout({ commit }) {

      console.log("logout called");
      await Auth.logout();
      window.localStorage.clear();
      document.cookie = "Token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
      commit('logout');
      router.replace('/');
    },
  },
}