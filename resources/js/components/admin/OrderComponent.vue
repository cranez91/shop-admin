<template>
  <div>
    <admin-side-bar></admin-side-bar>
    <v-card >
      <v-system-bar></v-system-bar>
      <v-toolbar flat>
        <v-toolbar-title>Orders Table</v-toolbar-title>
        <v-spacer></v-spacer>
        <div class="my-2">
          <v-row justify="center">
            <v-dialog v-model="dialog" persistent max-width="600px">
              <v-card>
                <v-card-title>
                  <span class="headline">Order Details</span>
                </v-card-title>
                <v-card-text>
                  <form id="product-form">
                    <v-container>
                      <v-row>
                        <v-col cols="12"  sm="6">
                          <v-text-field  prefix="$" label="Total" v-model="form.total" :readonly="true"></v-text-field>
                        </v-col>
                        <v-col cols="12"  sm="6">
                          <v-text-field label="Status" v-model="form.status" :readonly="true"></v-text-field>
                        </v-col>
                        <v-col cols="12"  sm="6">
                          <v-text-field label="Payment" v-model="form.payment" :readonly="true"></v-text-field>
                        </v-col>
                        <v-col cols="12"  sm="6">
                          <v-text-field label="Customer Name" v-model="form.recipient_name" :readonly="true"></v-text-field>
                        </v-col>
                        <v-col cols="12"  sm="6">
                          <v-text-field label="Country" v-model="form.country" :readonly="true"></v-text-field>
                        </v-col>
                        <v-col cols="12"  sm="6">
                          <v-text-field label="State" v-model="form.state" :readonly="true"></v-text-field>
                        </v-col>
                        <v-col cols="12">
                          <v-textarea
                            label="Address"
                            v-model="form.address"
                            :readonly="true"
                            no-resize
                            rows="3"
                          ></v-textarea>
                        </v-col>
                        <v-col cols="12"  sm="6">
                          <v-text-field label="Credit Card Number" v-model="form.credit_card" :readonly="true"></v-text-field>
                        </v-col>
                        <v-col cols="12"  sm="6">
                          <v-text-field label="Expiration Month" v-model="form.expiration_month" :readonly="true"></v-text-field>
                        </v-col>
                        <v-col cols="12"  sm="6">
                          <v-text-field label="Expiration Year" v-model="form.expiration_year" :readonly="true"></v-text-field>
                        </v-col>
                        </v-col>
                      </v-row>
                    </v-container>
                    
                  </form>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
            <v-dialog v-model="productsDialog" persistent max-width="600px">
              <v-card>
                <v-card-title>
                  <span class="headline">Order Products</span>
                </v-card-title>
                <v-card-text>
                  <v-simple-table
                    :dense="false"
                    :fixed-header="false"
                    :height="300"
                  >
                    <template v-slot:default>
                      <thead>
                        <tr>
                          <th class="text-left">Name</th>
                          <th class="text-left">Price</th>
                          <th class="text-left">Quantity</th>
                          <th class="text-left">Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="product in products" :key="product.id">
                          <td>{{ product.product_name }}</td>
                          <td>${{ product.product_price_formatted }}</td>
                          <td>
                            {{ product.amount }}
                          </td>
                          <td>${{ product.amount * product.product_price }}</td>
                        </tr>
                        <tr>
                          <td><strong>Total</strong></td>
                          <td colspan="2"></td>
                          <td colspan="2"><strong>${{total_cart}}</strong></td>
                        </tr>
                      </tbody>
                    </template>
                  </v-simple-table>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="productsDialog = false">Close</v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-row>
        </div>
      </v-toolbar>
      <v-card-text class="grey lighten-4">
        <v-simple-table>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left">ID</th>
                <th class="text-left">Total</th>
                <th class="text-left">Status</th>
                <th class="text-left">Payment</th>
                <th class="text-left">Customer Name</th>
                <th class="text-left">Detail</th>
                <th class="text-left">Products</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="order in orders" :key="order.id">
                <td>{{ order.id}}</td>
                <td>${{ order.total }}</td>
                <td>{{ order.status }}</td>
                <td>{{ order.payment }}</td>
                <td>{{ order.recipient_name }}</td>
                <td>
                  <v-btn class="mx-2" fab dark small color="cyan" @click="modalWindow(order)">
                    <v-icon dark>mdi-view-headline</v-icon>
                  </v-btn>
                </td>
                <td>
                  <v-btn class="mx-2" fab dark small color="cyan" @click="modalProducts(order)">
                    <v-icon dark>mdi-cart</v-icon>
                  </v-btn>
                </td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
    export default {
        data() {
            return {
              total_cart: 0,
              dialog: false,
              productsDialog: false,
              orders: [],
              products: [],
              form: new Form({
                  id: '',
                  total : '',
                  status : '',
                  payment: '',
                  recipient_name: '',
                  country: '',
                  state: '',
                  address: '',
                  credit_card: '',
                  expiration_month: '',
                  expiration_year: '',
              })
            }
        },
        methods: {
          modalWindow(order){
             this.form.clear();
             this.form.reset();
             this.dialog = true;
             this.form.fill(order);
          },
          modalProducts(order){
            axios.get("/api/cart/"+order.shopping_cart_id).then( (data)=>{
              this.products = data.data.data;
              this.total_cart = order.total;
              this.productsDialog = true;
            });
          },
          loadOrders() {
            var laravelToken = window.store.state.laravelToken;
            //pick data from controller and push it into orders object
            axios({
              method: "GET",
              url: "/api/orders",
              headers: {
                "Authorization": "Bearer " + laravelToken
              }
            }).then( data => (this.orders = data.data));
          },
          
        },

        created() { 
          this.loadOrders();
        }
    }
</script> 