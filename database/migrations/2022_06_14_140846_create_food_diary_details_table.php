<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodDiaryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_diary_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('food_id');
            $table->unsignedBigInteger('food_diary_id');
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
            $table->double('takaran_saji');

            $table->foreign('food_id')->references('id')->on('foods')->cascadeOnDelete();
            $table->foreign('food_diary_id')->references('id')->on('food_diaries')->cascadeOnDelete();

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
        Schema::dropIfExists('food_diary_details');
    }
}
