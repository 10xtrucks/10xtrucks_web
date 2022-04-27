<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `user_requests` CHANGE `payment_mode` `payment_mode` ENUM('CASH','CARD','PAYPAL','CC_AVENUE','PAYSTACK','BOL') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL");
        DB::statement("ALTER TABLE `users` CHANGE `payment_mode` `payment_mode` ENUM('CASH','CARD','PAYPAL','CC_AVENUE','PAYSTACK','BOL') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
