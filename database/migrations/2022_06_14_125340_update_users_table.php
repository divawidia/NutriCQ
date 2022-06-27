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
            $table->date('tgl_lahir')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('gender')->nullable();
            $table->string('cv')->nullable();
            $table->string('license')->nullable();
            $table->integer('tinggi_badan')->nullable();
            $table->integer('berat_badan')->nullable();
            $table->integer('tingkat_aktivitas')->nullable();
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
