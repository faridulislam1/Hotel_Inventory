<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('create_passenger_name_record_r_q_s', function (Blueprint $table) {
            $table->id();
            $table->string('version');
            $table->string('targetCity');
            $table->boolean('haltOnAirPriceError');
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
        Schema::dropIfExists('create_passenger_name_record_r_q_s');
    }
};
