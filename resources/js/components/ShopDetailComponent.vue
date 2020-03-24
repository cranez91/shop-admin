<template>
  <div v-if="product">
    <side-bar :count="in_cart"></side-bar>
    <v-row justify="center">
      <v-col cols="12" sm="8">
        <v-card
          v-if="!product"
          class="mx-auto"
          max-width="344"
          outlined
        >
          <v-list-item three-line>
            <v-list-item-content>
              <div class="overline mb-4">Something went wrong</div>
              <v-list-item-title class="headline mb-1">Not found</v-list-item-title>
              <v-list-item-subtitle>We could not find the requested product.</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>

          <v-card-actions>
            <v-btn
              color="deep-purple lighten-2"
              text
              href="#/shop"
            >
              Go to shop list
            </v-btn>
          </v-card-actions>
        </v-card>
        <v-card v-if="product" >
          <v-card-title class="deep-purple">
            <span class="headline white--text">{{product.name}}</span>
            <v-spacer></v-spacer>
            <v-btn
              color="white"
              class="ma-2 deep-purple--text"
              @click="addToCart(product)"
            >
              <v-icon right dark>mdi-cart-plus</v-icon>
            </v-btn>
            </v-btn-->
            </v-card-title>

          <v-list>
            <v-list-item @click="">
              <v-list-item-action>
                <v-icon>mdi-currency-usd</v-icon>
              </v-list-item-action>

              <v-list-item-content>
                <v-list-item-title>{{product.price}}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>

            <v-divider inset></v-divider>

            <v-list-item @click="">
              <v-list-item-action>
                Description
              </v-list-item-action>

              <v-list-item-content>
                <v-list-item-title>{{product.description}}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list>

          <v-img
            :src="'/img/products/'+product.image"
            height="400px"
          ></v-img>
        </v-card>

      </v-col>
    </v-row>
  </div>
  
 </template>

<script>
    export default {
        data() {
            return {
                in_cart: 0,
                product: {},
                loading: false,
                selection: 1,
            }
        },
        methods: {
          getProduct(slug) {
            //pick data from controller and push it into product object
            axios.get("/api/products/"+slug)
            .then( (data)=>{
              this.product = data.data.product;
            })
            .catch (error => {
              this.$router.push('/');
            });
          },
          addToCart(product){
            var cartId = window.store.state.cartId ?? null;
            axios({
              method: "POST",
              url: "/api/cart",
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
          }
        },
        created() { 
          this.product = null;
          var slug = this.$route.params.slug ?? null;
          this.in_cart = window.store.state.productsCount ?? 0;
          if (!slug) {
            this.$router.push('/');
          }
          this.getProduct(slug);
        }
    }
</script> 