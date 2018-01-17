<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Posts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Posts', function (Blueprint $table) {
            $table->increments('post_id');
            $table->unsignedInteger('writer_id');
            $table->string('title');
            $table->string('post');
            $table->timestamps();
        });

        Schema::table('Posts',function (Blueprint $table){
          $table->foreign('writer_id')
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
        Schema::dropIfExists('Posts');
    }
}
