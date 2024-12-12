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
            $table->string('room_type')->unique();
            $table->integer('available_capacity');
            $table->integer('max_capacity');
            $table->integer('room_available');
            $table->integer('refundable');
            $table->integer('non_refundable');
            $table->integer('refundable_break');
            $table->integer('refundable_nonbreak');
            $table->integer('extra_bed')->nullable();
            $table->integer('room_size');
            $table->string('bed_type')->nullable();
            $table->string('cancellation_policy');
            $table->string('room_facilities')->nullable();
            $table->json('inventory')->nullable();
            $table->json('sales')->nullable();
            $table->integer('total_rooms')->nullable();
            $table->integer('allocated_online_inventory')->nullable();
            $table->integer('allocated_offline_inventory')->nullable();
            $table->integer('online_sold')->nullable();
            $table->integer('offline_sold')->nullable();

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
