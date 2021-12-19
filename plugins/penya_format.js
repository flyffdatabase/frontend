import Vue from 'vue'
Vue.filter('penya', function (value) {
  // tanks @li-x for his simple formating function
  return Math.round(value).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' penya'
  // you can add something like .replace(/,/g, ' ') after toLocaleString method to customize your formatted number
})