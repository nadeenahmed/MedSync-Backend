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
        Schema::create('emergency_data', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('patient_id');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->integer('high_blood_pressure')->nullable();
            $table->integer('low_blood_pressure')->nullable();
            $table->integer('high_sugar')->nullable();
            $table->integer('low_sugar')->nullable();
            $table->integer('weight')->nullable();
            $table->integer('height')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('chronic_diseases')->nullable();
            $table->string('bad_habits')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_data');
    }
};
