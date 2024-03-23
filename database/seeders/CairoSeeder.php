<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CairoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cairo = Country::where('english_name', 'Cairo')->first();
        if (!$cairo) {
            $cairo = Country::create(['english_name' => 'Cairo']);
        }

        // Insert regions for Egypt
        $arabic_region = [          
            'وسط البلد',
            'الزمالك',
            'العباسية',
            'النزهة',
            'مدينة نصر',
            'القاهرة الجديدة',
            'هليوبوليس الجديدة',
            'بدر',
            'العبور',
            'الشروق',
            'مدينتى',
            'العاشر من رمضان',
            'مدينة بنها',
            'العاصمة الإدارية الجديدة',
            'التجمع الأول',
            'التجمع الثالث',
            'التجمع الخامس',
            'البساتين',
            'التبين',
            'التونسى',
            'الجزيرة',
            'الجمالية',
            'الحسين',
            'الحلمية',
            'الحلمية الجديدة',
            'الخليفة',
            'الدرب الأحمر',
            'الدويقة',
            'الزاوية الحمراء',
            'الزيتون',
            'الساحل',
            'شبرا',
            'الشرابية',
            'روض الفرج',
            'السلام',
            'السيدة زينب',
            'الصاغة',
            'الفجالة',
            'القطامية',
            'اللوتس',
            'المرج',
            'المستثمرين الجنوبية',
            'المستثمرين الشمالية',
            'المطرية',
            'المقطم',
            'المنيرة',
            'المنيرة الجديدة',
            'الموسكى',
            'الوايلي',
            'باب الشعرية',
            'باب اللوق',
            'بولاق أبو العلا',
            'ثكنات المعادى',
            'حدائق المعادى',
            'المعادى',
            'جاردن سيتى',
            'جزيرة الروضة',
            'جسر السويس',
            'حى السفارات',
            'حدائق القبة',
            'حى الخليفة',
            'دار السلام',
            'سراى القبة',
            'عابدين',
            'عبود',
            'قصر النيل',
            'كوتسيكا',
            'مدينة البنفسج',
            'مدينة الرحاب',
            'مدينة النرجس',
            'مدينة الياسمين',
            'مدينة نصر',
            'مساكن شيراتون المطار',
            'مصر الجديدة',
            'مصر القديمة',
            'منشية ناصر', 
        ];

        $english_region = [
            'downtown',
            'Zamalek',
            'Abbasiya',
            'El Nozha',
            'Nasr City',
            'New Cairo',
            'New Heliopolis',
            'Badr',
            'Al Abour',
            'Al Shorouk',
            'Madinaty',
            '10th of Ramadan city',
            'Banha City',
            'New Administrative Capital',
            'The First Settlement',
            'The 3th Settlement',
            'The 5th Settlement',
            'El Basatin',
            'Al-Tabin',
            'Al-Tunisia',
            'Al Jazeera',
            'El-Gamaleya',
            'ELHussein',
            'Al-Helmiya',
            'Al-Helmiya Algdida',
            'Caligh Al-Maamun',
            'Al-Darb Al-Ahmar',
            'Duwaiqa',
            'El Zawya El Hamraa',
            'El Zaitoun',
            'El Sahel',
            'Shubra',
            'Sharabiya',
            'Rod El-Farag',
            'As-Salam',
            'Al Sayeda Zeinab',
            'El Sagha',
            'Fajjala',
            'Katameya',
            'lotus',
            'Al Marj',
            'South Investors Area',
            'North Investors Area',
            'El Matariyya',
            'El Mokattam',
            'Al-Mounira',
            'New Mounira',
            'Musky',
            'Al-Waili',
            'Bab El Sharia',
            'Bab Al-Luq',
            'Bulaq Abu Al-Ela',
            'Sakanat El Maadi',
            'Maadi Gardens',
            'Maadi',
            'garden City',
            'Rhoda Island',
            'Geser El-Swies',
            'Al Safarat',
            'Kobba Gardens',
            'Al-Khalifa District',
            'Dar AISalaam',
            'Saray Al-Qubba',
            'Abdeen',
            'Abboud',
            'Kasr Al-Nile',
            'kotsika',
            'El-Banafseg',
            'Al Rehab',
            'Al Narges',
            'El yasmine',
            'Nasr City',
            'Sheraton Airport Residences',
            'Heliopolis',
            'Ancient Egypt',
            'Mansheyat Nasser',
        ];

       

        
        foreach ($arabic_region as $index => $arabic_region) {
            Region::create([
                'arabic_name' => $arabic_region,
                'english_name' => $english_region[$index],
                'country_id' => $cairo->id
            ]);
        }
    }
}
