<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('tgl_lahir');
            $table->string('no_telp');
            $table->string('gender');
            $table->string('cv');
            $table->string('license');
            $table->integer('tinggi_badan');
            $table->integer('berat_badan');
            $table->integer('tingkat_aktivitas');
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
            $table->dropColumn('tgl_lahir');
            $table->dropColumn('no_telp');
            $table->dropColumn('gender');
            $table->dropColumn('cv');
            $table->dropColumn('license');
            $table->dropColumn('tinggi_badan');
            $table->dropColumn('berat_badan');
            $table->dropColumn('tingkat_aktivitas');
        });
    }
}