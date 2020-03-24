<template>
  <div>
    <side-bar :count="in_cart"></side-bar>
    <v-simple-table
      :dense="dense"
      :fixed-header="fixedHeader"
      :height="height"
      v-if="items.length>0"
    >
      <template v-slot:default>
        <thead>
          <tr>
            <th class="text-left">Name</th>
            <th class="text-left">Price</th>
            <th class="text-left">Quantity</th>
            <th class="text-left">Total</th>
            <th class="text-left">Remove</th>
            
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in items" :key="item.id">
            <td>{{ item.product_name }}</td>
            <td>${{ item.product_price_formatted }}</td>
            <td>
              <v-btn @click.prevent="plus(item.id)" class="ma-2" tile large color="deep-purple" icon>
                <v-icon>mdi-plus-circle-outline</v-icon>
              </v-btn>  
              {{ item.amount }}
              <v-btn :disabled="item.amount<2" @click.prevent="minus(item.id)" class="ma-2" tile large color="deep-purple" icon>
                <v-icon>mdi-minus-circle-outline</v-icon>
              </v-btn>  
            </td>
            <td>${{ item.amount * item.product_price }}</td>
            <td>
              <v-btn @click.prevent="remove(item.id, item.amount)" class="ma-2" tile large color="red" icon>
                <v-icon>mdi-close-circle-outline</v-icon>
              </v-btn>  
            </td>
          </tr>
          <tr>
            <td><strong>Total</strong></td>
            <td colspan="2"></td>
            <td colspan="2"><strong>${{total_cart}}</strong></td>
          </tr>
        </tbody>
      </template>
    </v-simple-table>
    <v-btn v-if="items.length>0" block color="deep-purple lighten-1" href="/checkout" dark>Go to checkout</v-btn>
    <v-card
      class="mx-auto mt-12"
      max-width="400"
      v-if="items<1"
    >
      <v-img
        class="white--text align-end"
        height="200px"
        src="/img/empty_cart.jpg"
      >
      </v-img>
      <v-card-text class="text--primary">
        <div>Your shopping cart is empty!</div>
      </v-card-text>

      <v-card-actions>
        <v-btn
          color="deep-purple lighten-1"
          text
          href="/"
        >
          View products
        </v-btn>
      </v-card-actions>
    </v-card>
  </div>
 </template>

<script>
    export default {
        data() {
            return {
              total_cart: 0,
              cart_id: null,
              in_cart: 0,
              dense: false,
              fixedHeader: false,
              height: 300,
              items: [],
            }
        },
        methods: {
          getCartItems() {
            //pick data from controller and push it into items list
            axios.get("/api/cart/"+this.cart_id).then( (data)=>{
              this.items = data.data.data;
              this.total_cart = 0;
              for (var i = 0; i < this.items.length; i++) {
                this.total_cart += parseFloat(this.items[i].amount) * parseFloat(this.items[i].product_price);
              }
            });
          },
          plus(inShoppingCart) {
            let data = {
              data1: this.cart_id,
              data2: inShoppingCart
            };

            axios.post("/api/cart/plus", data).then((response)=>{
              window.store.commit("increment");
              this.in_cart = window.store.state.productsCount;
              this.getCartItems();
            }).catch (error => {
                    
            }); 
          },
          minus(inShoppingCart) {
            if (this.in_cart>0) {
              let data = {
                data1: this.cart_id,
                data2: inShoppingCart
              };

              axios.post("/api/cart/minus", data).then((response)=>{
                window.store.commit("decrement");
                this.in_cart = window.store.state.productsCount;
                this.getCartItems();
              }).catch (error => {
                      
              }); 
            }
          },
          remove(inShoppingCart, amount){
            axios.delete("/api/cart/"+inShoppingCart).then( response =>{
              var count = window.store.state.productsCount ?? 0;
              this.in_cart = count-amount;
              window.store.commit("setCount", this.in_cart);
              this.getCartItems();
            });
          }
        },
        created() { 
          this.cart_id = window.store.state.cartId ?? null;
          this.in_cart = window.store.state.productsCount ?? 0;
          this.getCartItems();
        }
    }
</script> 