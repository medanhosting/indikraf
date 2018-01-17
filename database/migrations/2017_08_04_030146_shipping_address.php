<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShippingAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Shipping_address', function (Blueprint $table) {
            $table->increments('shipping_id');
            $table->string('order_id');
            $table->unsignedInteger('address_id');
            $table->timestamps();
        });

        Schema::table('Shipping_address',function (Blueprint $table){
          $table->foreign('address_id')
                ->references('address_id')->on('Address')
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
        Schema::dropIfExists('Shipping_address');
    }
}
