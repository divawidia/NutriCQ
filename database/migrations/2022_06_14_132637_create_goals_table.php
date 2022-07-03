<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->double('total_air');
            $table->double('total_energi');
            $table->double('total_protein');
            $table->double('total_lemak');
            $table->double('total_karbohidrat');
            $table->double('total_serat');
            $table->double('total_abu');
            $table->double('total_kalsium');
            $table->double('total_fosfor');
            $table->double('total_besi');
            $table->double('total_natrium');
            $table->double('total_kalium');
            $table->double('total_tembaga');
            $table->double('total_seng');
            $table->double('total_retinol');
            $table->double('total_b_karoten');
            $table->double('total_karoten_total');
            $table->double('total_thiamin');
            $table->double('total_riboflamin');
            $table->double('total_niasin');
            $table->double('total_vitamin_c');
            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
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
        Schema::dropIfExists('goals');
    }
}
