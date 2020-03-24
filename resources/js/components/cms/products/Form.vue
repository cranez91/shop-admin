<template>
	<v-form id="productForm">
		<v-layout row wrap>
	          <v-flex xs10 offset-xs1>
	            <v-layout row wrap>
	              <v-flex class="pl-1 pr-1" xs12 sm6 md3>
	                  <v-text-field
				        maxlength="15" 
				        name="sku"
				        v-model="product.sku"
				        box
				        label="SKU"
				        required
				      ></v-text-field>
	              </v-flex>
	              <v-flex class="pl-1 pr-1" xs12 sm6 md3>
	                  <v-text-field
				        maxlength="60"
				        name="title"
				        v-model="product.title"
				        box
				        label="Nombre del producto"
				        required
				      ></v-text-field>
	              </v-flex>
	              <v-flex class="pl-1 pr-1" xs12 sm6 md3>
	                  <v-text-field
				        maxlength="9"
				        name="price"
				        v-model="product.price"
				        box
				        label="Precio sin descuento"
				        required
				      ></v-text-field>
	              </v-flex>
	              <v-flex class="pl-1 pr-1" xs12 sm6 md3>
	                  <v-text-field
				        maxlength="9"
				        name="price2"
				        v-model="product.price2"
				        box
				        label="Precio con descuento"
				        required
				      ></v-text-field>
	              </v-flex>
	              <v-flex class="pl-1 pr-1" xs12 sm6 md3>
	                  <v-text-field
				        maxlength="6"
				        name="points"
				        v-model="product.points"
				        box
				        label="Puntos acumulables"
				      ></v-text-field>
	              </v-flex>
	              <v-flex class="pl-1 pr-1" xs12 sm6 md3>
	                  <v-text-field
				        maxlength="6"
				        name="balancePercentage"
				        v-model="product.balancePercentage"
				        box
				        label="Dinero acumulable"
				      ></v-text-field>
	              </v-flex>
	              <v-flex class="pl-1 pr-1" xs12 sm6 md3>
	              	  <v-select
				        :items="lstallshops"
				        name="brand"
				        v-model="product.shop_id"
				        label="Elige una tienda"
				        item-value="id"
				        item-text="name"
				        @input="getShopData()"
				        single-line
				      ></v-select>
	              </v-flex>
	              <v-flex class="pl-1 pr-1" xs12 sm6 md3>
	              	  <v-select
				        :items="brands"
				        v-if="!product.shop_id ||brands.length>0"
				        name="brand"
				        v-model="product.brand_id"
				        label="Elige una marca"
				        item-value="id"
				        item-text="name"
				        single-line
				      ></v-select>
				      <v-text-field 
				        v-if="product.shop_id && brands.length<1"
				        value="No hay marcas para ésta tienda" 
				        solo-inverted 
				        readonly
				        label="Marca"></v-text-field>
	              </v-flex>
	              <v-flex class="pl-1 pr-1" xs12 sm6 md3>
	              	  <v-select
				        :items="categories"
				         v-if="!product.shop_id ||categories.length>0"
				        name="category"
				        v-model="selected_category"
				        label="Elige una categoría"
				        item-value="id"
				        item-text="name"
				        @input="changeCategory()"
				        single-line
				      ></v-select>
				      <v-text-field 
				        v-if="product.shop_id && categories.length<1"
				        value="No hay categorías para ésta tienda" 
				        solo-inverted 
				        readonly
				        label="Categoría"></v-text-field>
	              </v-flex>
	              <v-flex v-if="subcategories.length>0 && selected_category" class="pl-1 pr-1" xs12 sm6 md3>
	              	  <label>Subcategoría</label>
	                  <v-select
				        :items="subcategories"
				        name="subcategory"
				        v-model="selected_subcategory"
				        label="Elige..."
				        item-value="id"
				        item-text="name"
				        single-line
				      ></v-select>
	              </v-flex>
	              <v-flex v-if="method=='PUT'" class="pl-1 pr-1" xs12 sm6 md3>
	              	  <label>Estado</label>
	                  <v-select
				        :items="statusOptions"
				        v-model="product.status"
				        label="Estado"
				        single-line
				      ></v-select>
	              </v-flex>
	              <v-flex v-if="method=='PUT'" class="pl-1 pr-1" xs12 sm6 md3>
	              	  <label>Pagar contra entrega</label>
	                  <v-select
				        :items="cashOptions"
				        v-model="product.paymentCash"
				        label="Estado"
				        single-line
				      ></v-select>
	              </v-flex>
	            </v-layout>
	            <v-layout row wrap>
	              <v-flex xs12 sm6 offset-sm3 md6 offset-md3 >
	                  <v-text-field
		                name="description"
		                label="Descripción"
		                textarea
		                v-model="product.description"
		              >
		              </v-text-field>   
	              </v-flex>
	            </v-layout>

	            <v-layout row wrap>
	            	<v-flex xs12 sm6 offset-sm3 >
			            <input-file-image
						  :product='product'
						  :method='method'
						>
						</input-file-image>
					</v-flex>
	            </v-layout>

	            <v-layout 
	              v-if="method=='POST'" 
	              class="text-xs-center mt-4" 
	              row wrap
	            >
	            	<v-flex xs12 sm6 offset-sm3 >
	            		<h3>Galería de imágenes</h3>
			            <v-btn 
			              v-if="lstImages.length<4"	
			              @click="addImage()" 
			              fab dark 
			              class="indigo"
			            >
					      <v-icon class="pt-3" dark>add</v-icon>
					    </v-btn>
					</v-flex>
	            </v-layout>
	            
	            <v-layout v-if="method=='POST'" row wrap>
	            	<v-flex v-for="(image, index) in lstImages" :key="index" xs12 sm6 md4 lg3 >
			            <input-file-image
						  :product='image'
						  :method='method'
						>
						</input-file-image>
					</v-flex>
	            </v-layout>

	            <v-layout 
	              v-if="method=='PUT'" 
	              class="text-xs-center mt-4" 
	              row wrap
	            >
	            	<v-flex xs12 sm6 offset-sm3 >
	            		<h3  >Galería de imágenes</h3>
			            <v-btn 
			              v-if="product.galleries.length<4" 
			              @click="addImage()" 
			              fab dark 
			              class="indigo"
			            >
					      <v-icon class="pt-3" dark>add</v-icon>
					    </v-btn>
					</v-flex>


	            </v-layout>
	            
	            <v-layout v-if="method=='PUT'" row wrap>
	            	<v-flex v-for="(image, index) in product.galleries" :key="index" xs12 sm6 md4 lg3 >
			            <input-file-image
						  :product='image'
						  :method='method'
						>
						</input-file-image>
					</v-flex>
	            </v-layout>

	          </v-flex>
	    </v-layout>
	    <v-btn href="/sadmin/products" class="blue-grey lighten-4 ">Volver</v-btn>
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
	    	//console.log("marcas", this.lstallbrands);
	    	this.getCategories();
	    	this.setCurrentBrand();
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
		        cashOptions: [
		          { value: 'Si', 
		            text: 'Si' 
		          },
		          { value: 'No', 
		            text: 'No' 
		          },
		        ],
		        selected_brand: null,
		        selected_category: null,
		        selected_shop: null,
		        selected_subcategory: null,
	            drawer: false,
	            toolBarTitle: "Registrar Producto",
	            e1: 0,
	            dialog: false,
		  		//lstall: [],
		  		categories: [],
		  		brands: [],
		  		subcategories: [],
		  		lstImages: [],
		  		number_image: 0
	        }
	    },
		props: { 
		  product: { 
		  	image_url:null
		  },
		  lstall:{type:Array},
		  lstallshops:{type:Array},
		  lstallbrands:{type:Array},
		  method: "",
		  route: "",
		},
		methods:{
			getShopData(){
				this.product.brand_id = null;
				this.selected_category = null;
				this.selected_subcategory = null;
				this.brands = this.lstallbrands.filter(brand => brand.shop_id == this.product.shop_id );
				this.categories = this.lstall.filter(category => (category.shop_id == this.product.shop_id && category.parent_id == null) );
	            if( this.method=="PUT"){
	            	this.setCurrentCategory();
	            }
			},
			addImage(){
				if( this.number_image < 4 ){
					let image = {
					  image_url: ""
					};

					if( this.method=='PUT' ){
						this.product.galleries.push( image );
						this.number_image++;
					} else if ( this.method=='POST' ){
						this.lstImages.push( image );
						this.number_image++;
					}
				}
			},
			setCurrentCategory(){
				let current = this.lstall.filter(category => category.id == this.product.category_id )[0];

				 if (current && current.parent_id != null) {
				 	this.selected_subcategory = current.id;
				 	this.selected_category = this.lstall.find(category => category.id == current.parent_id ).id;
				 	this.setSubcategory();
				 } else if ( current && current.parent_id == null){
				 	this.selected_category = current.id;
				 }
			},
			setCurrentBrand(){
				if( this.method=="PUT"){
					this.brands = this.lstallbrands.filter(brand => brand.shop_id == this.product.shop_id );
	            	let current = this.brands.filter(brand => brand.id == this.product.brand_id )[0];
			 		this.selected_brand = current.id;
	            }
				
			},
			getCategories(){
	            this.categories = this.lstall.filter(category => category.parent_id == null );
	            if( this.method=="PUT"){
	            	this.setCurrentCategory();
	            }
	        },
	        changeCategory(){
	        	this.setSubcategory();
	        	this.selected_subcategory = null;
	        },
	        setSubcategory(){
	        	this.subcategories = this.lstall.filter(category => category.parent_id == this.selected_category );
	        },
			send(){
				if( !this.validate() ){
					return;
				}

				var url_to = this.method=="POST"?"/sadmin/products":"/sadmin/products/"+this.product.id;
				var data = this.setData();

				axios({
					method: this.method,
					url: url_to,
					timeout: 10000, // Let's say you want to wait at least 180 seconds
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
		                  window.location.href= "/sadmin/products";
		                });
					}
				}).catch ((error) => {
		            this.makeSwal("Lo sentimos", "Ocurrió un error inesperado, intenta de nuevo.", "error", "Aceptar");
		        });  
			},
			validate(){
				if( !this.product.shop_id ){
					this.makeSwal("¡Espera!", "No has elegido una tienda.", "warning", "Aceptar");
					return false;
				} else if( !this.product.brand_id ){
					this.makeSwal("¡Espera!", "No has elegido una marca.", "warning", "Aceptar");
					return false;
				} else if ( !this.selected_category ){
					this.makeSwal("¡Espera!", "No has elegido una categoría.", "warning", "Aceptar");
					return false;
				} else if ( !this.selected_subcategory ){
					this.makeSwal("¡Espera!", "No has elegido una subcategoría.", "warning", "Aceptar");
					return false;
				}

				return true;
			},
			setData(){
				var lstImages;

				if( this.method=="POST" ){
					lstImages = JSON.parse( JSON.stringify( this.lstImages ) ); 
				} else {
					lstImages = JSON.parse( JSON.stringify( this.product.galleries ) ); 
				}

				for (var i = lstImages.length; i < 4; i++) {
					lstImages.push({ image_url: "" });
				}
				
				let data = {
					id: this.product.id || null,
					sku: this.product.sku || null,
					title: this.product.title || null,
					description: this.product.description || null,
					price: this.product.price || null,
					category_id: this.selected_subcategory || null,
					shop_id: this.product.shop_id || null,
					brand_id: this.product.brand_id || null,
					price2: this.product.price2 || null,
					points: this.product.points || null,
					balancePercentage: this.product.balancePercentage || null,
					image_url: this.product.image_url || null,
					status: this.product.status,
					paymentCash: this.product.paymentCash,
					images: lstImages
				};

				if( !  this.product.image_url ){
					data.new_image_url = this.product.new_image_url;
				}

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

<style>
	.input-group__input {
		border: 1px solid #ccc;
	}

	textarea{
		resize: none;
	}
</style>