// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import store from './vuex/store'
import BootstrapVue from 'bootstrap-vue'
import axios from 'axios'

axios.defaults.baseURL = process.env.NODE_ENV === 'production' ? 'http://54.180.160.194' : 'http://localhost:8888'
Vue.prototype.$http = axios

Vue.use(
  BootstrapVue,
  axios
)

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  components: { App },
  template: '<App/>'
})
