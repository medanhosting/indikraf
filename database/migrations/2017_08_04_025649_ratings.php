<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Ratings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Ratings', function (Blueprint $table) {
            $table->increments('rating_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('user_id');
            $table->integer('rating');
            $table->string('comments');
            $table->timestamps();
        });

        Schema::table('Ratings',function (Blueprint $table){
          $table->foreign('user_id')
                ->references('user_id')->on('Users')
                ->onDelete('cascade');

          $table->foreign('product_id')
                ->references('category_id')->on('Products')
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
        Schema::dropIfExists('Ratings');
    }
}
