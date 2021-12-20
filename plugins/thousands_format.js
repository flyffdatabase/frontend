import Vue from 'vue'
Vue.filter('thousands', function (value) {
  return Math.round(value).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
})