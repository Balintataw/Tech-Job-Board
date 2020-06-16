import axios from 'axios'
import { vm } from '../app.js'

let csrftoken = document.head.querySelector('meta[name="csrf-token"]')

const instance = axios.create({
  // baseURL: '/api/v1/',
  // headers: {
  //   'Content-Type': 'application/json',
  //   'X-Requested-With': 'XMLHttpRequest',
  //   'X-CSRF-TOKEN': csrftoken.content
  // }
})

function getCookieValue(a) {
  var b = document.cookie.match('(^|;)\\s*' + a + '\\s*=\\s*([^;]+)');
  return b ? b.pop() : '';
}

instance.interceptors.request.use(
  config => {
    config.baseURL = '/api/v1/';
    config.headers = {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': csrftoken.content
    }
    // define access token
    let token = undefined;
    // check for user token in localstorage
    try {
      token = getCookieValue('Token');
    } catch (error) { }
    console.log("COOKIE", token)
    if (token) {
      console.log("SETTING TOKEN", token)
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  error => {
    return Promise.reject(error);
  }
);

// Add a response interceptor
instance.interceptors.response.use(
  response => {
    return response;
  },
  error => {

    if (error.response.status === 401) {

      vm.$router.push({ name: 'login' });

    } else if (error.response.status === 422) {

      if (error.response.data.errors) {
        let messages = []
        for (let key in error.response.data.errors) {
          messages.push(error.response.data.errors[key])
        }
        return Promise.reject({
          status: 422,
          messages: messages
        });
      }

    } else if (error.response.status === 403) {
      if (error.response.data.message === 'Unauthorized Route') {
        vm.$router.back();
        // vm.$toasted.error(error.response.data.message);
      }
    } else {

      console.error(error)
    }

    return Promise.reject(error);
  })

export default instance