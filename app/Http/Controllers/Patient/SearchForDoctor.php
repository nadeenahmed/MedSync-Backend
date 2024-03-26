<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Doctor;
use App\Models\MedicalCollege;
use App\Models\MedicalDegree;
use App\Models\Patient;
use App\Models\Region;
use App\Models\Specialities;
use App\Models\User;
use App\Models\Workplace;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SearchForDoctor extends Controller
{
    public function GetAllDoctors(Request $request)
    {
        try {
            $user = $request->user();
            $patient = Patient::where('user_id', $user->id)->first();
            if (!$patient) {
                return response()->json(['errors' => 'Patient not found'], 404);
            }
            $doctors = Doctor::all();
            if ($doctors->isEmpty()) {
                return response()->json(['message' => 'No doctors found'], 404);
            }
            $medicalSpecialtyIds = $doctors->pluck('speciality_id')->unique()->toArray();
            array_unshift($medicalSpecialtyIds, 0);
            $medicalSpecialties = Specialities::whereIn('id', $medicalSpecialtyIds)->get();
            $defaultSpecialty = [
                'english_name' => 'All',
                'arabic_name' => 'الكل',
                'photo' => null,
            ];
            $medicalSpecialties->prepend($defaultSpecialty);

            foreach ($doctors as $doctor) {
                $user = User::where('id', $doctor->user_id)->first();
                $medicalSpeciality = Specialities::find($doctor->speciality_id);
                $university = MedicalCollege::find($doctor->university_id);
                $medical_degree = MedicalDegree::find($doctor->medical_degree_id);
                $doctorName = $user->name;
                $doctor->user = $user ?: null;
                $doctor["Doctor Name"] = 'Dr.' . $doctorName;
                $doctor["Doctor Reviews"] = 0;
                $doctor["Doctor Rate"] = 0;
                $doctor["Patients"] = 0;
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
            $user = $request->user();
            $patient = Patient::where('user_id', $user->id)->first();
            if (!$patient) {
                return response()->json(['errors' => 'Patient not found'], 404);
            }
            $specialtyEnglishName = str_replace('"', '', $request->input('medical_speciality_english'));
            $specialtyArabicName = str_replace('"', '', $request->input('medical_speciality_arabic'));
            $isAllSpecialityEnglish = in_array($specialtyEnglishName, ['All']) && empty($specialtyArabicName);
            $isAllSpecialityArabic = in_array(strtoupper($specialtyArabicName), ['الكل']) && empty($specialtyEnglishName);

            if ($isAllSpecialityEnglish || $isAllSpecialityArabic) {
                $doctors = Doctor::all();
            } else {
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
                $user = User::find($doctor->user_id);
                $medicalSpeciality = Specialities::find($doctor->speciality_id);
                $university = MedicalCollege::find($doctor->university_id);
                $medical_degree = MedicalDegree::find($doctor->medical_degree_id);
                $doctorName = $user ? $user->name : null;
                $doctor->user = $user ? : null ;
                $doctor->doctor_name = 'Dr.' . $doctorName;
                $doctor->doctor_reviews = 0;
                $doctor->doctor_rate = 0;
                $doctor->patients = 0;
                $doctor->medical_speciality = $medicalSpeciality
                    ? [
                        "english_name" => $medicalSpeciality->english_name,
                        "arabic_name" => $medicalSpeciality->arabic_name,
                    ]
                    : null;
            
                $doctor->university = $university
                    ? [
                        "english_name" => $university->english_name,
                        "arabic_name" => $university->arabic_name,
                    ]
                    : null;
            
                $doctor->medical_degree = $medical_degree
                    ? [
                        "english_name" => $medical_degree->english_name,
                        "arabic_name" => $medical_degree->arabic_name,
                    ]
                    : null;
            
                $workplaces = Workplace::where('doctor_id', $doctor->id)->get()->map(function ($workplace) {
                    $region = Region::find($workplace->region_id);
                    $country = Country::find($workplace->country_id);
                    return [
                        "Clinic_Name" => $region->english_name,
                        "street" => $workplace->street,
                        "region" => $region
                            ? [
                                "english_name" => $region->english_name,
                                "arabic_name" => $region->arabic_name,
                            ]
                            : null,
                        "country" => $country
                            ? [
                                "english_name" => $country->english_name,
                                "arabic_name" => $country->arabic_name,
                            ]
                            : null,
                        "description" => $workplace->description,
                        "work_days" => json_decode($workplace->work_days),
                    ];
                });
            
                $doctor->workplaces = $workplaces->isEmpty() ? null : $workplaces;
            }
            
            
            
            return response()->json(['doctors' => $doctors], 200);
        } catch (\Exception $exception) {
            // Handle exceptions
            return response()->json(['message' => 'Internal Server Error', 'error' => $exception->getMessage()], 500);
        }
    }




    public function search(Request $request)
    {
        $user = $request->user();
        $patient = Patient::where('user_id', $user->id)->first();
        if (!$patient) {
            return response()->json(['errors' => 'Patient not found'], 404);
        }
        $query = $request->input('query');
        $users = User::where('name', 'like', "%$query%")->get();
        $doctorIds = $users->pluck('id');
        $doctors = Doctor::whereIn('user_id', $doctorIds)
            ->get();
        $doctorNames = $doctors->pluck('user.name');
        return response()->json(['doctors' => $doctorNames]);
    }
}
