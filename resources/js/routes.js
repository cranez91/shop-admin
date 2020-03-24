import Login from './components/admin/LoginComponent.vue'
import User from './components/admin/UserComponent.vue'
import Product from './components/admin/ProductComponent.vue'
import Order from './components/admin/OrderComponent.vue'
import Shop from './components/ShopComponent.vue'
import ShopDetail from './components/ShopDetailComponent.vue'
import ShoppingCart from './components/ShoppingCartComponent.vue'
import Checkout from './components/CheckoutComponent.vue'

export const routes = [
    {
        path:'/login',
        component:Login,
        meta: {
          public: true,
          onlyLoggedOut: true,
          required_cart: false
        }
    },
    { 
        path:'/admin/users',
        component:User,
        meta: {
          public: false
        }
    },
    { 
        path:'/admin/products',
        component:Product,
        meta: {
          public: false
        }
    },
    { 
        path:'/admin/orders',
        component:Order,
        meta: {
          public: false
        }
    },
    { 
        path:'/',
        component:Shop,
        meta: {
          public: true,
          requiredCart: false
        }
    },
    { 
        path:'/:slug/detail',
        component:ShopDetail,
        meta: {
          public: true,
          requiredCart: false
        }
    },
    { 
        path:'/cart',
        component:ShoppingCart,
        meta: {
          public: true,
          requiredCart: false
        }
    },
    { 
        path:'/checkout',
        component:Checkout,
        meta: {
          public: true,
          requiredCart: true
        }
    }
 
];