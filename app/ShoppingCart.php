<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ShoppingCart extends Model
{
    public static function findOrCreateById($shopping_cart_id){
      if ( !is_null( $shopping_cart_id ) ) {
        $cart = ShoppingCart::find($shopping_cart_id);
        //if( !is_null( $cart ) ){
          return $cart;
        /*} else {
          return ShoppingCart::create();
        }*/
      } else {
        Storage::disk('logs')->append('shop.log', "\n"."Se creo un nuevo registro de carrito de compras.");
        return ShoppingCart::create();
        //return new ShoppingCart;
      }	
    }

    public static function findById($shopping_cart_id){
      if ( !is_null( $shopping_cart_id ) ) {
        $cart = ShoppingCart::find($shopping_cart_id);
          return $cart;
      } 
    }

    public function products(){
    	return $this->belongsToMany("App\Product", "product_in_shopping_carts");
    }
    
    public function products_in_shopping_cart()
    {
        return $this->hasMany('App\ProductInShoppingCart');
    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    public function productsCount(){
      return $this->products()->count();
    }

    public function amount(){
      //return $this->products()->sum("price");
      $amount = 0;
      foreach ($this->products_in_shopping_cart as $product_in_cart) {
        $amount += $product_in_cart->amount * $product_in_cart->product->price2;
      }
      return $amount;
    }

}
