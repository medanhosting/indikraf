<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Carts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('Carts', function (Blueprint $table) {
          $table->increments('cart_id');
          $table->unsignedInteger('buyer_id');
          $table->unsignedInteger('product_id');
          $table->unsignedInteger('product_option_id');
          $table->unsignedInteger('transaction_id');
          $table->integer('price');
          $table->integer('amount');
          $table->integer('total_price');
          $table->tinyInteger('status');
          $table->timestamps();
      });

      Schema::table('Carts',function (Blueprint $table){
          $table->foreign('buyer_id')
                ->references('user_id')->on('Users')
                ->onDelete('cascade');

          $table->foreign('product_id')
                ->references('product_id')->on('Products')
                ->onDelete('cascade');

          $table->foreign('product_option_id')
                ->references('product_option_id')->on('Product_options')
                ->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Carts');
    }
}
