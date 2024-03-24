<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\MedicalCollege;
use App\Models\MedicalDegree;
use App\Models\Specialities;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SearchForDoctor extends Controller
{
    public function index()
{
    try {
        $doctors = Doctor::all();

        if ($doctors->isEmpty()) {
            return response()->json(['message' => 'No doctors found'], 404);
        }

        // Retrieve unique specialty IDs from doctors
        $medicalSpecialtyIds = $doctors->pluck('speciality_id')->unique()->toArray();

        // Prepend the "All" option to the IDs
        array_unshift($medicalSpecialtyIds, 0); // Assuming 0 represents "All"

        // Get medical specialties corresponding to unique IDs
        $medicalSpecialties = Specialities::whereIn('id', $medicalSpecialtyIds)->get();

        // Create the default "All" option
        $defaultSpecialty = [
            'english_name' => 'All',
            'arabic_name' => 'الكل',
            'photo' => null,
        ];

        // Prepend the "All" option to the medical specialties
        $medicalSpecialties->prepend($defaultSpecialty);

        foreach ($doctors as $doctor) {
            $medicalSpeciality = Specialities::find($doctor->speciality_id);
            $university = MedicalCollege::find($doctor->university_id);
            $medical_degree = MedicalDegree::find($doctor->medical_degree_id);

            $doctor["Medical Speciality"] = $medicalSpeciality
                ? [
                    "english name" => $medicalSpeciality->english_name,
                    "arabic name" => $medicalSpeciality->arabic_name,
                ]
                : null;

            $doctor["university"] = $university
                ? [
                    "english name" => $university->english_name,
                    "arabic name" => $university->arabic_name,
                ]
                : null;

            $doctor["medical Degree"] = $medical_degree
                ? [
                    "english name" => $medical_degree->english_name,
                    "arabic name" => $medical_degree->arabic_name,
                ]
                : null;
        }

        return response()->json(['medical_specialties' => $medicalSpecialties, 'doctors' => $doctors], 200);
    } catch (ModelNotFoundException $exception) {
        return response()->json(['message' => 'Doctors not found', 'error' => $exception->getMessage()], 404);
    } catch (\Exception $exception) {
        return response()->json(['message' => 'Internal Server Error', 'error' => $exception->getMessage()], 500);
    }
}



public function filterBySpecialty(Request $request)
{
    try {
        $specialtyEnglishName = str_replace('"', '', $request->input('medical_speciality_english'));
        $specialtyArabicName = str_replace('"', '', $request->input('medical_speciality_arabic'));

        // Check if the requested specialty is "All" or "الكل"
        $isAllSpecialityEnglish = in_array($specialtyEnglishName, ['All']) && empty($specialtyArabicName);
        $isAllSpecialityArabic = in_array(strtoupper($specialtyArabicName), ['الكل']) && empty($specialtyEnglishName);

        if ($isAllSpecialityEnglish || $isAllSpecialityArabic) {
            // Return all doctors
            $doctors = Doctor::all();
        } else {
            // Proceed with filtering by the requested specialty
            if (empty($specialtyEnglishName) && empty($specialtyArabicName)) {
                return response()->json(['message' => 'Medical specialty names are required'], 400);
            }

            $medicalSpecialty = Specialities::where('english_name', $specialtyEnglishName)
                ->orWhere('arabic_name', $specialtyArabicName)
                ->first();

            if (!$medicalSpecialty) {
                return response()->json(['message' => 'Medical specialty not found'], 404);
            }

            $doctors = Doctor::where('speciality_id', $medicalSpecialty->id)->get();

            if ($doctors->isEmpty()) {
                return response()->json(['message' => 'No doctors found for the specified medical specialty'], 404);
            }
        }

        foreach ($doctors as $doctor) {
            $medicalSpeciality = Specialities::find($doctor->speciality_id);
            $university = MedicalCollege::find($doctor->university_id);
            $medical_degree = MedicalDegree::find($doctor->medical_degree_id);
            $doctor["Medical Speciality"] = $medicalSpeciality
                ? [
                    "english name" => $medicalSpeciality->english_name,
                    "arabic name" => $medicalSpeciality->arabic_name,
                ]
                : null;

            $doctor["university"] = $university
                ? [
                    "english name" => $university->english_name,
                    "arabic name" => $university->arabic_name,
                ]
                : null;

            $doctor["medical Degree"] = $medical_degree
                ? [
                    "english name" => $medical_degree->english_name,
                    "arabic name" => $medical_degree->arabic_name,
                ]
                : null;
        }

        return response()->json(['doctors' => $doctors], 200);
    } catch (\Exception $exception) {
        // Handle exceptions
        return response()->json(['message' => 'Internal Server Error', 'error' => $exception->getMessage()], 500);
    }
}

    


    public function search(Request $request)
    {
        $query = $request->input('query');
        $users = User::where('name', 'like', "%$query%")->get();
        $doctorIds = $users->pluck('id');
        $doctors = Doctor::whereIn('user_id', $doctorIds)
            ->get();
        $doctorNames = $doctors->pluck('user.name');
        return response()->json(['doctors' => $doctorNames]);
    }
}
