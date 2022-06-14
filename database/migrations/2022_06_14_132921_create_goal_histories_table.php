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
            $table->date('tanggal_goals');
            $table->double('air');
            $table->double('energi');
            $table->double('protein');
            $table->double('lemak');
            $table->double('karbohidrat');
            $table->double('serat');
            $table->double('abu');
            $table->double('kalsium');
            $table->double('fosfor');
            $table->double('besi');
            $table->double('natrium');
            $table->double('kalium');
            $table->double('tembaga');
            $table->double('seng');
            $table->double('retinol');
            $table->double('b_karoten');
            $table->double('karoten_total');
            $table->double('thiamin');
            $table->double('riboflamin');
            $table->double('niasin');
            $table->double('vitamin_c');
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
