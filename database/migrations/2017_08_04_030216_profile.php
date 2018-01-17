<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Profile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('Profiles', function (Blueprint $table) {
          $table->increments('profile_id');
          $table->unsignedInteger('user_id');
          $table->string('first_name');
          $table->string('last_name');
          $table->string('profile_image');
          $table->timestamps();
      });

      Schema::table('Profiles',function (Blueprint $table){
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
        Schema::dropIfExists('Profiles');
    }
}
