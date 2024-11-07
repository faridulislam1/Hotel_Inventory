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
        Schema::create('booking_agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('agent');
            $table->integer('amount');
            $table->string('currency', 3);
            $table->string('pnr', 50);
            $table->string('bookingID', 50);
            $table->string('flight_no', 50);
            $table->string('tot_time', 50);
            $table->string('origin', 100);
            $table->string('date_time_orig', 100);
            $table->string('destination', 100);
            $table->string('date_time_dest', 100);
            $table->string('layover', 100)->nullable();
            $table->string('layover_airport', 255)->nullable();
            $table->string('date_time_layover', 100)->nullable();
            $table->integer('isLCC');
            $table->integer('Refundable');
            $table->integer('journey_type');
            $table->integer('adult');
            $table->integer('child')->nullable();
            $table->integer('infant')->nullable();
            $table->string('ptitle', 10);
            $table->string('pfname', 255);
            $table->string('plname', 255);
            $table->integer('gender');
            $table->string('dob', 10);
            $table->string('passportno', 50);
            $table->string('passportexpdate', 50);
            $table->string('contactNo', 20);
            $table->string('email', 255)->nullable();
            $table->string('cityCode', 255)->nullable();
            $table->string('countryCode', 255)->nullable();
            $table->string('addressLine1', 255)->nullable();
            $table->string('addressLine2', 255)->nullable();
            $table->timestamp('updatedOn')->nullable();
            $table->timestamp('addedOn')->nullable();
            $table->integer('addedBy');
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
        Schema::dropIfExists('booking_agents');
    }
};
