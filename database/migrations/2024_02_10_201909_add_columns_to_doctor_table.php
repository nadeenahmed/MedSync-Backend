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
        Schema::table('doctors', function (Blueprint $table) {
            $table->unsignedBigInteger('speciality_id'); 
            $table->foreign('speciality_id')->references('id')->on('specialities');
            $table->integer('years_of_experience')->nullable();
            $table->string('medical_degree');
            $table->string('university');               
            $table->string('medical_board_organization')->nullable();
            $table->string('licence_information'); 
            $table->string('gender')->nullable(); 
            $table->string('phone')->nullable(); 
            $table->string('profile_image')->nullable(); 
            //$table->string('graduation_certification'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            //
        });
    }
};
