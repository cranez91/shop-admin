<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shopping_cart_id')->unsigned();
            $table->decimal('total', 8, 2);
            $table->enum('status', ['Waiting', 'In process', 'Attended'])->default('Waiting');
            $table->enum('payment', ['Paypal', 'Cash', 'Credit'])->default('Credit');
            $table->string('recipient_name', 50);
            $table->string('address', 80);
            $table->string('country', 20);
            $table->string('state', 20);
            $table->string('credit_card', 19);
            $table->string('expiration_month', 2);
            $table->string('expiration_year', 4);
            $table->string('verification_number', 3);
            $table->softDeletes();

            $table->foreign('shopping_cart_id')->references("id")->on("shopping_carts");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
