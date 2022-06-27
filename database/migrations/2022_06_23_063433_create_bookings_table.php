<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('doctor')->nullable();
            $table->string('tgl_booking')->nullable();
            $table->string('deskripsi_booking')->nullable();
            $table->string('meeting_link')->nullable();
            $table->time('lama_meeting')->nullable();
            $table->string('file_resep')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('user_dokter_id')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('user_dokter_id')->references('id')->on('users');
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
        Schema::dropIfExists('bookings');
    }
}
