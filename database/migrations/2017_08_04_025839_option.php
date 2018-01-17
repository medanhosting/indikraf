<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Option extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('Options', function (Blueprint $table) {
          $table->increments('option_id');
          $table->unsignedInteger('group_option_id');
          $table->string('option_name');
          $table->timestamps();
      });

      Schema::table('Options',function (Blueprint $table){
        $table->foreign('group_option_id')
              ->references('group_option_id')->on('Group_options')
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
        Schema::dropIfExists('Options');
    }
}
