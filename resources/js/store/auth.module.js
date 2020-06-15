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
          username: email,
          password
        });
        console.log("LOGIN resp", data);
        const expires = "expires=" + data.body.expires_in;
        window.localStorage.setItem('Token', data.body.access_token);
        window.localStorage.setItem('expires', data.body.expires_in);
        document.cookie =
          "Token=" +
          data.body.access_token +
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
    },
    async register({ commit }, { email, password, name, user_type }) {
      console.log(email, password, name, user_type);
      commit('loginRequest');
      try {
        const { data } = await Auth.register({
          email,
          password,
          name,
          user_type
        });
        console.log("Register resp", data);

        return data;
      } catch (error) {
        const msg = error.messages[0];
        this.$toasted.error(msg);
        console.log("Register error:", error);
      }
    },
    async logout({ commit }) {
      try {
        await Auth.logout();
        window.localStorage.clear();
        document.cookie = "Token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        commit('logout');
        router.replace('/');
      } catch (error) {
        console.log("Logout failed", error)
      }
    },
  },
}