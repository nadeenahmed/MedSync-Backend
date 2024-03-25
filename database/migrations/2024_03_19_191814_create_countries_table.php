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
        Schema::create('countries', function (Blueprint $table) {
            // $table->id();
            // $table->string('arabic_name');
            // $table->string('english_name');
            // $table->timestamps();
        });

        // $arabic_data = [
        //     'القاهرة',
        //     'الجيزة',
        //     'الإسكندرية',
        //     'أسوان',
        //     'أسيوط',
        //     'البحيرة',
        //     'بني سويف',
        //     'الدقهلية',
        //     'دمياط',
        //     'الفيوم',
        //     'الغربية',
        //     'الإسماعيلية',
        //     'كفر الشيخ',
        //     'الأقصر',
        //     'مطروح',
        //     'المنيا',
        //     'المنوفية',
        //     'شمال سيناء',
        //     'بور سعيد',
        //     'القليوبية',
        //     'قنا',
        //     'البحر الاحمر',
        //     'الشرقية',
        //     'سوهاج',
        //     'جنوب سيناء',
        //     'السويس',
        //     'طنطا'
        // ];

        // $english_data = [
        //     'Cairo',
        //     'Giza',
        //     'Alexandria',
        //     'Aswan',
        //     'Asyut',
        //     'Elbehera',
        //     'Bani Sweif',
        //     'Dakahlia',
        //     'Damietta',
        //     'Fayoum',
        //     'Gharbia',
        //     'Ismailia',
        //     'Kafr El-Sheikh',
        //     'Oqsur',
        //     'Matrooh',
        //     'Minya',
        //     'Menoufia',
        //     'North Sinai',
        //     'Port Said',
        //     'Qalyubia',
        //     'Qna',
        //     'The Red Sea',
        //     'Sharqia',
        //     'Sohag',
        //     'South of Sinaa',
        //     'Suez',
        //     'Tanta'
        // ];

        // foreach ($arabic_data as $index => $arabic_countries) {
        //     DB::table('countries')->insert([
        //         'arabic_name' => $arabic_countries,
        //         'english_name' => $english_data[$index],
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
        // Schema::dropIfExists('countries');
    }
};
