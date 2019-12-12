
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');



window.Vue = require('vue');




/*Vue.prototype.authorize  = function(handler) {
    //assidional admin privilages
    let user = window.App.user;

    return user ? handler(user) :  false
};*/

let authorizations = require('./authorizations');

Vue.prototype.authorize  = function(...params) {
    //assidional admin privilages
    let user = window.App.user;

    if(! window.App.signedIn) return  false;

    if(typeof params[0] === 'string')  {
       return authorizations[params[0]](params[1]);
    }


    return params[0](user);
};


Vue.prototype.signedIn = window.App.signedIn;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('alert', require('./components/Alert.vue'));
Vue.component('thread-view', require('./pages/Thread.vue'));
Vue.component('paginator', require('./components/paginator'));
Vue.component('user-notifications', require('./components/UserNotifications'));
Vue.component('avatar-form', require('./components/AvatarForm'));
Vue.component('scan', require('./components/Scan'));



import InstantSearch from 'vue-instantsearch';

Vue.use(InstantSearch);


const app = new Vue({
    el: '#app'
});
