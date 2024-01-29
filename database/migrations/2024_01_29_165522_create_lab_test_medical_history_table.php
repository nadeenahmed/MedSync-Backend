<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('lab_test_medical_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medical_history_id');
            $table->unsignedBigInteger('lab_test_id');
            // Any additional columns you may need in the pivot table
            $table->timestamps();

            $table->foreign('medical_history_id')->references('id')->on('medical_histories')->onDelete('cascade');
            $table->foreign('lab_test_id')->references('id')->on('lab_tests')->onDelete('cascade');
        });
    }

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_test_medical_history');
    }
};
