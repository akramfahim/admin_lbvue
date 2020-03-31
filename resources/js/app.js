/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

//Moment 
import moment from 'moment'

//Vform
import { Form, HasError, AlertError } from 'vform'

//Sweet Alert
import Swal from 'sweetalert2'
window.Swal = Swal 

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    // timerProgressBar: true,
    // onOpen: (toast) => {
    //   toast.addEventListener('mouseenter', Swal.stopTimer)
    //   toast.addEventListener('mouseleave', Swal.resumeTimer)
    // }
})

window.Toast = Toast

//Vue-router
import VueRouter from 'vue-router'


window.Form = Form 
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

Vue.use(VueRouter)

//Progress Bar
import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
  color: 'rgb(143, 255, 199)',
  failedColor: 'red',
  height: '5px'
})

//import Example from './components/ExampleComponent.vue'
import Dashboard from './components/Dashboard.vue'
import Profile from './components/Profile.vue'
import Users from './components/Users.vue'
import Developer from './components/Developer.vue'
import NotFound from './components/NotFound.vue'


const routes = [
    // { 
    //     path: '/home',
    //     name: 'Example', 
    //     component: Example
    // },
    { 
        path: '/home', 
        name : 'Dashboard',
        component: Dashboard
    },
    { 
        path: '/profile',
        name: 'Profile', 
        component: Profile
    },
    { 
        path: '/users',
        name: 'Users', 
        component: Users
    },
    {
        path: '/developer',
        name: 'Developer',
        component: Developer
    },
    {
        path: '*',
        name: 'NotFound',
        component: NotFound
    }
    
]
  
  // 3. Create the router instance and pass the `routes` option
  // You can pass in additional options here, but let's
  // keep it simple for now.
const router = new VueRouter({
    mode:'history',
    routes // short for `routes: routes`
})


//Vue.component('Dashboard', require('./components/Dashboard.vue').default);

//Global Function which can be accessed from everywhere
//Vue Filter 
Vue.filter('upText', function(text){
    return text.charAt(0).toUpperCase() + text.slice(1)
});

Vue.filter('myDate', function(date){
    return moment(date).format('MMMM Do YYYY, h:mm a');//moment().startOf('hour').fromNow();
});


//Global Custom Event
let Fire = new Vue();
window.Fire = Fire;


//Laravel Passport Vue
Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);
//404 Not Found Page
Vue.component(
    'not-found',
    require('./components/NotFound.vue').default
);


//Laravel Vue Pagination
Vue.component('pagination', require('laravel-vue-pagination'));


import Gate from './Gate'
Vue.prototype.$gate = new Gate(window.user);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    data:{
        search : ''
    },
    methods:{
        searchit: _.debounce(() => {
            Fire.$emit('searching');
        },1000)
    }
});
