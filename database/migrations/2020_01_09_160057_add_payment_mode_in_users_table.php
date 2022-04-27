<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentModeInUsersTable extends Migration
{
    public function __construct()
    {
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('payment_mode','tmp_name');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum('payment_mode', ['CASH','CARD','PAYPAL','CC_AVENUE','PAYSTACK']);
        });

        $users=DB::table('users')->get();
        
        foreach($users as $user)
        {
            DB::table('users')->update(['payment_mode' => $user->tmp_name]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tmp_name');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
