<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CmsController extends Controller
{
    public function __construct()
    {
        $this->middleware('preventBackHistory'); 
        $this->middleware('auth');
        $this->middleware("checkadmin");
        $this->middleware("cms_user");
        $this->middleware("checkstatus");
    }

    public function homePage()
    {
    	$indicators = $this->getIndicators();
        return view("cms.home", [ 
            'indicators' => $indicators
        ]);
    }

    private function getIndicators(){
    	$data = DB::table('users')
                ->select( 
                  DB::raw(
	              ' (select count(*) from products) as Productos,
	                (select count(*) from customers) as Clientes,
	                (select count(*) from sales sa where sa.`type`="Contado") as Contado,
	                (select count(*) from sales sa where sa.`type`="Crédito") as Credito,
	                (select count(*) from sales sa where sa.`type`="Crédito" and DATE(sa.expiration)<DATE(NOW())) as Vencidas,
	                (select count(*) from operations op where op.`type`="Compra" ) as Compras,
	                (select count(*) from operations op where op.`type`="Ingreso al almacén" ) as Almacen,
	                (select count(*) from operations op where op.`type`="Devolución Compra" ) as DevCompras,
	                (select count(*) from operations op where op.`type`="Devolución Venta" ) as DevVentas
	              '
	              )
                )->first();
        return $data;
    }
}
