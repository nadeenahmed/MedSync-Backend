<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medical_degrees', function (Blueprint $table) {
            // $table->id();
            // $table->string('arabic_name')->nullable();
            // $table->string('english_name')->nullable();
            // $table->timestamps();
        });

        // $arabic_data = [
        //     'طبيب الامتياز/ طبيب متدرب/ طبيب متمرن',
        //     'طبيب مقيم',
        //     'أخصائي',
        //     'أخصائي أول',
        //     'إستشاري',
        //     'استشاري أول',
        //     'دكتوراه / بروفيسور',


        // ];

        // $data = [
        //     'Intern',
        //     'Resident',
        //     'Specialist',
        //     'Senior Specialist',
        //     'Consultant',
        //     'Senior Consultant',
        //     'Professor',
        // ];

        // foreach ($arabic_data as $index => $arabic_college) {
        //     DB::table('medical_degrees')->insert([
        //         'arabic_name' => $arabic_college,
        //         'english_name' => $data[$index],
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('medical_degrees');
    }
};
