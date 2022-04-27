<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsTrackInUserRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_requests', function (Blueprint $table) {
            $table->enum('is_track', ['YES','NO'])->default('NO');
            $table->double('track_distance', 15, 8)->default(0);
            $table->double('track_latitude', 15, 8)->default(0);
            $table->double('track_longitude', 15, 8)->default(0);
            $table->longText('destination_log')->nullable();
            $table->integer('sender_otp')->nullable();
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
