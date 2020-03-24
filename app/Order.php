<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
 
    protected $dates = ['deleted_at'];
    
	protected $fillable = [
        "shopping_cart_id", 
        "total", 
        "status",
        "payment",  
        "recipient_name",
        "address", 
        "country", 
        "state", 
        "credit_card", 
        "expiration_month", 
        "expiration_year", 
        "verification_number"
    ];

    public function shopping_cart()
    {
        return $this->belongsTo('App\ShoppingCart');
    }
}
