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

                $table->json('clinic_addresses')->nullable()->change();
                $table->json('clinic_phones')->nullable()->change();
                $table->string('license_path')->nullable();
                
                degree: The medical degree obtained by the professional.
university: The university from which the professional graduated.
board_certified: Boolean indicating whether the professional is board-certified.
certifying_board: 
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
