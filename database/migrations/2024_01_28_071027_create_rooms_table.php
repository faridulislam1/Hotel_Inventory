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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained(); // Assuming foreign key relationship
            $table->integer('city_id');
            $table->string('room_num');
            $table->string('available_capacity');
            $table->string('max_capacity');
            $table->string('refundable');
            $table->string('non_refundable');
            $table->string('refundable_break');
            $table->string('refundable_nonbreak');
            $table->string('extra_bed')->nullable();
            $table->string('room_size');
            $table->string('bed_type')->nullable();
            $table->string('cancellation_policy');
            $table->string('room_available');
            $table->string('room_facilities')->nullable();
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
        Schema::dropIfExists('rooms');
    }
};
