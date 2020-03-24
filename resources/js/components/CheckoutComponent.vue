<template>
  <div>
    <side-bar :count="in_cart"></side-bar>
      <v-content>
          <v-container
            class="fill-height"
            fluid
          >
            <v-row
              class="ml-12 mr-12"
              align="center"
              justify="center"
            >
              <v-card-text>
                  <v-form v-on:submit.prevent="validate()" ref="myform" v-model="valid">
                    <fieldset>
                        <legend>Contact info</legend> 
                        <v-row>
                            <v-col cols="12"  sm="6">
                                <v-text-field
                                  v-model="form.recipient_name"
                                  :rules="nameRules"
                                  :counter="50"
                                  label="Name"
                                  required
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12"  sm="6">
                                <v-text-field
                                  v-model="form.address"
                                  :rules="addressRules"
                                  :counter="80"
                                  label="Address"
                                  required
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12"  sm="6">    
                                <v-text-field
                                  v-model="form.state"
                                  :rules="stateRules"
                                  :counter="20"
                                  label="State"
                                  required
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12"  sm="6">
                                <v-text-field
                                  v-model="form.country"
                                  :rules="countryRules"
                                  :counter="20"
                                  label="Country"
                                  required
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </fieldset>

                    <fieldset>
                        <legend>Billing info</legend> 
                        <v-row>
                            <v-col cols="12"  sm="6">
                                <v-text-field
                                  v-model="form.credit_card"
                                  :rules="ccnRules"
                                  :counter="19"
                                  label="Credit Card Number"
                                  required
                                  v-mask="mask"
                                ></v-text-field>    
                            </v-col> 
                            <v-col cols="12"  sm="6"> 
                                <v-text-field
                                  v-model="form.expiration_month"
                                  :rules="expmRules"
                                  :counter="2"
                                  label="Expiration Month"
                                  hint="MM"
                                  required
                                ></v-text-field>
                            </v-col>     

                            <v-col cols="12"  sm="6"> 
                                <v-text-field
                                  v-model="form.expiration_year"
                                  :rules="expyRules"
                                  :counter="4"
                                  hint="YYYY"
                                  label="Expiration Year"
                                  required
                                ></v-text-field>
                            </v-col>  

                            <v-col cols="12"  sm="6"> 
                                <v-text-field
                                  v-model="form.verification_number"
                                  :rules="ccvnRules"
                                  :counter="3"
                                  label="Credit Card CVV"
                                  required
                                ></v-text-field>
                            </v-col>       
                        </v-row>
                    </fieldset>

                    <v-btn v-if="!sent" class="mr-4" type="submit">Submit</v-btn>
                  </v-form>
              </v-card-text>
            </v-row>
          </v-container>
      </v-content>
  </div>  
  
</template>

<script>
    import { mask } from 'vue-the-mask';
  export default {
    directives: {
      mask,
    },
    data: () => ({
      sent: false,
      panel: [true, false],
      valid: true,
      mask: '####-####-####-####',
      in_cart: 0,
      form: new Form({
        recipient_name: '',
        address : '',
        state : '',
        country: '',
        credit_card: '',
        expiration_month: '',
        expiration_year: '',
        verification_number: '',
      }),
      nameRules: [
        v => !!v || 'Name is required',
        v => (v && v.length <= 50) || 'Name must be less than 51 characters',
      ],
      addressRules: [
        v => !!v || 'Address is required',
        v => (v && v.length <= 80) || 'Address must be less than 81 characters',
      ],
      stateRules: [
        v => !!v || 'State is required',
        v => (v && v.length <= 20) || 'State must be less than 21 characters',
      ],
      countryRules: [
        v => !!v || 'Country is required',
        v => (v && v.length <= 20) || 'Country must be less than 21 characters',
      ],
      ccnRules: [
        v => !!v || 'Credit card number is required',
        v => (v && v.length <= 19) || 'Credit card number must be less than 20 characters',
      ],
      expmRules: [
        v => !!v || 'Expiration month is required',
        v => (v && v.length <= 2) || 'Expiration month must be less than 3 digits',
        v => (v && v>=1 && v<= 12) || 'Expiration month not valid',
        v => /^\d+$/.test(v) || 'Expiration month must be only digits',
      ],
      expyRules: [
        v => !!v || 'Expiration year is required',
        v => (v && v.length <= 4) || 'Expiration year must be less than 5 digits',
        v => (v && v>=2020 && v<=2050) || 'Expiration year not valid',
        v => /^\d+$/.test(v) || 'Expiration year must be only digits',
      ],
      ccvnRules: [
        v => !!v || 'Verification number is required',
        v => (v && v.length <= 3) || 'Verification number must be less than 4 digits',
        v => (v && v>=1 && v<= 999) || 'Expiration month not valid',
        v => /^\d+$/.test(v) || 'Verification number must be only digits',
      ],
    }),
    methods: {
      validate () {
        this.$refs.myform.validate();
        if (this.valid) {
          this.submit();
        }
      },
      submit () {
        this.form.shopping_cart_id = window.store.state.cartId;
        this.sent = true;
        this.form.submit("post", '/api/checkout')
          .then((response) => {
              window.store.commit("setCount", 0);
              window.store.commit("setCart", null);
              this.in_cart = 0;
              let timerInterval;
              Toast.fire({
                icon: 'success',
                title: 'Your order has been created successfully',
                allowOutsideClick: false,
                timer: 5000,
                timerProgressBar: true,
                onBeforeOpen: () => {
                  Swal.showLoading()
                  timerInterval = setInterval(() => {
                    const content = Swal.getContent()
                    if (content) {
                      const b = content.querySelector('b')
                      if (b) {
                        b.textContent = Swal.getTimerLeft()
                      }
                    }
                  }, 100)
                },
                onClose: () => {
                  clearInterval(timerInterval)
                }
              }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {

                  this.$router.push('/');
                }
              });
          })
          .catch((response) => {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: response.data.msg
            })
            this.sent = false; 
          })
      },
      reset () {
        this.$refs.myform.reset();
      },
      resetValidation () {
        this.$refs.myform.resetValidation();
      },
      clear () {
        this.$v.$reset()
        this.name = ''
        this.email = ''
        this.select = null
        this.checkbox = false
      },
    },
    created() { 
      this.in_cart = window.store.state.productsCount ?? 0;
    }
  }
</script>
