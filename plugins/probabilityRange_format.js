import Vue from 'vue'
Vue.filter('probabilityRange', function (value) {
  if (value.endsWith('[') && value.startsWith('['))
    return value.replace('[', '').replace('[', '').replace(';', ' - ');

  return value;
})