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
        // Schema::create('diagnosis_medical_history', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('diagnosis_id');
        //     $table->unsignedBigInteger('medical_history_id');
        //     $table->timestamps();
        //     $table->foreign('diagnosis_id')->references('id')->on('diagnoses')->onDelete('cascade');
        //     $table->foreign('medical_history_id')->references('id')->on('medical_histories')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('diagnosis_medical_history');
    }
};
