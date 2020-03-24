<template>
	<div>
		<v-navigation-drawer
	      :clipped="$vuetify.breakpoint.lgAndUp"
	      v-model="drawer"
	      fixed
	      app
	    >
	      <v-list dense>
	        <template v-for="item in items">
	          
	          <v-list-group
	            v-if="item.children"
	            v-model="item.model"
	            :key="item.text"
	            :prepend-icon="item.model ? item.icon : item['icon-alt']"
	            append-icon=""
	          >
	            <v-list-tile slot="activator">
	              <v-list-tile-content>
	                <v-list-tile-title>
	                  {{ item.text }}
	                </v-list-tile-title>
	              </v-list-tile-content>
	            </v-list-tile>
	            <v-list-tile
	              v-for="(child, i) in item.children"
                v-if="(logged.level=='Vendedor' && !child.onlyAdmin) || logged.level!='Vendedor'"
	              :key="i"
	              @click="redirect(child.link)"
	            >
	              <v-divider
	                dark
	                v-if="child.divider"
	                class="my-2"
	                :key="i"
	              ></v-divider>	
	              <v-list-tile  >
		              <v-list-tile-action>
		                <v-icon>{{ child.icon }}</v-icon>
		              </v-list-tile-action>
		              <v-list-tile-content>
		                <v-list-tile-title>
		                  {{ child.text }}
		                </v-list-tile-title>
		              </v-list-tile-content>
		           </v-list-tile>
	            </v-list-tile>
	          </v-list-group>
	          <v-list-tile v-else :key="item.text" @click="redirect(item.link)">
	            <v-list-tile-action>
	              <v-icon>{{ item.icon }}</v-icon>
	            </v-list-tile-action>
	            <v-list-tile-content>
	              <v-list-tile-title>
	                {{ item.text }}
	              </v-list-tile-title>
	            </v-list-tile-content>
	          </v-list-tile>
	        </template>
	      </v-list>
	    </v-navigation-drawer>
	    <v-toolbar
	      :clipped-left="$vuetify.breakpoint.lgAndUp"
	      color="light-green darken-3"
	      dark
	      app
	      fixed
	    >
	      <v-toolbar-title style="width: 300px" class="ml-0 pl-3">
	        <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
	        <span class="hidden-sm-and-down">{{title}}</span>
	      </v-toolbar-title>
	      <v-spacer></v-spacer>
        <v-menu
          :close-on-content-click="false"
          :nudge-width="100"
          v-model="menu"
          offset-x
        >
          <v-btn slot="activator" color="light-green darken-3" icon  dark>
            <v-icon>account_circle</v-icon>
          </v-btn>
          <v-card>
            <v-list>
              <v-list-tile avatar>
                <v-list-tile-avatar>
                  <img src="/images/icons/avatar-profile.jpg" alt="John">
                </v-list-tile-avatar>
                <v-list-tile-content>
                  <v-list-tile-title>{{logged.name}}</v-list-tile-title>
                  <v-list-tile-sub-title>{{logged.level}}</v-list-tile-sub-title>
                </v-list-tile-content>
              </v-list-tile>
            </v-list>
            <v-divider></v-divider>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn flat href="/sadmin/user/profile">Ver perfil</v-btn>
              <v-btn flat @click="logout()">Salir</v-btn>
            </v-card-actions>
          </v-card>
        </v-menu>
	    </v-toolbar>
	</div>
</template>

<script>
  export default {
  	mounted(){

  	},
    data: () => ({
      dialog: false,
      drawer: false,
      menu: false,
      items: [
        {
          icon: 'keyboard_arrow_up',
          'icon-alt': 'keyboard_arrow_down',
          text: 'Reportes' ,
          model: false,
          children: [
            { onlyAdmin: true, icon: 'dashboard', text: 'Tablero', link: '/sadmin/home' },
            /*{ icon: 'description', text: 'Estado de cuenta por cliente', link: '/cuentaCliente' },
            { icon: 'description', text: 'Estado de cuenta por proveedor', link: '/cuentaProveedor' },
            { icon: 'archive', text: 'Existencias por producto', link: '/existenciasProducto' },
            { icon: 'content_paste', text: 'Kardex', link: '/consultarKardex' },*/
            { onlyAdmin: false, icon: 'archive', text: 'Existencias por producto', link: '/sadmin/reports/products/stocks' },
            { onlyAdmin: true, icon: 'attach_money', text: 'Reporte de Ingresos', link: '/sadmin/reports/incomes' },
            { onlyAdmin: true, icon: 'attach_money', text: 'Reporte de Utilidades', link: '/sadmin/reports/utilities' },
          ]
        },
        {
          icon: 'keyboard_arrow_up',
          'icon-alt': 'keyboard_arrow_down',
          text: 'Catálogos' ,
          model: false,
          children: [
            { onlyAdmin: false,  icon: 'flag', text: 'Categorías', link: '/sadmin/categories' },
            //{ onlyAdmin: false,  icon: 'format_size', text: 'Tallas', link: '/sadmin/sizes' },
            //{ onlyAdmin: false,  icon: 'palette', text: 'Colores', link: '/sadmin/colors' },
            { onlyAdmin: false,  icon: 'copyright', text: 'Marcas', link: '/sadmin/brands' },
            { onlyAdmin: false, divider: true},
            { onlyAdmin: false,  icon: 'person', text: 'Clientes', link: '/sadmin/customers' },
            { onlyAdmin: false,  icon: 'shop', text: 'Productos', link: '/sadmin/products'},
            { onlyAdmin: false,  icon: 'local_shipping', text: 'Proveedores', link: '/sadmin/suppliers' },
            { onlyAdmin: true, divider: true},
            { onlyAdmin: true, icon: 'person_pin', text: 'Usuarios', link: '/sadmin/users' },
            { onlyAdmin: true, icon: 'store', text: 'Tiendas', link: '/sadmin/shops' },
            /*{ divider: true},
            { icon: 'class', text: 'Licencias', link: '/licencias' },*/
          ]
        },
        {
          icon: 'keyboard_arrow_up',
          'icon-alt': 'keyboard_arrow_down',
          text: 'Operaciones' ,
          model: false,
          children: [
            { onlyAdmin: false, icon: 'chrome_reader_mode', text: 'Movimientos', link: '/sadmin/operations' },
            { onlyAdmin: false, icon: 'credit_card', text: 'Ventas', link: '/sadmin/sales' },
            { onlyAdmin: false, icon: 'monetization_on', text: 'Pagos', link: '/sadmin/payments' },
            { onlyAdmin: false, icon: 'shopping_cart', text: 'Ordenes', link: '/sadmin/orders' },
          ]
        },
        /*
        
        */
      ]
    }),
    props: {
      title: "",
      logged: Object
    },
    methods: {
    	logout(){
    		event.preventDefault();
            document.getElementById('logout-form').submit();
    	},
    	redirect(link){
    		window.location.href = link;
    	}
    }
  }
</script>