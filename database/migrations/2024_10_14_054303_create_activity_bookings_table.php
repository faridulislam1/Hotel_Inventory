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
        Schema::create('activity_bookings', function (Blueprint $table) {
            $table->id('bookID');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('actID');
            $table->integer('bookingId');
            $table->string('invoice_number');
            $table->string('country', 100);
            $table->string('currency', 100);
            $table->string('date_from', 15);
            $table->string('lead_pass_name', 100);
            $table->string('lead_pass_email', 100)->nullable();
            $table->string('lead_pass_country', 50);
            $table->string('lead_pass_mobile', 100)->nullable();
            // Adult fields (nullable)
            $table->string('adult0', 100)->nullable();
            $table->string('adult1', 100)->nullable();
            $table->string('adult2', 100)->nullable();
            $table->string('adult3', 100)->nullable();
            $table->string('adult4', 100)->nullable();
            $table->string('adult5', 100)->nullable();
            $table->string('adult6', 100)->nullable();
            $table->string('adult7', 100)->nullable();
            $table->string('adult8', 100)->nullable();
            $table->string('adult9', 100)->nullable();
            $table->string('adult10', 100)->nullable();
            // Child fields (nullable)
            $table->string('child0', 100)->nullable();
            $table->string('child1', 100)->nullable();
            $table->string('child2', 100)->nullable();
            $table->string('child3', 100)->nullable();
            $table->string('child4', 100)->nullable();
            $table->string('child5', 100)->nullable();
            $table->string('child6', 100)->nullable();
            $table->string('child7', 100)->nullable();
            $table->string('child8', 100)->nullable();
            // Infant fields (nullable)
            $table->string('infant0', 100)->nullable();
            $table->string('infant1', 100)->nullable();
            $table->string('infant2', 100)->nullable();
            $table->string('infant3', 100)->nullable();
            $table->string('infant4', 100)->nullable();
            $table->string('infant5', 100)->nullable();
            $table->string('infant6', 100)->nullable();
            $table->string('infant7', 100)->nullable();
            $table->string('infant8', 100)->nullable();

            // Other details
            $table->tinyInteger('isTour')->default(0);
            $table->decimal('offer', 11, 2)->default(0.00);
            $table->decimal('public', 11, 2)->default(0.00);
            $table->decimal('baseFare', 11, 2)->default(0.00);
            $table->decimal('offerFare', 11, 2)->default(0.00);
            $table->decimal('publicFare', 11, 2)->default(0.00);
            $table->decimal('agent_public_price', 10, 2)->nullable();
            $table->decimal('agent_offer_price', 10, 2)->nullable();
            $table->decimal('agent_commission', 10, 2)->nullable();
            $table->decimal('agent_tds', 10, 2)->nullable();
            $table->decimal('agent_invoice_amount', 10, 2)->nullable();
            $table->decimal('agent_net_receivable', 10, 2)->nullable();
            
            // Dates and status
            $table->string('cancel_date', 15)->nullable();
            $table->string('cancel_status', 15)->nullable();
            $table->datetime('updatedOn')->nullable();
            $table->datetime('addedOn')->nullable();

            // References and additional info
            $table->integer('addedBy');
            $table->integer('agent_id')->nullable();
            $table->string('flight_info', 100)->nullable();
            $table->string('flight_ticket', 100)->nullable();
            $table->string('remarks')->nullable();
            $table->string('new_travel_date', 100)->nullable();
            $table->string('new_pickup_location')->nullable();
            $table->tinyInteger('admin_status')->nullable();
            $table->string('refund_amount')->nullable();
            $table->integer('vendor')->nullable();
            $table->string('services', 80)->nullable();
            $table->decimal('net_cost', 10, 2)->nullable();
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
        Schema::dropIfExists('activity_bookings');
    }
};
