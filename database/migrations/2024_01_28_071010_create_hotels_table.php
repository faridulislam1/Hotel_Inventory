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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->string('hotel')->unique();
            $table->string('embed_code');
            $table->string('landmark');
            $table->string('rating');
            $table->text('Single_image');
            $table->text('multiple_image');  
            $table->string('address');
            $table->string('highlights');
            $table->longText('long_decription');
            $table->string('currency');
            $table->text('term_condition');
            $table->string('longitude');
            $table->string('litetitude');
            $table->string('facilities');

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
        Schema::dropIfExists('hotels');
    }
};
