<template>
  <v-layout row wrap>
     <v-flex xs10 offset-xs1>
      <v-card>
        <v-card-title>
          Productos
          <v-spacer></v-spacer>
          <v-text-field
            v-model="search"
            append-icon="search"
            label="Filtrar..."
            single-line
            hide-details
          ></v-text-field>
        </v-card-title>
        <v-data-table
          :headers="headers"
          :items="products"
          :search="search"
          rows-per-page-text="Registros por página"
          :rows-per-page-items="rowsPerPageItems"
        >
          <template slot="items" slot-scope="props">
            <td>{{ props.item.sku }}</td>
            <td class="text-xs-center">{{ props.item.title }}</td>
            <td class="text-xs-center">${{ props.item.price }}</td>
            <td class="text-xs-center">${{ props.item.price2 }}</td>
            <td class="text-xs-center">{{ props.item.shop.name }}</td>
            <td v-if="props.item.status=='Activo'" class="text-xs-center">
              <v-btn class="green" fab small dark>
                 <v-icon class="pt-2">check</v-icon>
              </v-btn>
            </td>
            <td v-else class="text-xs-center">
              <v-btn class="red" fab small dark>
                 <v-icon class="pt-2">clear</v-icon>
              </v-btn>
            </td>
            <td class="text-xs-center">
              <v-btn :href="'/sadmin/products/'+props.item.id+'/edit'" class="blue" fab small dark>
                 <v-icon class="pt-2">description</v-icon>
              </v-btn>
            </td>
          </template>
          <template slot="pageText" slot-scope="{ pageStart, pageStop }">
            De {{ pageStart }} a {{ pageStop }}
          </template>
          <v-alert slot="no-results" :value="true" color="error" icon="warning">
            No se encontraron resultados para tu búsqueda "{{ search }}".
          </v-alert>
          <v-alert slot="no-data" :value="true" color="error" icon="warning">
            No hay registros por el momento.
          </v-alert>
        </v-data-table>
        <v-btn
          fab
          bottom
          right
          color="blue darken-3"
          class="pt-4 pl-4"
          dark
          fixed
          href="/sadmin/products/create"
        >
          <v-icon>add</v-icon>
        </v-btn>
      </v-card>
     </v-flex>
  </v-layout>
</template>

<script>
  export default {
    mounted(){

    },
    props: { 
      products: {},
    },
    data () {
      return {
        rowsPerPageItems : [5, 10, 15, { text: "Todo", value: -1 }],
        search: '',
        headers: [
          {
            text: 'SKU',
            align: 'left',
            sortable: true,
            value: 'sku'
          },
          { 
            text: 'Nombre', 
            align: 'center', 
            sortable: true, 
            value: 'title' 
          },
          { 
            text: 'Sin descuento', 
            align: 'center', 
            sortable: false, 
            value: 'price1' 
          },
          { 
            text: 'Con descuento', 
            align: 'center', 
            sortable: false, 
            value: 'price2' 
          },
          { 
            text: 'Tienda', 
            align: 'center', 
            sortable: false, 
            value: 'shop.name' 
          },
          { 
            text: 'Activo', 
            align: 'center', 
            sortable: false, 
            value: 'status' 
          },
          { 
            text: 'Detalle', 
            align: 'center', 
            sortable: false, 
          },
        ]
      }
    },
    methods: {

    }
  }
</script>