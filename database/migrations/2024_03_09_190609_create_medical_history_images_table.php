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
        Schema::create('medical_history_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medical_history_id');
            $table->string('image_path');
            $table->timestamps();
            $table->foreign('medical_history_id')->references('id')->on('medical_histories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_history_images');
    }
};
