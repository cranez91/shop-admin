require('./bootstrap');

window.Vue = require('vue');

//Import Vue Filter
require('./filter'); 

//Import progressbar
require('./progressbar'); 

//Setup custom events 
require('./customEvents'); 

//Import View Router
import VueRouter from 'vue-router'
Vue.use(VueRouter)

//Import Vuetify
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)

const Vuex = require('vuex');
import  createPersistedState  from  'vuex-persistedstate'

window.store = new Vuex.Store({
  state: {
      productsCount: 0,
      cartId: null,
      laravelToken: null
  },
  mutations: {
    decrement(state){
      return state.productsCount--  
    },
    increment(state){
      return state.productsCount++  
    },
    setCount(state, value){
      return state.productsCount = value
    },
    setCart(state, value){
      return state.cartId = value
    },
    setToken(state, value){
      return state.laravelToken = value
    }
  },
  plugins: [createPersistedState()]
});

//Import Sweetalert2
import Swal from 'sweetalert2'
window.Swal = Swal
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})
window.Toast = Toast

//Import v-from
import { Form, HasError, AlertError } from 'vform'
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

//Routes
import { routes } from './routes';

//Register Routes
const router = new VueRouter({
    routes,
    mode: 'history'
})

router.beforeEach((to, from, next) => {
  //const authenticated = store.state.userToken
  const hasCart = window.store.state.cartId ?? null;
  const hasCartItems = window.store.state.productsCount ?? 0;
  const authenticated = window.store.state.laravelToken ?? null;
  const onlyLoggedOut = to.matched.some(record => record.meta.onlyLoggedOut)
  const requiredCart = to.matched.some(record => record.meta.requiredCart)
  const isPublic = to.matched.some(record => record.meta.public)
  if (!isPublic && !authenticated) {
    // this route requires auth, check if logged in
    // if not, redirect to login page.
    return next({
      path: '/login',
      query: { redirect: to.fullPath }
    })
  }
  if (requiredCart && (!hasCart || !hasCartItems)) {
    return next('/')
  }
  if (authenticated && onlyLoggedOut) {
    return next('/')
  }
  next()
})

Vue.component('side-bar', require('./components/SideBarComponent.vue').default); 
Vue.component('admin-side-bar', require('./components/admin/AdminSideBarComponent.vue').default); 

const app = new Vue({
    vuetify: new Vuetify(),
    el: '#app',
    router
});