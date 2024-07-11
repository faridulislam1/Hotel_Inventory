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
        Schema::create('state_county_provs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agency_info_id');
            $table->foreign('agency_info_id')->references('id')->on('addresses');
            $table->string('StateCode');
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
        Schema::dropIfExists('state_county_provs');
    }
};
