<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('Products', function (Blueprint $table) {
          $table->increments('product_id');
          $table->unsignedInteger('seller_id');
          $table->unsignedInteger('category_id');
          $table->string('product_name');
          $table->string('description');
          $table->double('weight');
          $table->integer('stock');
          $table->timestamps();
      });

      Schema::table('Products',function (Blueprint $table){
        $table->foreign('seller_id')
              ->references('user_id')->on('Users')
              ->onDelete('cascade');

        $table->foreign('category_id')
              ->references('category_id')->on('Categories')
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
        Schema::dropIfExists('Products');
    }
}
