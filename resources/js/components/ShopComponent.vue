<template>
  <div>
    <side-bar :count="in_cart"></side-bar>
    <v-row no-gutters>
      
      <v-card
        v-for="product in products" :key="product.id"
        :loading="loading"
        class="mx-auto my-12"
        colm="3"
        max-width="374"

      >
        <v-img
          height="250"
          :src="'/img/products/'+product.image"
        ></v-img>

        <v-card-title class="deep-purple--text text--lighten-1" >{{ product.name }}</v-card-title>

        <v-card-text>
          <div class="my-2 subtitle-1">
            <strong>${{ product.price }}</strong>
          </div>
          <div>{{ product.description }}</div>
        </v-card-text>
        <v-card-actions>
          <v-btn
            color="deep-purple lighten-2"
            text
            :href="'/'+product.slug+'/detail'"
          >
            <v-icon dark>mdi-eye-outline</v-icon>
          </v-btn>
          <v-btn
            color="deep-purple lighten-2"
            text
            @click="addToCart(product)"
          >
            <v-icon dark>mdi-cart-plus</v-icon>
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-row>  
  </div>
 </template>

<script>
    export default {
        data() {
            return {
                in_cart: 0,
                products: [],
                loading: false,
                selection: 1,
            }
        },
        methods: {
          addToCart(product){
            var cartId = window.store.state.cartId ?? null;
            axios({
              method: "POST",
              url: "api/cart",
              data:{
                product_id: product.id,
                shopping_cart_id: cartId
              },
              headers:{
                "Accept": "application/json",
                "Content-Type": "application/json"
              }
            }).then((response)=>{
              if (!cartId) {
                window.store.commit("setCart", response.data.data);
              }
              window.store.commit("increment");
              this.in_cart = window.store.state.productsCount;
              Toast.fire({
                icon: 'success',
                title: 'Product added to cart successfully'
              })
            }).catch (error => {
                  Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!'
                  })
                }); 
          },
          loadProducts() {
            //pick data from controller and push it into products object
            axios.get("api/products").then( data => (this.products = data.data));
          }
        },
        created() { 
          this.in_cart = window.store.state.productsCount ?? 0;
          this.loadProducts();
        }
    }
</script> 