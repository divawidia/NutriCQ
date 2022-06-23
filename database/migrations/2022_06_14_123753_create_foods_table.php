<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sumber');
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
            $table->double('berat_per_takaran_saji');
            //$table->unsignedBigInteger('category_id');
            $table->timestamps();

            //$table->foreign('category_id')->references('id')->on('food_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('foods');
    }
}
