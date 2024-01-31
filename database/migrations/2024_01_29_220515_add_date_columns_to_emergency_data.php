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
            $table->date('bloodPressure_change_date')->nullable();
            $table->Time('bloodPressure_change_time')->nullable();
            $table->date('bloodSugar_change_date')->nullable();
            $table->Time('bloodSugar_change_time')->nullable();
            $table->date('weightHeight_change_date')->nullable();
            $table->Time('weightHeight_change_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emergency_data', function (Blueprint $table) {
            //
        });
    }
};
