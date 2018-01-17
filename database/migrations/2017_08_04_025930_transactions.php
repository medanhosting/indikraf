<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Transactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('Transactions', function (Blueprint $table) {
          $table->increments('transactions_id');
          $table->string('order_id');
          $table->unsignedInteger('buyer_id');
          $table->integer('amount');
          $table->string('payment_method');
          $table->string('status');
          $table->timestamps();
      });

      Schema::table('Transactions',function (Blueprint $table){
        $table->foreign('buyer_id')
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
        Schema::dropIfExists('Transactions');
    }
}
