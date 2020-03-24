<template>
  <v-app id="inspire">
    <cms-side-bar :logged="logged"></cms-side-bar>
    <v-content>
      <v-container fluid fill-height>
        <v-layout justify-center align-center>
          <v-layout row wrap>
            <v-flex xs10 offset-xs1>
              <v-layout class="mt-2" row wrap>
                <v-flex xs6 sm4 md3>
                  <v-card color="purple" class="white--text">
                    <v-layout row>
                      <v-flex xs7>
                        <v-card-title primary-title>
                          <div>
                            <div class="headline">Total<br/>Productos</div>
                            <div class="headline">{{indicators.Productos}}</div>
                          </div>
                        </v-card-title>
                      </v-flex>
                      <v-flex xs5 class="pt-4">
                        <v-card-media class="hidden-md-and-up"
                          src="/images/bag.png"
                          height="45px"
                          contain
                        ></v-card-media>
                        <v-card-media class="hidden-sm-and-down"
                          src="/images/bag.png"
                          height="80px"
                          contain
                        ></v-card-media>
                      </v-flex>
                    </v-layout>
                    <v-divider light></v-divider>
                    <v-card-actions class="purple darken-2">
                      <v-btn href="/sadmin/products" flat dark>
                        <v-icon left light>list</v-icon>Ver Lista
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </v-flex>
                <v-flex xs6 sm4 md3>
                  <v-card color="green" class="white--text">
                    <v-layout row>
                      <v-flex xs7>
                        <v-card-title primary-title>
                          <div>
                            <div class="headline">Total<br/>Clientes</div>
                            <div class="headline">{{indicators.Clientes}}</div>
                          </div>
                        </v-card-title>
                      </v-flex>
                      <v-flex xs5 class="pt-4">
                        <v-card-media class="hidden-md-and-up"
                          src="/images/customers.png"
                          height="45px"
                          contain
                        ></v-card-media>
                        <v-card-media class="hidden-sm-and-down"
                          src="/images/customers.png"
                          height="80px"
                          contain
                        ></v-card-media>
                      </v-flex>
                    </v-layout>
                    <v-divider light></v-divider>
                    <v-card-actions class="green darken-2">
                      <v-btn href="/sadmin/customers" flat dark>
                        <v-icon left light>list</v-icon>Ver Lista
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </v-flex>
                <v-flex xs6 sm4 md3>
                  <v-card color="brown" class="white--text">
                    <v-layout row>
                      <v-flex xs7>
                        <v-card-title primary-title>
                          <div>
                            <div class="headline">Ingresos <br/> Almacén</div>
                            <div class="headline">{{indicators.Almacen}}</div>
                          </div>
                        </v-card-title>
                      </v-flex>
                      <v-flex xs5 class="pt-4">
                        <v-card-media class="hidden-md-and-up"
                          src="/images/empty-box.png"
                          height="45px"
                          contain
                        ></v-card-media>
                        <v-card-media class="hidden-sm-and-down"
                          src="/images/empty-box.png"
                          height="80px"
                          contain
                        ></v-card-media>
                      </v-flex>
                    </v-layout>
                    <v-divider light></v-divider>
                    <v-card-actions class="brown darken-2">
                      <v-btn href="/sadmin/operations" flat dark>
                        <v-icon left light>list</v-icon>Ver Lista
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </v-flex>
                <v-flex xs6 sm4 md3>
                  <v-card color="cyan" class="white--text">
                    <v-layout row>
                      <v-flex xs7>
                        <v-card-title primary-title>
                          <div>
                            <div class="headline">Total <br/> Compras</div>
                            <div class="headline">{{indicators.Compras}}</div>
                          </div>
                        </v-card-title>
                      </v-flex>
                      <v-flex xs5 class="pt-4">
                        <v-card-media class="hidden-md-and-up"
                          src="/images/Cart.png"
                          height="45px"
                          contain
                        ></v-card-media>
                        <v-card-media class="hidden-sm-and-down"
                          src="/images/Cart.png"
                          height="80px"
                          contain
                        ></v-card-media>
                      </v-flex>
                    </v-layout>
                    <v-divider light></v-divider>
                    <v-card-actions class="cyan darken-2">
                      <v-btn href="/sadmin/operations" flat dark>
                        <v-icon left light>list</v-icon>Ver Lista
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </v-flex>
                <v-flex xs6 sm4 md3>
                  <v-card color="blue" class="white--text">
                    <v-layout row>
                      <v-flex xs7>
                        <v-card-title primary-title>
                          <div>
                            <div class="headline">Ventas <br/> Contado</div>
                            <div class="headline">{{indicators.Contado}}</div>
                          </div>
                        </v-card-title>
                      </v-flex>
                      <v-flex xs5 class="pt-4">
                        <v-card-media class="hidden-md-and-up"
                          src="/images/sales.png"
                          height="45px"
                          contain
                        ></v-card-media>
                        <v-card-media class="hidden-sm-and-down"
                          src="/images/sales.png"
                          height="80px"
                          contain
                        ></v-card-media>
                      </v-flex>
                    </v-layout>
                    <v-divider light></v-divider>
                    <v-card-actions class="blue darken-2">
                      <v-btn href="/sadmin/sales" flat dark>
                        <v-icon left light>list</v-icon>Ver Lista
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </v-flex>
                <v-flex xs6 sm4 md3>
                  <v-card color="deep-orange" class="white--text">
                    <v-layout row>
                      <v-flex xs7>
                        <v-card-title primary-title>
                          <div>
                            <div class="headline">Ventas <br/> Crédito</div>
                            <div class="headline">{{indicators.Credito}}</div>
                          </div>
                        </v-card-title>
                      </v-flex>
                      <v-flex xs5 class="pt-4">
                        <v-card-media class="hidden-md-and-up"
                          src="/images/sales.png"
                          height="45px"
                          contain
                        ></v-card-media>
                        <v-card-media class="hidden-sm-and-down"
                          src="/images/sales.png"
                          height="80px"
                          contain
                        ></v-card-media>
                      </v-flex>
                    </v-layout>
                    <v-divider light></v-divider>
                    <v-card-actions class="deep-orange darken-2">
                      <v-btn href="/sadmin/sales" flat dark>
                        <v-icon left light>list</v-icon>Ver Lista
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </v-flex>
                <v-flex xs6 sm4 md3>
                  <v-card color="red" class="white--text">
                    <v-layout row>
                      <v-flex xs7>
                        <v-card-title primary-title>
                          <div>
                            <div class="headline">Crédito <br/> Vencidas</div>
                            <div class="headline">{{indicators.Vencidas}}</div>
                          </div>
                        </v-card-title>
                      </v-flex>
                      <v-flex xs5 class="pt-4">
                        <v-card-media class="hidden-md-and-up"
                          src="/images/expired.png"
                          height="45px"
                          contain
                        ></v-card-media>
                        <v-card-media class="hidden-sm-and-down"
                          src="/images/expired.png"
                          height="80px"
                          contain
                        ></v-card-media>
                      </v-flex>
                    </v-layout>
                    <v-divider light></v-divider>
                    <v-card-actions class="red darken-2">
                      <v-btn href="/sadmin/sales" flat dark>
                        <v-icon left light>list</v-icon>Ver Lista
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </v-flex>
                <!--v-flex xs6 sm4 md3>
                  <v-card color="lime" class="white--text">
                    <v-layout row>
                      <v-flex xs7>
                        <v-card-title primary-title>
                          <div>
                            <div class="headline">Devoluciones <br/> Compra</div>
                            <div class="headline">{{indicators.DevCompras}}</div>
                          </div>
                        </v-card-title>
                      </v-flex>
                      <v-flex xs5 class="pt-4">
                        <v-card-media class="hidden-md-and-up"
                          src="/images/returnPurchase.png"
                          height="45px"
                          contain
                        ></v-card-media>
                        <v-card-media class="hidden-sm-and-down"
                          src="/images/returnPurchase.png"
                          height="80px"
                          contain
                        ></v-card-media>
                      </v-flex>
                    </v-layout>
                    <v-divider light></v-divider>
                    <v-card-actions class="lime darken-2">
                      <v-btn flat dark>
                        <v-icon left light>list</v-icon>Ver Lista
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </v-flex-->
                <!--v-flex xs6 sm4 md3>
                  <v-card color="blue-grey" class="white--text">
                    <v-layout row>
                      <v-flex xs7>
                        <v-card-title primary-title>
                          <div>
                            <div class="headline">Devoluciones <br/> Venta</div>
                            <div class="headline">{{indicators.DevVentas}}</div>
                          </div>
                        </v-card-title>
                      </v-flex>
                      <v-flex xs5 class="pt-4">
                        <v-card-media class="hidden-md-and-up"
                          src="/images/returns.png"
                          height="45px"
                          contain
                        ></v-card-media>
                        <v-card-media class="hidden-sm-and-down"
                          src="/images/returns.png"
                          height="80px"
                          contain
                        ></v-card-media>
                      </v-flex>
                    </v-layout>
                    <v-divider light></v-divider>
                    <v-card-actions class="blue-grey darken-2">
                      <v-btn flat dark>
                        <v-icon left light>list</v-icon>Ver Lista
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </v-flex-->
              </v-layout>
              <!--v-layout v-if="dataSales.length>=1" class="mt-4" row wrap>
                <v-flex xs12 class="mb-4" >
                  <v-toolbar class="grey lighten-2 innerToolbar" dark>
                    <v-toolbar-title class="text-xs-center ">Ventas del último mes</v-toolbar-title>
                  </v-toolbar>
                </v-flex>
                  <area-chart :download="true" :data="dataSales"></area-chart>
              </v-layout>
              <v-layout v-if="dataPieCategory.length>0 || dataPieBrand.length>0 || dataColumnSize.length>0" class="mt-4 mb-4" row wrap>
                <v-flex xs12 class="mb-4" >
                  <v-toolbar class="grey lighten-2 innerToolbar" dark>
                    <v-toolbar-title class="text-xs-center">Venta de Productos</v-toolbar-title>
                  </v-toolbar>
                </v-flex>
                <v-flex class="mb-3" xs12 sm4>
                   <pie-chart :label="''" :download="true" :colors="['#913483', '#23767a', '#347a54','#828217','#497a34', '#353491']" :data="dataPieCategory"></pie-chart>
                </v-flex>
                <v-flex class="mb-3" xs12 sm4>
                   <pie-chart :download="true" :colors="['#2d1956', '#18561b', '#563c18', '#18564c','#561838','#4f5618']" :data="dataPieBrand"></pie-chart>
                </v-flex>
                <v-flex class="mb-3" xs12 sm4>
                   <column-chart :data="dataColumnSize" :colors="['#79821d']"></column-chart>
                </v-flex>
                 
              </v-layout-->
            </v-flex>  
          </v-layout>
        </v-layout>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
  export default {
    data: () => ({

    }),
    props: {
      source: String,
      indicators: Object,
      logged: Object
    }
  }
</script>