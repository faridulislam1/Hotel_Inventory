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
        Schema::create('searchresults', function (Blueprint $table) {
            $table->id();
            $table->string('country')->nullable(); 
            $table->date('check_in')->nullable(); 
            $table->date('check_out')->nullable(); 
            $table->json('guests')->nullable(); 
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
        Schema::dropIfExists('searchresults');
    }
};
