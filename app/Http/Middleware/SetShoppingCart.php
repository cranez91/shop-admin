<?php

namespace App\Http\Middleware;

use Closure;
use App\ShoppingCart;
use Illuminate\Support\Facades\View;

class SetShoppingCart
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*//dd(session()->get("shopping_cart_id"));
        $sessionName = "shopping_cart_id";
        //$shopping_cart_id = \Session::get($sessionName);
        $shopping_cart_id = session()->get("shopping_cart_id");
        $shopping_cart = ShoppingCart::findOrCreateById($shopping_cart_id);

        if( is_null( $shopping_cart ) ){
            $shopping_cart = ShoppingCart::findOrCreateById($shopping_cart);
        }

        //\Session::put($sessionName, $shopping_cart->id);
        session()->put("shopping_cart_id", $shopping_cart->id);
        $request->shopping_cart = $shopping_cart;

        View::share("shopping_cart", $shopping_cart);

        return $next($request);*/
        return $next($request);
    }
}
