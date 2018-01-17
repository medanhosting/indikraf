<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductImageRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Product_image_requests', function (Blueprint $table) {
            $table->increments('product_image_id');
            $table->unsignedInteger('request_id');
            $table->string('product_image_name');
            $table->timestamps();
        });

        Schema::table('Product_image_requests',function (Blueprint $table){
            $table->foreign('request_id')
                  ->references('request_id')->on('Requests')
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
        Schema::dropIfExists('Product_image_requests');
    }
}
