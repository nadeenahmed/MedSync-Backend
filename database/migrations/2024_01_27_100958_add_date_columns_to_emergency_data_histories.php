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
        Schema::table('emergency_data_histories', function (Blueprint $table) {
            $table->dateTime('bloodPressure_change_date')->nullable();
            $table->dateTime('bloodSugar_change_date')->nullable();
            $table->dateTime('weightHeight_change_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emergency_data_histories', function (Blueprint $table) {
            $table->dropColumn('bloodPressure_change_date');
            $table->dropColumn('bloodSugar_change_date');
            $table->dropColumn('weightHeight_change_date');
        });
    }
};
