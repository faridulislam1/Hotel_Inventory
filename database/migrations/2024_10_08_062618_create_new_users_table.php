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
        Schema::create('new_users', function (Blueprint $table) {
            $table->id();
            $table->integer('groupID')->nullable();
            $table->string('name')->nullable();
            $table->string('agency_ID')->nullable();
            $table->string('emailID')->nullable();
            $table->string('password')->nullable();
            $table->string('salt')->nullable();
            $table->string('mobile')->nullable();
            $table->integer('offID')->nullable();
            $table->integer('packID')->nullable();
            $table->integer('hpackID')->nullable();
            $table->integer('tpackID')->nullable();
            $table->integer('rpackID')->nullable();
            $table->integer('apackID')->nullable();
            $table->integer('buspackID')->nullable();
            $table->integer('shohoz')->nullable();
            $table->integer('bdticket')->nullable();
            $table->string('countryCode')->nullable();
            $table->string('country')->nullable();
            $table->string('division')->nullable();
            $table->string('contactPerson')->nullable();
            $table->string('address')->nullable();
            $table->string('logo')->nullable();
            $table->string('currency')->nullable();
            $table->decimal('balance')->nullable();
            $table->integer('credit_balance')->nullable();
            $table->date('validity_date')->nullable();
            $table->integer('credit_bal_status')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->integer('tbo')->nullable();
            $table->integer('bdapi')->nullable();
            $table->tinyInteger('showfare')->nullable();
            $table->datetime('addedOn')->nullable();
            $table->integer('addedBy')->nullable();
            $table->string('zip')->nullable();
            $table->string('staff_name')->nullable();
            $table->datetime('balace_update_date')->nullable();
            $table->integer('balance_addedBy')->nullable();
            $table->decimal('balance_amount')->nullable();
            $table->datetime('loginData')->nullable();
            $table->bigInteger('scount')->nullable();
            $table->string('user_status')->nullable();
            $table->integer('loginOtp')->nullable();
            $table->string('secret_key')->nullable();
            $table->integer('our_member')->nullable();
            

            $table->timestamp('email_verified_at')->nullable();
            //$table->string('password');
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
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
        Schema::dropIfExists('new_users');
    }
};
