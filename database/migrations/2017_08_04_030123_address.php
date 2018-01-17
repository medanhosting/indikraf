<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Address extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('Address', function (Blueprint $table) {
          $table->increments('address_id');
          $table->unsignedInteger('user_id');
          $table->string('address');
          $table->integer('city');
          $table->integer('postal_code');
          $table->string('phone');
          $table->timestamps();
      });

      Schema::table('Address',function (Blueprint $table){
        $table->foreign('user_id')
              ->references('user_id')->on('Users')
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
        Schema::dropIfExists('Address');
    }
}
