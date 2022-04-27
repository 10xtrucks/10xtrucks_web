<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRequestDropoffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_request_dropoff', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_request_id');
            $table->string('service_items')->nullable();
            $table->string('s_address')->nullable();
            $table->double('s_latitude', 15, 8);
            $table->double('s_longitude', 15, 8);

            $table->string('d_address')->nullable();
            $table->double('d_latitude', 15, 8);
            $table->double('d_longitude', 15, 8);

            $table->enum('status', [
                    'SEARCHING',
                    'CANCELLED',
                    'ACCEPTED', 
                    'STARTED',
                    'ARRIVED',
                    'PICKEDUP',
                    'DROPPED',
                    'COMPLETED',
                    'SCHEDULED',
                ]); 

            $table->boolean('user_rated')->default(0)->nullable();
            $table->boolean('provider_rated')->default(0)->nullable();

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
        Schema::dropIfExists('user_request_dropoff');
    }
}
