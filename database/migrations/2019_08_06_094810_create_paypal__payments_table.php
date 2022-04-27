<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaypalPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paypal__payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference');
            $table->integer('user_id');
            $table->integer('request_id')->nullable();
            $table->double('amount');
            $table->string('via')->nullable();
            $table->enum('paid',[0,1])->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paypal__payments');
    }
}
