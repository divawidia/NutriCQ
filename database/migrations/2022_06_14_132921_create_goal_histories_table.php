<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoalHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goal_histories', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_goals')->useCurrent();
            $table->double('total_air')->default(0);
            $table->double('total_energi')->default(0);
            $table->double('total_protein')->default(0);
            $table->double('total_lemak')->default(0);
            $table->double('total_karbohidrat')->default(0);
            $table->double('total_serat')->default(0);
            $table->double('total_abu')->default(0);
            $table->double('total_kalsium')->default(0);
            $table->double('total_fosfor')->default(0);
            $table->double('total_besi')->default(0);
            $table->double('total_natrium')->default(0);
            $table->double('total_kalium')->default(0);
            $table->double('total_tembaga')->default(0);
            $table->double('total_seng')->default(0);
            $table->double('total_retinol')->default(0);
            $table->double('total_b_karoten')->default(0);
            $table->double('total_karoten_total')->default(0);
            $table->double('total_thiamin')->default(0);
            $table->double('total_riboflamin')->default(0);
            $table->double('total_niasin')->default(0);
            $table->double('total_vitamin_c')->default(0);
            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('goal_histories');
    }
}
