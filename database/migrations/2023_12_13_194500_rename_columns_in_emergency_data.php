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
        Schema::table('emergency_data', function (Blueprint $table) {
            $table->renameColumn('high_blood_pressure', 'systolic');
            $table->renameColumn('low_blood_pressure', 'diastolic');
            $table->renameColumn('sugar', 'blood_sugar');
            
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emergency_data', function (Blueprint $table) {
            $table->renameColumn('high_blood_pressure', 'systolic');
            $table->renameColumn('low_blood_pressure', 'diastolic');
            $table->renameColumn('sugar', 'blood_sugar');
        });
    }
};
