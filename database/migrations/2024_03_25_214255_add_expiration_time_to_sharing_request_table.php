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
        Schema::table('sharing_requests', function (Blueprint $table) {
            $table->integer('sharing_duration')->nullable();
            $table->timestamp('expiration_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sharing_requests', function (Blueprint $table) {
            //
        });
    }
};
