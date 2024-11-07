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
        Schema::create('passengers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bookings_id'); // Add bookings_id column
            $table->foreign('bookings_id')->references('id')->on('bookings')->onDelete('cascade'); // Set up foreign key
            $table->string('title');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender')->nullable();
            $table->string('nationality')->nullable();
            $table->string('email')->nullable();
            $table->string('number')->nullable();
            $table->string('passport_num')->nullable();
            $table->date('passport_expiry_date')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('type'); // Type of passenger (adult, infant, child)
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
        Schema::dropIfExists('passengers');
    }
};
