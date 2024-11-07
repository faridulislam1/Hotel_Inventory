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
        Schema::create('hotel_bookings', function (Blueprint $table) {
            $table->id('bookID');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->bigInteger('bookingId');
            $table->string('invoice_number', 255);
            $table->string('confirmation_no', 255)->nullable();
            $table->string('booking_ref_no', 255)->nullable();
            $table->string('HCN', 255)->nullable();
            $table->string('HotelCity', 255)->nullable();
            $table->string('hotel_name', 255)->nullable();
            $table->string('checkIn', 20)->nullable();
            $table->string('checkOut', 20)->nullable();
            $table->string('jdate', 20)->nullable();
            $table->decimal('offer', 11, 2)->nullable();
            $table->decimal('public', 11, 2)->nullable();
            $table->decimal('base', 11, 2)->nullable();
            $table->decimal('base_offerFare', 11, 2)->nullable();
            $table->integer('publicFare')->nullable();
            $table->decimal('offerFare', 11, 2)->nullable();
            $table->decimal('agent_pFare', 11, 2)->nullable();
            $table->decimal('agent_oFare', 11, 2)->nullable();
            $table->decimal('agent_commission', 11, 2)->nullable();
            $table->decimal('agent_tds', 11, 2)->nullable();
            $table->decimal('agent_invoice_amount', 11, 2)->nullable();
            $table->decimal('agent_net_receivable', 11, 2)->nullable();
            $table->integer('hotel_account_fare')->nullable();
            $table->string('currency', 10)->nullable();
            $table->string('title', 50)->nullable();
            $table->string('fname', 255)->nullable();
            $table->string('lname', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('mobile', 10)->nullable();
            $table->datetime('LastCancellationDate')->nullable();
            $table->string('ChangeRequestId', 50)->nullable();
            $table->string('ChangeRequestdate', 50)->nullable();
            $table->datetime('updatedOn')->nullable();
            $table->datetime('addedOn')->nullable();
            $table->integer('addedBy')->nullable();
            $table->integer('sourceID')->nullable();
            $table->decimal('agent_bal', 10, 2)->nullable();
            $table->decimal('agent_crit', 10, 2)->default(0);
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
        Schema::dropIfExists('hotel_bookings');
    }
};
