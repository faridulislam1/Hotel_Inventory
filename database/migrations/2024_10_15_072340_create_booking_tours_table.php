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
        Schema::create('booking_tours', function (Blueprint $table) {
            $table->id('bookID');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('tourID')->nullable();
            $table->integer('bookingId')->nullable();
            $table->string('invoice_number', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->integer('star')->nullable();
            $table->integer('tot_room')->nullable();
            $table->string('currency', 10)->nullable();
            $table->string('date_from', 100)->nullable();
            $table->string('date_to', 100)->nullable();
            $table->string('lead_pass_name', 100)->nullable();
            $table->string('lead_pass_email', 100)->nullable();
            $table->string('lead_pass_country', 100)->nullable();
            $table->string('lead_pass_mobile', 100)->nullable();
            // Address fields
            $table->string('default_act1', 100)->nullable();
            $table->string('default_act2', 100)->nullable();
            $table->string('default_act3', 100)->nullable();
            $table->string('default_act4', 100)->nullable();
            $table->string('default_act5', 100)->nullable();
            // Add-ons
            $table->string('add_act1', 100)->nullable();
            $table->string('add_act2', 100)->nullable();
            $table->string('add_act3', 100)->nullable();
            $table->string('add_act4', 100)->nullable();
            $table->string('add_act5', 100)->nullable();
            // Adult fields
            for ($i = 0; $i <= 19; $i++) {
                $table->string('adult' . $i, 100)->nullable();
            }
            // Child fields
            for ($i = 0; $i <= 14; $i++) {
                $table->string('child' . $i, 100)->nullable();
            }
            // Pricing details
            $table->boolean('isTour')->default(0);
            $table->decimal('offer', 11, 2)->nullable();
            $table->decimal('public', 11, 2)->default(0.00);
            $table->decimal('baseFare', 11, 2)->nullable();
            $table->decimal('offerFare', 11, 2)->nullable();
            $table->decimal('publicFare', 11, 2)->nullable();
            $table->decimal('agent_public_price', 11, 2)->nullable();
            $table->decimal('agent_offer_price', 10, 2)->nullable();
            $table->decimal('agent_commission', 10, 2)->nullable();
            $table->decimal('agent_tds', 10, 2)->nullable();
            $table->decimal('agent_invoice_price', 10, 2)->nullable();
            $table->decimal('agent_net_receivable', 10, 2)->nullable();
            // Cancellation and status fields
            $table->string('cancel_status', 100)->nullable();
            $table->datetime('cancel_date')->nullable();
            // Timestamps
            $table->datetime('updatedOn')->nullable();
            $table->datetime('addedOn')->nullable();
            $table->integer('addedBy')->nullable();
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
        Schema::dropIfExists('booking_tours');
    }
};
