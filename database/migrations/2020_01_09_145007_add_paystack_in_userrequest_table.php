<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaystackInUserrequestTable extends Migration
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
        Schema::table('user_requests', function (Blueprint $table) {
           
            $table->renameColumn('payment_mode','tmp_name');
        });

        Schema::table('user_requests', function (Blueprint $table) {
            $table->enum('payment_mode', ['CASH','CARD','PAYPAL','CC_AVENUE','PAYSTACK']);
        });

        $user_requests=DB::table('user_requests')->get();
        
        foreach($user_requests as $user_request)
        {
            DB::table('user_requests')->update(['payment_mode' => $user_request->tmp_name]);
        }

        Schema::table('user_requests', function (Blueprint $table) {
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
        Schema::table('user_requests', function (Blueprint $table) {
            //
        });
    }
}
