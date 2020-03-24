<template>
  <div>
    <v-system-bar color="deep-purple darken-3"></v-system-bar>
    <v-app-bar
      color="deep-purple accent-4"
      dark
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
    </v-app-bar>
    <v-navigation-drawer
        v-model="drawer"
        absolute
        bottom
        temporary
      >
        <v-list
          nav
          dense
        >
          <v-list-item-group
            v-model="group"
            active-class="deep-purple--text text--accent-4"
          >
            <v-list-item href="/admin/users">
              <v-list-item-title >Users</v-list-item-title>
            </v-list-item>

            <v-list-item href="/admin/products">
              <v-list-item-title>Products</v-list-item-title>
            </v-list-item>

            <v-list-item href="/admin/orders">
              <v-list-item-title>Orders</v-list-item-title>
            </v-list-item>

            <v-list-item @click="logout()">
              <v-list-item-title>
                <v-icon right>mdi-logout-variant</v-icon>
                Logout
              </v-list-item-title>
            </v-list-item>
          </v-list-item-group>
        </v-list>
      </v-navigation-drawer>
  </div>
</template>
<script>
  export default {
    data: () => ({
      drawer: false,
      group: null,
    }),

    watch: {
      group () {
        this.drawer = false
      },
    },
    methods: {
      logout () {
        var url_to = "/api/auth/logout";
        var laravelToken = JSON.parse(localStorage.getItem('laravel-token'));
        axios({
          method: "GET",
          url: url_to,
          headers: {
            "Authorization": "Bearer " + laravelToken
          }
        }).then((response)=>{
          localStorage.removeItem("laravel-token");
          this.$router.push('/login');
        }).catch ((error) => {
          Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Something went wrong!'
            })
        });  
      },
      clear () {
        this.form.clear();
        this.form.reset();
      },
    },
  }
</script>