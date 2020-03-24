<template>
  <v-layout row wrap>
     <v-flex xs10 offset-xs1>
      <v-card>
        <v-card-title>
          Proveedores
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
          :items="users"
          :search="search"
          rows-per-page-text="Registros por página"
          :rows-per-page-items="rowsPerPageItems"
        >
          <template slot="items" slot-scope="props">
            <td class="text-xs-center">{{ props.item.name }}</td>
            <td class="text-xs-center">{{ props.item.email }}</td>
            <td class="text-xs-center">{{ props.item.shop.name }}</td>
            <td class="text-xs-center">{{ props.item.level }}</td>
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
              <v-btn :href="'/sadmin/users/'+props.item.id+'/edit'" class="blue" fab small dark>
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
          href="/sadmin/users/create"
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
      users: {},
    },
    data () {
      return {
        rowsPerPageItems : [5, 10, 15, { text: "Todo", value: -1 }],
        search: '',
        headers: [
          { 
            text: 'Nombre', 
            align: 'center', 
            sortable: true, 
            value: 'name' 
          },
          { 
            text: 'Correo', 
            align: 'center', 
            sortable: true, 
            value: 'email' 
          },
          { 
            text: 'Negocio', 
            align: 'center', 
            sortable: false, 
            value: 'shop.name' 
          },
          { 
            text: 'Nivel', 
            align: 'center', 
            sortable: false, 
            value: 'level' 
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
