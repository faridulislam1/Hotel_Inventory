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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            //$table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->on('users')->onDelete('cascade'); 

            // Adding all the fields from the database
            $table->string('contactNo', 255)->nullable();
            $table->string('contactNo2', 255)->nullable();
            $table->string('IP', 50)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('cityCode', 255)->nullable();
            $table->string('countryCode', 255)->nullable();
            $table->string('addressLine1', 255)->nullable();
            $table->string('addressLine2', 255)->nullable();
            $table->string('pnr', 255)->nullable()->unique();
            $table->string('bookingId', 255)->unique();
            $table->tinyInteger('isDomestic')->default(0);
            $table->string('origin', 64)->nullable();
            $table->string('destination', 64)->nullable();
            $table->string('airlineCode', 64)->nullable();
            $table->longText('airlineRemark')->nullable();
            $table->string('isLCC', 20)->nullable();
            $table->tinyInteger('nonRefundable')->default(0);
            $table->string('invoiceAmount', 64)->nullable();
            $table->string('invoiceNo', 255)->nullable();
            $table->string('invoiceCreatedOn', 255)->nullable();
            $table->string('fareRules', 255)->nullable();
            $table->string('errorCode', 255)->nullable();
            $table->string('errorMessage', 255)->nullable();
            $table->tinyInteger('isTicket')->default(0);
            $table->longText('hold')->nullable();
            $table->decimal('offer', 11, 2)->default(0.00);
            $table->decimal('public', 11, 2)->default(0.00);
            $table->decimal('baseFare', 11, 2)->default(0.00);
            $table->decimal('otherFare', 11, 2)->default(0.00);
            $table->decimal('offerFare', 11, 2)->default(0.00);
            $table->decimal('publicFare', 11, 2)->default(0.00);
            $table->decimal('agent_public_price', 10, 2)->nullable();
            $table->decimal('agent_offer_price', 10, 2)->nullable();
            $table->decimal('agent_commission', 10, 2)->nullable();
            $table->decimal('agent_tds', 10, 2)->nullable();
            $table->decimal('agent_invoice_price', 10, 2)->nullable();
            $table->decimal('agent_net_receivable', 10, 2)->nullable();
            $table->decimal('account_fare', 10, 2)->nullable();
            $table->decimal('my_net_receivable', 10, 2)->nullable();
            $table->string('jdate', 255)->nullable();
            $table->string('cancelID', 30)->nullable();
            $table->string('voidID', 30)->nullable();
            $table->integer('RefID')->nullable();
            $table->integer('ReissueID')->nullable();
            $table->string('SsrID', 225)->nullable();
            $table->string('TicketId', 30)->nullable();
            $table->string('CreditNoteNo', 225)->nullable();
            $table->integer('ChangeRequestStatus')->nullable();
            $table->dateTime('updatedOn')->nullable();
            $table->dateTime('addedOn')->nullable();
            $table->integer('addedBy')->nullable();
            $table->string('agntCurrency', 10)->nullable();
            $table->string('agency', 225)->nullable();
            $table->integer('acceptBy')->nullable();
            $table->string('lead_pax', 225)->nullable();
            $table->string('surname', 225)->nullable();
            $table->integer('ppr')->default(0);
            $table->integer('partial_pay')->nullable();
            $table->integer('due_amt')->nullable();
            $table->string('request_for', 100)->nullable();
            $table->dateTime('ordertime')->nullable();
            $table->dateTime('updatetime')->nullable();
            $table->string('copy', 225)->nullable();
            $table->integer('sourceID')->nullable();
            $table->string('void_pass', 100)->nullable();
            $table->string('cancel_pass', 100)->nullable();
            $table->string('cancel_leg', 100)->nullable();
            $table->string('refund_pass', 100)->nullable();
            $table->string('refund_leg', 100)->nullable();
            $table->string('reissue_pass', 100)->nullable();
            $table->string('ssr_leg', 225)->nullable();
            $table->string('ssr_pass', 225)->nullable();
            $table->string('sb_bag', 225)->nullable();
            $table->longText('remark_book')->nullable();
            $table->string('pcc_currency', 10)->nullable();
            $table->string('flt_class', 10)->nullable();
            $table->string('pass_copy', 225)->nullable();
            $table->integer('refund_point')->default(0);
            $table->integer('reissue_point')->default(0);
            $table->bigInteger('passID')->nullable();
            $table->decimal('agent_bal', 10, 2)->nullable();
            $table->decimal('agent_crit', 10, 2)->nullable();
            $table->integer('subID')->nullable();
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
        Schema::dropIfExists('bookings');
    }
};
