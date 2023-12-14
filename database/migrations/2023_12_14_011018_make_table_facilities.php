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
            Schema::table('medical_facilities', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }
    
        public function down()
        {
            Schema::table('medical_facilities', function (Blueprint $table) {
                $table->dropColumn('id');
                $table->dropTimestamps();
            });
        }
};
