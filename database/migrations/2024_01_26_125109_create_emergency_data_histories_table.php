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
        // Schema::create('emergency_data_histories', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('emergency_data_id');
        //     $table->foreign('emergency_data_id')->references('id')->on('emergency_data')->onDelete('cascade');
        //     $table->timestamps();
        //     $table->integer('systolic')->nullable();
        //     $table->integer('diastolic')->nullable();
        //     $table->integer('blood_sugar')->nullable();
        //     $table->integer('weight')->nullable();
        //     $table->integer('height')->nullable();
        //     $table->string('blood_type')->nullable();
        //     $table->json('chronic_diseases_bad_habits')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('emergency_data_histories');
    }
};
