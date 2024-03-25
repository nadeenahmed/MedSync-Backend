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
            // $table->string('bloodPressure_change_date')->change();
            // $table->string('bloodPressure_change_time')->change();
            // $table->string('bloodSugar_change_date')->change();
            // $table->string('bloodSugar_change_time')->change();
            // $table->string('weightHeight_change_date')->change();
            // $table->string('weightHeight_change_time')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emergency_data', function (Blueprint $table) {
            // $table->date('bloodPressure_change_date')->change();
            // $table->time('bloodPressure_change_time')->change();
            // $table->date('bloodSugar_change_date')->change();
            // $table->time('bloodSugar_change_time')->change();
            // $table->date('weightHeight_change_date')->change();
            // $table->time('weightHeight_change_time')->change();
        });
    }
};
