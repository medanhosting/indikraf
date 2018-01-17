<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('Products_options', function (Blueprint $table) {
          $table->increments('product_option_id');
          $table->unsignedInteger('group_option_id');
          $table->unsignedInteger('option_id');
          $table->unsignedInteger('product_id');
          $table->integer('additional_price');
          $table->timestamps();
      });

      Schema::table('Products_options',function (Blueprint $table){
        $table->foreign('group_option_id')
              ->references('group_option_id')->on('Group_options')
              ->onDelete('cascade');

        $table->foreign('option_id')
              ->references('option_id')->on('Options')
              ->onDelete('cascade');

        $table->foreign('product_id')
              ->references('product_id')->on('Products')
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
        Schema::dropIfExists('Products_options');
    }
}
