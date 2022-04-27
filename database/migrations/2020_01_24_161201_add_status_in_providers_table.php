<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusInProvidersTable extends Migration
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
        Schema::table('providers', function (Blueprint $table) {
            $table->renameColumn('status','tmp_name');
        });


        Schema::table('providers', function (Blueprint $table) {
            $table->enum('status', ['document','onboarding', 'approved', 'banned'])->default('document');
        });

        $providers=DB::table('providers')->get();
        
        foreach($providers as $provider)
        {
            DB::table('providers')->update(['status' => $provider->tmp_name]);
        }

        Schema::table('providers', function (Blueprint $table) {
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
        Schema::table('providers', function (Blueprint $table) {
            //
        });
    }
}
