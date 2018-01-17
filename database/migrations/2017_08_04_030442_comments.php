<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Comments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('Comments', function (Blueprint $table) {
          $table->increments('comment_id');
          $table->integer('comment_tail');
          $table->unsignedInteger('post_id');
          $table->string('name');
          $table->string('email');
          $table->string('comment');
          $table->timestamps();
      });

      Schema::table('Comments',function (Blueprint $table){
        $table->foreign('post_id')
              ->references('post_id')->on('Posts')
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
        Schema::dropIfExists('Comments');
    }
}
