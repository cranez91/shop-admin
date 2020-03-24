<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductInShoppingCart extends Model
{
    protected $fillable = ["shopping_cart_id", "product_id", "color_id", "size_id", "amount"];

    public function shopping_cart()
    {
        return $this->belongsTo('App\ShoppingCart');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function color()
    {
        return $this->belongsTo('App\Color');
    }

    public function size()
    {
        return $this->belongsTo('App\Size');
    }
}
