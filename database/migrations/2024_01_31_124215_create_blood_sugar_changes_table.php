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
        // Schema::create('blood_sugar_changes', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('emergency_data_id');
        //     $table->foreign('emergency_data_id')->references('id')->on('emergency_data')->onDelete('cascade');
        //     $table->string('time')->nullable();
        //     $table->string('date')->nullable();
        //     $table->integer('blood_sugar')->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('blood_sugar_changes');
    }
};
