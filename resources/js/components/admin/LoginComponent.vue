<template>
    <v-card
      class="mx-auto mt-12"
      max-width="400"
    >
      <v-card-text class="text--primary">
        <form>
            <v-text-field
              v-model="form.email"
              label="E-mail"
              required
            ></v-text-field>
            <v-text-field
              :append-icon="show3 ? 'mdi-eye' : 'mdi-eye-off'"
              :type="show3 ? 'text' : 'password'"
              v-model="form.password"
              label="Password"
              @click:append="show3 = !show3"
              required
            ></v-text-field>
          <v-btn class="mr-4" @click="submit">Login</v-btn>
          <v-btn @click="clear">clear</v-btn>
        </form>
      </v-card-text>
    </v-card>
</template>
<script>
  export default {
    data: () => ({
      show3: false,
      form: new Form({
        email: '',
        password: '',
      })
    }),
    methods: {
      submit () {
        this.form.post('api/auth/login')
          .then((response) => {
            localStorage.setItem('laravel-token', JSON.stringify(response.data.access_token));
            this.$router.push('/admin/users');
          })
          .catch((error) => {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'The user and/or password are incorrect!'
            })
          })
      },
      clear () {
        this.form.clear();
        this.form.reset();
      },
    },
  }
</script>