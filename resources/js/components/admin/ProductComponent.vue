<template>
  <div>
    <admin-side-bar></admin-side-bar>
    <v-card >
      <v-system-bar></v-system-bar>
      <v-toolbar flat>
        <v-toolbar-title>Products Table</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn color="primary" dark @click="prepareCreateForm()">Add New</v-btn>
        <div class="my-2">
          <v-row justify="center">
            <v-dialog v-model="dialog" persistent max-width="600px">
              <v-card>
                <v-card-title>
                  <span class="headline">{{editMode ?'Edit Product' : 'New Product'}}</span>
                </v-card-title>
                <v-card-text>
                  <form v-on:submit.prevent="editMode ? updateProduct() : createProduct()" id="product-form">
                    <v-container>
                      <v-row>
                        <v-col cols="12"  sm="6">
                          <v-text-field label="Name*" v-model="form.name" required></v-text-field>
                        </v-col>
                         <v-col cols="12"  sm="6">
                          <v-text-field label="Price*" v-model="form.price" required></v-text-field>
                        </v-col>
                        <v-col cols="12">
                          <v-textarea
                            label="Description*"
                            v-model="form.description"
                            no-resize
                            rows="3"
                          ></v-textarea>
                        </v-col>
                        <v-col cols="12">
                          <v-file-input
                            label="Image file"
                            @change="fileChanged($event)"
                            id="photo" ref="fileInput"
                            filled
                            prepend-icon="mdi-camera"
                          ></v-file-input>
                        </v-col>
                      </v-row>
                    </v-container>
                    
                  </form>
                  <small>*indicates required field</small>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
                  <v-btn color="blue darken-1" text type="submit" form="product-form">{{editMode ?'Update' : 'Save'}}</v-btn>
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
                <th class="text-left">Slug</th>
                <th class="text-left">Price</th>
                <th class="text-left">Edit</th>
                <th class="text-left">Delete</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="product in products" :key="product.id">
                <td>{{ product.name }}</td>
                <td>{{ product.slug }}</td>
                <td>{{ product.price }}</td>
                <td>
                  <v-btn class="mx-2" fab dark small color="cyan" @click="editModalWindow(product)">
                    <v-icon dark>mdi-pencil</v-icon>
                  </v-btn>
                </td>
                <td>
                  <v-btn class="mx-2" fab dark small color="red" @click="deleteProduct(product.id)">
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
              my_file: null,
              file_type: "",
              dialog: false,
              editMode: false,
              products: [],
              form: new Form({
                  id: '',
                  name : '',
                  image : '',
                  description: '',
                  price: '',
              })
            }
        },
        methods: {
          getToken(){
            return window.store.state.laravelToken;
          },
          fileChanged(e) {
              var file = e;
              if (!file)
                  return;
              this.createImage(file);
          },
          createImage(file) {
              var image = new Image();
              var reader = new FileReader();
              var vm = this;
              var str = file.name;
              this.form.image = str.replace(" ", "_");
              this.file_type = file.type;
              reader.onload = (e) => {
                  vm.my_file = e.target.result;
              };
              reader.readAsDataURL(file);
          },
          prepareCreateForm(){
            this.form.clear();
            this.form.reset();
            this.dialog = true;
          },
          editModalWindow(product){
            const input = this.$refs.fileInput;
            if (input) {
              input.value = null;
            }
             this.form.clear();
             this.editMode = true
             this.form.reset();
             this.dialog = true
             this.form.fill(product)
          },
          updateProduct(){
            if (this.my_file) {
              if (!this.validateImage()) {
                return;
              }
              this.form.my_file = this.my_file;
              this.form.file_type = this.file_type;
            }
             this.form.submit("put", '/api/products/'+this.form.id, {headers: { "Authorization": "Bearer " + this.getToken() }})
                 .then((response)=>{
                    
                    Toast.fire({
                      icon: 'success',
                      title: 'Product updated successfully'
                    })
                    Fire.$emit('AfterCreatedProductLoadIt');
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
          loadProducts() {
            //pick data from controller and push it into products object
            axios({
              method: "GET",
              url: "/api/products",
              headers: {
                "Authorization": "Bearer " + this.getToken()
              }
            }).then( data => (this.products = data.data));
          },
          createProduct(){
            if( !this.my_file ){
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: "You must choose an image."
                })
                return false;
            }

            if (!this.validateImage()) {
              return;
            }

            this.form.my_file = this.my_file;
            this.form.file_type = this.file_type;
            this.$Progress.start()
            this.form.submit("post", '/api/products', {headers: { "Authorization": "Bearer " + this.getToken() }})
                .then((response) => {
                    const input = this.$refs.fileInput;
                    input.type = 'text';
                    input.type = 'file';
                    Fire.$emit('AfterCreatedProductLoadIt'); //custom events

                    Toast.fire({
                      icon: 'success',
                      title: 'Product created successfully'
                    })

                    this.$Progress.finish()
                    this.dialog = false
                    this.loadProducts()
                })
                .catch((response) => {
                  Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.data.msg
                  })
                   
                })
          },
          validateImage(){
            var types = ["image/jpeg", "image/png"];
            if( this.file_type && types.indexOf(this.file_type) < 0 ){
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: "Only jpeg/png files are allowed."
                })
                return false;
            }
            return true;
          },
          deleteProduct(id) {
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
                this.form.submit("delete", '/api/products/'+id, {headers: { "Authorization": "Bearer " + this.getToken() }})
                    .then((response)=> {
                            Swal.fire(
                              'Deleted!',
                              'Product deleted successfully',
                              'success'
                            )
                    this.loadProducts();

                    }).catch(() => {
                        Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Something went wrong!'
                        })
                    })
                }

            })
          }
        },

        created() { 
          this.loadProducts();
          Fire.$on('AfterCreatedProductLoadIt',()=>{ //custom events fire on
              this.loadProducts();
          });
        }
    }
</script> 