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
        // Schema::table('workplaces', function (Blueprint $table) {
        //     $table->unsignedBigInteger('country_id')->nullable(); 
        //     $table->unsignedBigInteger('region_id')->nullable(); 
        //     $table->foreign('country_id')->references('id')->on('countries');
        //     $table->foreign('region_id')->references('id')->on('regions');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('workplaces', function (Blueprint $table) {
        //     //
        // });
    }
};
