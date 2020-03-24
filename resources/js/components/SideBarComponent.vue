<template>
  <v-toolbar color="deep-purple" dense>
    <v-spacer></v-spacer>
    <v-btn href="/" text color="white deep-purple--text">Shop</v-btn>
    <v-btn href="/cart" text color="white deep-purple--text"><v-icon dark>mdi-cart</v-icon>{{count}}</v-btn>
  </v-toolbar>
</template>
<script>
    export default {
        props: ["count"],
        data() {
            return {
                in_cart: 0,
            }
        }
    }
</script> 