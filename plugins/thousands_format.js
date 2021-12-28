import Vue from 'vue'
import VSwitch from 'v-switch-case'

Vue.use(VSwitch)

Vue.filter('thousands', function (value) {
  return Math.round(value).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
})