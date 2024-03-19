<?php

namespace App\Http\Controllers\MedicalHistory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Specialities;
use Illuminate\Support\Facades\DB;
class FilterController extends Controller
{
    public function filterMedicalHistoryBySpecialty(Request $request)
    {
        $user = $request->user();
        $patient = Patient::where('user_id', $user->id)->first();

        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        $specialityEnglishName = str_replace('"', '', $request->input('medical_speciality_english'));
        $specialityArabicName = str_replace('"', '', $request->input('medical_speciality_arabic'));

        $isAllSpecialityEnglish = in_array($specialityEnglishName, ['All']) && empty($specialityArabicName);
        $isAllSpecialityArabic = in_array(strtoupper($specialityArabicName), ['الكل']) && empty($specialityEnglishName);

        $filteredMedicalHistory = [];

        if (!$isAllSpecialityEnglish && !$isAllSpecialityArabic) {
            $medicalSpeciality = Specialities::where(function ($query) use ($specialityEnglishName, $specialityArabicName) {
                $query->where('english_name', $specialityEnglishName)
                    ->orWhere('arabic_name', $specialityArabicName);
            })->firstOrFail();

            if (!$medicalSpeciality) {
                return response()->json(['error' => 'Medical speciality not found'], 404);
            }

            $filteredMedicalHistory = DB::table('medical_histories')
                ->where('patient_id', $patient->id)
                ->where('medical_speciality_id', $medicalSpeciality->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $filteredMedicalHistory = DB::table('medical_histories')
                ->where('patient_id', $patient->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        foreach ($filteredMedicalHistory as $medicalRecord) {
            $medicalRecord->notes = json_decode($medicalRecord->notes);

            if (isset($medicalSpeciality)) {
                $medicalSpecialityInALL = null;
                $medicalRecord->medical_speciality = [[
                    'english_name' => $medicalSpeciality->english_name,
                    'arabic_name' => $medicalSpeciality->arabic_name,
                ]];
            } else {
                $medicalSpeciality = null;
                $id = $medicalRecord->medical_speciality_id;

                $medicalSpecialityInALL = DB::table('Specialities')->where('id', $id)->first();

                if ($medicalSpecialityInALL) {
                    $medicalRecord->medical_speciality = [[
                        'english_name' => $medicalSpecialityInALL->english_name,
                        'arabic_name' => $medicalSpecialityInALL->arabic_name,
                    ]];
                } else {
                    $medicalRecord->medical_speciality = [[
                        'english_name' => 'Not Found',
                        'arabic_name' => 'غير موجود',
                    ]];
                }
            }
            $diagnoses = DB::table('diagnosis_medical_history')
                ->join('diagnoses', 'diagnosis_medical_history.diagnosis_id', '=', 'diagnoses.id')
                ->where('diagnosis_medical_history.medical_history_id', $medicalRecord->id)
                ->select('diagnoses.*')
                ->get();
            $medicalRecord->diagnoses = $diagnoses;

            $medications = DB::table('medication_medical_history')
                ->join('medications', 'medication_medical_history.medication_id', '=', 'medications.id')
                ->where('medication_medical_history.medical_history_id', $medicalRecord->id)
                ->select('medications.*')
                ->get();
            $medicalRecord->medications = $medications;

            $labTests = DB::table('lab_test_medical_history')
                ->join('lab_tests', 'lab_test_medical_history.lab_test_id', '=', 'lab_tests.id')
                ->where('lab_test_medical_history.medical_history_id', $medicalRecord->id)
                ->select('lab_tests.*')
                ->get();
            $medicalRecord->lab_Tests = $labTests;

            $Files = DB::table('medical_history_images')
                ->join('medical_histories', 'medical_history_images.medical_history_id', '=', 'medical_histories.id')
                ->where('medical_history_images.medical_history_id', $medicalRecord->id)
                ->select('image_path')
                ->get();
        
            $medicalRecord->Files= $Files->pluck('image_path')->map(function ($filePath) {
                return trim($filePath);
            })->toArray();
        }

        return response()->json([
            'message' => 'Medical History Filtered Successfully',
            'data' => $filteredMedicalHistory,
        ], 200);
    }
}
