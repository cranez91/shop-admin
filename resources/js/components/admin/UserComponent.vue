<template>
  <div>
    <admin-side-bar></admin-side-bar>
    <v-card >
      <v-system-bar></v-system-bar>
      <v-toolbar flat>
        <v-toolbar-title>Users Table</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn color="primary" dark @click="prepareCreateForm()">Add New</v-btn>
        <div class="my-2">
          <v-row justify="center">
            <v-dialog v-model="dialog" persistent max-width="600px">
              <v-card>
                <v-card-title>
                  <span class="headline">New User</span>
                </v-card-title>
                <v-card-text>
                  <form v-on:submit.prevent="editMode ? updateUser() : createUser()" id="product-form">
                    <v-container>
                      <v-row>
                        <v-col cols="12"  sm="6">
                          <v-text-field label="Name*" v-model="form.name" required></v-text-field>
                        </v-col>
                        <v-col cols="12"  sm="6">
                          <v-text-field label="Email*" v-model="form.email" required></v-text-field>
                        </v-col>
                        <v-col cols="12"  sm="6">
                          <v-text-field
                            v-model="form.password"
                            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                            :rules="[rules.required, rules.min]"
                            :type="show1 ? 'text' : 'password'"
                            name="input-10-1"
                            label="Password*"
                            hint="At least 8 characters"
                            counter
                            @click:append="show1 = !show1"
                          ></v-text-field>
                        </v-col>
                        
                      </v-row>
                    </v-container>
                    
                  </form>
                  <small>*indicates required field</small>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
                  <v-btn color="blue darken-1" text type="submit" form="product-form">Save</v-btn>
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
                <th class="text-left">Name</th>
                <th class="text-left">Email</th>
                <th class="text-left">Edit</th>
                <th class="text-left">Delete</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in users" :key="user.id">
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td>
                  <v-btn class="mx-2" fab dark small color="cyan" @click="editModalWindow(user)">
                    <v-icon dark>mdi-pencil</v-icon>
                  </v-btn>
                </td>
                <td>
                  <v-btn class="mx-2" fab dark small color="red" @click="deleteUser(user.id)">
                    <v-icon dark>mdi-delete</v-icon>
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
                show1: false, 
                password: 'Password',
                rules: {
                  required: value => !!value || 'Required.',
                  min: v => v.length >= 8 || 'Min 8 characters',
                },
                dialog: false,
                editMode: false,
                users: [],
                form: new Form({
                    id: '',
                    name : '',
                    email: '',
                    password: '',
                    type: '',

                })
            }
        },
        methods: {
          prepareCreateForm(){
            this.form.clear();
            this.form.reset();
            this.dialog = true;
          },
          
          editModalWindow(user){
             this.form.clear();
             this.editMode = true
             this.form.reset();
             this.dialog = true
             this.form.fill(user)
          },
          updateUser(){
            var laravelToken = JSON.parse(localStorage.getItem('laravel-token'));
             this.form.submit('put','/api/users/'+this.form.id, {headers: { "Authorization": "Bearer " + laravelToken }})
                 .then(()=>{
                     Toast.fire({
                        icon: 'success',
                        title: 'User updated successfully'
                      })
                      Fire.$emit('AfterCreatedUserLoadIt');
                      this.dialog = false
                 })
                 .catch(()=>{
                    Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: "Something went wrong."
                    })
                 })
          },
          loadUsers() {
            //pick data from controller and push it into users object
            var laravelToken = JSON.parse(localStorage.getItem('laravel-token'));
            axios({
              method: "GET",
              url: "/api/users",
              headers: {
                "Authorization": "Bearer " + laravelToken
              }
            }).then( data => (this.users = data.data));
          },
          createUser(){
              var laravelToken = JSON.parse(localStorage.getItem('laravel-token'));
              this.$Progress.start()
              this.form.submit('post','/api/users', {headers: { "Authorization": "Bearer " + laravelToken }})
                  .then(() => {
                     
                      Fire.$emit('AfterCreatedProductLoadIt'); //custom events

                          Toast.fire({
                            icon: 'success',
                            title: 'User created successfully'
                          })

                          this.$Progress.finish()
                          this.dialog = false
                          this.loadUsers();
                  })
                  .catch((error ) => {
                    
                    var errors = error.response.data.errors;
                    var msg = "";
                    for(error in errors){
                      msg += errors[error][0] + "\n";
                    }
                    var msg = 
                     Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: msg
                    })
                  })
          },
          deleteUser(id) {
            var laravelToken = JSON.parse(localStorage.getItem('laravel-token'));
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                
              if (result.value) {
                //Send Request to server
                this.form.submit('delete','/api/users/'+id, {headers: { "Authorization": "Bearer " + laravelToken }})
                    .then((response)=> {
                            Swal.fire(
                              'Deleted!',
                              'User deleted successfully',
                              'success'
                            )
                    this.loadUsers();

                    }).catch(() => {
                        Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Something went wrong!',
                          footer: '<a href>Why do I have this issue?</a>'
                        })
                    })
                }

            })
          }
        },
        created() { 
            this.loadUsers();
            Fire.$on('AfterCreatedUserLoadIt',()=>{ //custom events fire on
                this.loadUsers();
            });

        }
    }
</script> 