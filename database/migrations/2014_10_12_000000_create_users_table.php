<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable(); 
            $table->string('company_persons_name')->nullable(); 
            $table->string('country')->nullable(); 
            $table->string('division')->nullable(); 
            $table->string('district')->nullable(); 
            $table->string('contact_no')->nullable(); 
            $table->string('address')->nullable(); 
            $table->string('currency')->default('BDT'); 
            $table->string('emailID')->unique(); 
            $table->decimal('balance')->default(0.00); 
            $table->decimal('credit_balance')->default(0.00); 
            $table->string('status')->default('active'); 
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
