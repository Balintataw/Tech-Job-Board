import axios from '@/config/axios.js'

const responseBody = response => {
  return response.data;
};

export default {

  get(url) {
    return axios.get(url)
  },

  post(url, data, headers) {
    return axios.post(url, data, headers)
  },

  put(url, data) {
    return axios.put(url, data)
  },

  delete(url, data) {
    return axios.delete(url, data)
  },

  postWithProgress: (url, file, cb) => {
    return axios
      .post(url, file, {
        onUploadProgress: progressEvent => {
          const totalLength = progressEvent.lengthComputable
            ? progressEvent.total
            : progressEvent.target.getResponseHeader("content-length") ||
            progressEvent.target.getResponseHeader(
              "x-decompressed-content-length"
            );
          if (totalLength !== null) {
            cb && cb(Math.round((progressEvent.loaded * 100) / totalLength));
          }
        }
      })
    // .then(responseBody);
  }

}