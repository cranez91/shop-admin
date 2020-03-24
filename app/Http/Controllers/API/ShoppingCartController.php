<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Resources\ProductsCollection;
use App\Http\Controllers\Controller;


class ShoppingCartController extends Controller
{
    public function __construct(){
    	$this->middleware("shopping_cart");
        //$this->middleware("menu_options");
        //$this->middleware("preventBackHistory");
    }

    public function show(Request $request){
    	return view("shopping_cart.show", ["shoppingCart" => $request->shopping_cart]);
    }

    public function products(Request $request){
    	$products_in_cart = $request->shopping_cart->products_in_shopping_cart()->get();
    	return $this->setResult( $products_in_cart );
    }

    private function setResult($products_in_cart){
    	$array = [];
    	foreach ($products_in_cart as $record) {
            $record->product->brand;
            $record->color;
            $record->size;
	    	$record["product"] = $record->product;

    		$this->unsetProperties(
    		  $record, 
    		  [
    		  	"product_id",
	    	    "created_at",
	    	    "updated_at"	
	    	  ]
	    	);

    		array_push($array, $record);
    	}

    	return $array;	
    }

    private function unsetProperties($record, $properties){
    	foreach ($properties as $property) {
    	    unset($record[$property]);	
    	}
    }

    
}
