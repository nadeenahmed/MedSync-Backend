<?php

namespace Database\Seeders;

use App\Models\Specialities;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $imageDirectory = public_path('Specialities-photos');
        $specialities = Specialities::all();

        // Iterate over each speciality and update its photo
        foreach ($specialities as $speciality) {
            // Assuming each speciality has a unique identifier (e.g., ID)
            $imagePath = $imageDirectory . '/' . $speciality->id . '.jpg'; // Adjust file extension if necessary

            // Check if the image file exists
            if (file_exists($imagePath)) {
                // Update the photo column with the image path
                $speciality->update(['photo' => 'storage/Specialities-photos/' . $speciality->id . '.jpg']);
            }
        }
    }

}
