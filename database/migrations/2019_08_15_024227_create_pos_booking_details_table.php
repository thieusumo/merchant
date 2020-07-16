<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePosBookingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_booking_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bookingdetail_place_id');
            $table->string('booking_code',10);
            $table->integer('service_id');
            $table->integer('worker_id');
            $table->dateTime('booking_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pos_booking_details');
    }
}
