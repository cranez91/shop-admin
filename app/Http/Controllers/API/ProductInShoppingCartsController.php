<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\ProductInShoppingCart;
use App\Http\Controllers\Controller;
use App\ShoppingCart;

class ProductInShoppingCartsController extends Controller
{
    public function __construct(){
    	$this->middleware("shopping_cart");
    }

    public function store(Request $request){
      $shopping_cart_id = $request->shopping_cart_id;
      if (!$shopping_cart_id) {
        $shopping_cart = ShoppingCart::query()->find($shopping_cart_id);
        $shopping_cart = ShoppingCart::findOrCreateById($shopping_cart); 

        $in_shopping_cart = ProductInShoppingCart::create([
          "shopping_cart_id" => $shopping_cart->id,
          "product_id" => $request->product_id,
        ]); 

        return response()->json(["data" =>  $shopping_cart->id, "count" =>  1], 200);
      }
      $shopping_cart = ShoppingCart::query()->find($shopping_cart_id);
      $previous = ProductInShoppingCart::query()->where([
        ["shopping_cart_id", "=", $shopping_cart_id],
        ["product_id", "=", $request->product_id]
      ])->first();

      if( !is_null( $previous ) ){
          $previous->amount = $previous->amount + 1;
          $previous->update();
      } else {
        $previous = ProductInShoppingCart::create([
          "shopping_cart_id" => $shopping_cart_id,
          "product_id" => $request->product_id,
        ]);
      }

      return response()->json(["data" => $shopping_cart_id, "count" => $shopping_cart->products_in_shopping_cart()->sum("amount")], 200);
    }

    public function show($id){
      $shopping_cart = ShoppingCart::query()->find($id);

      if( $shopping_cart ){
        $data = $shopping_cart->products_in_shopping_cart;
        foreach ($data as $item) {
          $item->product_name = $item->product->name;
          $item->product_price = $item->product->price;
          $item->product_price_formatted = number_format($item->product->price, 2, '.', ',');
          unset ($item->product_id, $item->created_at, $item->updated_at, $item->shopping_cart_id, $item->product);
        }
        return response()->json(["data" => $data], 200);
      }
      return response()->json(null, 404);
    }

    public function destroy($id){
      $row = ProductInShoppingCart::find($id);

      if( $row->delete() ){
        return response()->json("ok", 200);
      } else {
        return response()->json("error", 404);
      }
    }

    public function plus(Request $request){
      $row = ProductInShoppingCart::where("id", $request->data2)->where("shopping_cart_id", $request->data1)->first();
      if( $row ){
        $row->amount = $row->amount + 1;

        if( $row->update() ){
          return response()->json("ok", 200);    
        } else {
          return response()->json("error", 404);
        }
      }
    }

    public function minus(Request $request){
      $row = ProductInShoppingCart::where("id", $request->data2)->where("shopping_cart_id", $request->data1)->first();

      if( $row && $row->amount > 1){
        $row->amount = $row->amount - 1;

        if( $row->update() ){
            return response()->json("ok", 200);
        } else {
          return response()->json("error", 404);
        }
      }
    }
}
