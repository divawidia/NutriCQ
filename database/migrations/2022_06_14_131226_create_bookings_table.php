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
            $table->string('nama_user');
            $table->string('nama_dokter')->nullable();
            $table->date('tanggal');
            $table->string('deskripsi');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('meeting_link')->nullable();
            $table->time('lama_meeting')->nullable();
            $table->string('file_resep')->nullable();
            $table->string('status');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_dokter_id');

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
