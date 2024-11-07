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
        Schema::create('visa_bookings', function (Blueprint $table) {
            $table->id('bookID'); // bookID is your primary key
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('ref_no', 225);
            $table->string('country', 225);
            $table->string('visa_type', 225);
            $table->string('entry_type', 225)->nullable();
            $table->string('visa_duration', 225)->nullable();
            $table->string('processing_duration', 225)->nullable();
            $table->string('visa_charge', 225)->nullable();
            $table->longText('require_document')->nullable();
            $table->date('jstatus')->nullable();
            $table->string('visa_status', 30)->nullable();
            $table->date('delivary_date')->nullable();
            $table->integer('acceptBy')->nullable();
            $table->string('remark', 225)->nullable();
            $table->timestamp('updatedOn')->nullable();
            $table->timestamp('addedOn')->nullable();
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
        Schema::dropIfExists('visa_bookings');
    }
};
