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
        Schema::create('rail_bookings', function (Blueprint $table) {
            $table->id('bookID');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('pnr', 225)->nullable()->unique();;
            $table->string('bookingId', 225)->nullable()->unique();;
            $table->string('jstatus', 225)->nullable();
            $table->string('origin', 225);
            $table->string('destination', 225);
            $table->string('dtime', 20)->nullable();
            $table->string('atime', 20)->nullable();
            $table->integer('train_no');
            $table->string('train_name', 225);
            $table->string('jdate', 20)->nullable();
            $table->string('class', 10)->nullable();
            $table->boolean('isAC');
            $table->string('invoiceNo', 11);
            $table->integer('isTicket');
            $table->decimal('offer', 11, 2);
            $table->decimal('public', 11, 2);
            $table->decimal('baseFare', 11, 2);
            $table->string('currency', 5)->nullable();
            $table->decimal('offerFare', 11, 2);
            $table->decimal('publicFare', 11, 2);
            $table->decimal('agent_public_price', 11, 2);
            $table->decimal('agent_offer_price', 11, 2);
            $table->decimal('agent_commission', 11, 2);
            $table->decimal('agent_tds', 11, 2);
            $table->decimal('agent_invoice_price', 11, 2);
            $table->decimal('agent_net_receivable', 11, 2);
            $table->decimal('rail_accFare', 10, 2)->nullable();
            $table->string('book_status', 225)->nullable();
            $table->string('curr_status', 225)->nullable();
            $table->string('ttime', 10)->nullable();
            $table->integer('distance');
            $table->string('source', 225)->nullable();
            $table->integer('rail_status');
            $table->string('remark', 225)->nullable();
            $table->dateTime('updatedOn')->nullable();
            $table->dateTime('addedOn');
            $table->integer('addedBy');
            $table->integer('acceptBy')->nullable();
            $table->integer('sourceID');
            $table->string('availabl', 225)->nullable();
            $table->decimal('agent_bal', 10, 2);
            $table->decimal('agent_crit', 10, 2);
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
        Schema::dropIfExists('rail_bookings');
    }
};
