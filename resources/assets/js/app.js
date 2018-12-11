require('./bootstrap');

window.Vue = require('vue');

Vue.component('notification', require('./components/Notification.vue'));

Vue.prototype.url = app.url;

new Vue({
    el: '#notification'
});
