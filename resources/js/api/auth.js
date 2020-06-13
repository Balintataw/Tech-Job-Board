import axios from '@/config/axios.js'

export default {

  register(data) {
    return axios.post('auth/register', data)
  },

  registerEmployer(data) {
    return axios.post('auth/register/employer', data)
  },

  login(data) {
    return axios.post('auth/login', data)
  },

  logout() {
    return axios.get('auth/logout')
  },

  emailLink(data) {
    return axios.post('password/email', data)
  },

  resetPassword(data) {
    return axios.post('password/reset', data)
  },

}