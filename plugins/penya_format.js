import Vue from 'vue'
Vue.filter('penya', function (value) {
  return Math.round(value).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' penya'
})