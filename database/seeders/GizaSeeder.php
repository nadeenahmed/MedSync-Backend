<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GizaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $giza = Country::where('english_name', 'Giza')->first();
        if (!$giza) {
            $giza = Country::create(['english_name' => 'Giza']);
        }

        // Insert regions for Egypt
        $arabic_region = [
            'أكتوبر',
            'حدائق أكتوبر',
            'الشيخ زايد',
            'العجوزة',
            'المهندسين',
            'الدقي',
            'البحوث',
            'أبو رواش',
            'إمبابة',
            'الكيت كات',
            'أرض اللواء',
            'الوراق',
            'بولاق الدكرور',
            'ميت عقبة',
            'حي الجيزة',
            'مركز الجيزة',
            'الهرم',
            'المريوطية',
            'ترسا',
            'فيصل',
            'المنيب',
            'حدائق الأهرام',
            'أوسيم',
            'البدرشين',
            'البراجيل',
            'الحوامدية',
            'الرماية',
            'الصحفيين',
            'الصف',
            'العزيزية',
            'العمرانية',
            'العياط',
            'المنصورية',
            'بشتيل',
            'جزيرة الدهب',
            'دهشور',
            'سفط',
            'سوميد',
            'كرداسة',
            'كفر طهرمس',
            'ناهيا'
        ];


        $english_region = [
            'October',
            'October Gardens',
            'sheikh Zayed',
            'Agoza',
            'Mohandseen',
            'Dokki',
            'Bhoos',
            'Abu Rawash',
            'Imbaba',
            'Kit Kat',
            'Ard El Lewa',
            'Al-Warraq',
            'Bulaq Dakror',
            'Meet Okba',
            'Giza district',
            'Giza Center',
            'pyramid',
            'Mariouteya',
            'Tersa',
            'Faisal',
            'Al-Munib',
            'The Pyramid gardens',
            'awseem',
            'Al-Badrasheen',
            'Barajil',
            'Hawamdiya',
            'Remaia',
            'Sahfeen',
            'El Saaf',
            'Aziziyya',
            'Umrania',
            'Ayat',
            'Mansouriya',
            'bashtil',
            'Dahab Island',
            'Dahshur',
            'Saft',
            'Sumed',
            'Kardasa',
            'Kafr Tohormos',
            'Nahya'
        ];

        foreach ($arabic_region as $index => $arabic_region) {
           // $english_region = $english_region[$index] ?? ''; 
    
            // Create the region and associate with Egypt
            Region::create([
                'arabic_name' => $arabic_region,
                'english_name' => $english_region[$index],
                'country_id' => $giza->id
            ]);
        }
    }
}
