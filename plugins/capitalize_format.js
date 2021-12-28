import Vue from 'vue'
Vue.filter('capitalize', function (string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
})