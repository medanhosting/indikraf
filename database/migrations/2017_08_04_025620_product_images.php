<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Product_images', function (Blueprint $table) {
            $table->increments('product_image_id');
            $table->unsignedInteger('product_id');
            $table->string('product_image_name');
            $table->timestamps();
        });

        Schema::table('Product_images',function (Blueprint $table){
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
        Schema::dropIfExists('Product_images');
    }
}
