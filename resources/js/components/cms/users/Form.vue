<template>
	<v-form id="productForm">
		<v-layout row wrap>
	          <v-flex xs10 offset-xs1>
	            <v-layout row wrap>
	              <v-flex class="pl-1 pr-1" xs12 sm6 md3>
	                  <v-text-field
				        maxlength="60"
				        name="name"
				        v-model="user.name"
				        box
				        label="Nombre"
				        required
				      ></v-text-field>
	              </v-flex>
	              <v-flex class="pl-1 pr-1" xs12 sm6 md3>
	                  <v-text-field
	                    type="email"
				        maxlength="130" 
				        name="email"
				        v-model="user.email"
				        box
				        label="Correo electrónico"
				        required
				      ></v-text-field>
	              </v-flex>
	              <v-flex v-if="method=='POST'" class="pl-1 pr-1" xs12 sm6 md3>
	                  <v-text-field
	                    type="password"
				        maxlength="130" 
				        name="password"
				        v-model="user.password"
				        box
				        label="Contraseña"
				        required
				      ></v-text-field>
	              </v-flex>
	              <v-flex v-if="method=='POST'" class="pl-1 pr-1" xs12 sm6 md3>
	                  <v-text-field
	                    type="password"
				        maxlength="130" 
				        name="confirm_password"
				        v-model="user.confirm_password"
				        box
				        label="Confirmar Contraseña"
				        required
				      ></v-text-field>
	              </v-flex>
	              <v-flex class="pl-1 pr-1" xs12 sm6 md3>
	                  <v-text-field
				        maxlength="100"
				        name="address"
				        v-model="user.address"
				        box
				        label="Domicilio (Calle y número)"
				        
				      ></v-text-field>
	              </v-flex>
	              <v-flex class="pl-1 pr-1" xs12 sm6 md3>
	                  <v-text-field
				        maxlength="50"
				        name="suburb"
				        v-model="user.suburb"
				        box
				        label="Colonia"
				        
				      ></v-text-field>
	              </v-flex>
	              <v-flex class="pl-1 pr-1" xs12 sm6 md3>
	                  <v-text-field
				        maxlength="30"
				        name="township"
				        v-model="user.township"
				        box
				        label="Municipio"
				        
				      ></v-text-field>
	              </v-flex>
	              <v-flex class="pl-1 pr-1" xs12 sm6 md3>
	                  <v-text-field
				        maxlength="5"
				        name="postcode"
				        v-model="user.postcode"
				        box
				        label="Código Postal"
				        
				      ></v-text-field>
	              </v-flex>
	              <v-flex class="pl-1 pr-1" xs12 sm6 md3>
	                  <v-text-field
				        maxlength="50"
				        name="state"
				        v-model="user.state"
				        box
				        label="Estado"
				        
				      ></v-text-field>
	              </v-flex>
	              <v-flex class="pl-1 pr-1" xs12 sm6 md3>
	                  <v-text-field
				        maxlength="10"
				        name="phone"
				        v-model="user.phone"
				        box
				        label="Teléfono"
				        
				      ></v-text-field>
	              </v-flex>
	              <v-flex class="pl-1 pr-1" xs12 sm6 md3>
	                  <v-text-field
				        maxlength="10"
				        name="cellphone"
				        v-model="user.cellphone"
				        box
				        label="Celular"
				        
				      ></v-text-field>
	              </v-flex>
	              <v-flex class="pl-1 pr-1" xs12 sm6 md3>
	              	  <label>Nivel de usuario</label>
	                  <v-select
				        :items="types"
				        v-model="user.level"
				        label="Estado"
				        item-value="value"
				        item-text="text"
				        single-line
				      ></v-select>
	              </v-flex>
	              <v-flex class="pl-1 pr-1" xs12 sm6 md3>
	              	  <label>Tienda</label>
	                  <v-select
				        :items="shops"
				        v-model="user.shop_id"
				        label="Estado"
				        item-value="id"
				        item-text="name"
				        single-line
				      ></v-select>
	              </v-flex>
	              <v-flex v-if="method=='PUT'" class="pl-1 pr-1" xs12 sm6 md3>
	              	  <label>Estado</label>
	                  <v-select
				        :items="statusOptions"
				        v-model="user.status"
				        label="Estado"
				        single-line
				      ></v-select>
	              </v-flex>
	            </v-layout>
	          </v-flex>
	    </v-layout>
	    <v-btn href="/sadmin/users" class="blue-grey lighten-4 ">Volver</v-btn>
	    <v-btn @click="send()" class="blue white--text">Guardar</v-btn>
    </v-form>      
</template>

<script>
	import swal from 'sweetalert2';
	export default {
		components: {
	      swal,
	    },
	    mounted(){
	    	//this.setInitialData();
	    },
	    data(){
	        return {
	        	statusOptions: [
		          { value: 'Activo', 
		            text: 'Activo' 
		          },
		          { value: 'Inactivo', 
		            text: 'Inactivo' 
		          },
		        ],
		        drawer: false,
	            e1: 0,
	            dialog: false,
	        }
	    },
		props: { 
		  user: { 
		  },
		  shops: { 
		  },
		  types: { 
		  },
		  method: "",
		  route: "",
		},
		methods:{
			send(){
				
				if(!this.validate()){
					return;
				}

				var url_to = this.method=="POST"?"/sadmin/users":"/sadmin/users/"+this.user.id;
				var data = this.setData();

				axios({
					method: this.method,
					url: url_to,
					data: data,
					headers:{
						"Accept": "application/json",
						"Content-Type": "application/json"
					}
				}).then((response)=>{
					if( response.data.status == "error" ){
						this.makeSwal("Lo sentimos", response.data.msg, "error", "Aceptar");
					} else if ( response.data.status == "ok" ){
						swal({
		                  title: "Listo!",
		                  html: response.data.msg,
		                  type: "success",
		                  confirmButtonText: "Aceptar",
		                  allowOutsideClick: false
		                }).then(function(){
		                  window.location.href= "/sadmin/users";
		                });
					}
				}).catch ((error) => {
		            this.makeSwal("Lo sentimos", "Ocurrió un error inesperado, intenta de nuevo.", "error", "Aceptar");
		        });  
			},
			validate(){
				if( !this.user.level ){
					this.makeSwal("¡Espera!", "No has elegido una nivel de usuario.", "warning", "Aceptar");
					return false;
				} else if ( !this.user.shop_id ){
					this.makeSwal("¡Espera!", "No has elegido una tienda.", "warning", "Aceptar");
					return false;
				}

				return true;
			},
			setData(){
				let data = {
					id: this.user.id || null,
					name: this.user.name || null,
					email: this.user.email || null,
					password: this.user.password || null,
					confirm_password: this.user.confirm_password || null,
					address: this.user.address || null,
					postcode: this.user.postcode || null,
					suburb: this.user.suburb || null,
					township: this.user.township || null,
					state: this.user.state || null,
					phone: this.user.phone || null,
					cellphone: this.user.cellphone || null,
					status: this.user.status || null,
					level: this.user.level || null,
					shop_id: this.user.shop_id || null,
				};

				return  data;
			},
	          makeSwal(swalTitle, swalText, swalType, buttonText){
		        swal({
		          title: swalTitle,
		          html: swalText,
		          type: swalType,
		          confirmButtonText: buttonText,
		          allowOutsideClick: false
		        });
		      }
		}
    }
</script>