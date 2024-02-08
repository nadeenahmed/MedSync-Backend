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
        // Schema::create('medical_histories', function (Blueprint $table) {
        //         $table->id();
        //         $table->unsignedBigInteger('patient_id');
        //         $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        //         $table->unsignedBigInteger('medical_speciality_id'); 
        //         $table->foreign('medical_speciality_id')->references('id')->on('specialities');
        //         $table->string('notes')->nullable();
        //         $table->unsignedBigInteger('diagnosis_id'); 
        //         $table->foreign('diagnosis_id')->references('id')->on('diagnoses');
        //         $table->timestamps();
        
        //         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('medical_histories');
    }
};
