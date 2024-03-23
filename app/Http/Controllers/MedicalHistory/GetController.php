<?php

namespace App\Http\Controllers\MedicalHistory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedicalHistory;
use App\Models\Patient;

use Illuminate\Support\Facades\DB;

class GetController extends Controller
{
    public function getAllMedicalRecords(Request $request)
    {
        try {
            $user = $request->user();
            $patient = Patient::where('user_id', $user->id)->first();

            if (!$patient) {
                return response()->json(['error' => 'Patient not found'], 404);
            }

            $medicalRecords = MedicalHistory::where('patient_id', $patient->id)
            ->orderBy('created_at', 'desc') 
            ->get();
            if ($medicalRecords->isEmpty()) {
                return response()->json([
                    'message' => 'Medical Records Retrieved Successfully',
                    'Speciality Filters' => [
                        [
                            'english_name' => 'All',
                            'arabic_name' => 'الكل',
                        ],
                    ],
                    'data' => [],
                ], 200);
            }
            $allSpecialities = [];
            foreach ($medicalRecords as $medicalRecord) {
                $medicalRecord->notes = json_decode($medicalRecord->notes);
                $labTests = DB::table('lab_test_medical_history')
                    ->join('lab_tests', 'lab_test_medical_history.lab_test_id', '=', 'lab_tests.id')
                    ->where('lab_test_medical_history.medical_history_id', $medicalRecord->id)
                    ->select('lab_tests.*')
                    ->get();

                //$medicalRecord->labTests = $labTests;
                $medicalRecord["Lab Tests"] = $labTests->map(function ($labTest) {
                    return [
                        'id' => $labTest->id,
                        'arabic_name' => trim($labTest->arabic_name),
                        'english_name' => trim($labTest->english_name),
                    ];
                })->toArray();

                $medications = DB::table('medication_medical_history')
                    ->join('medications', 'medication_medical_history.medication_id', '=', 'medications.id')
                    ->where('medication_medical_history.medical_history_id', $medicalRecord->id)
                    ->select('medications.*')
                    ->get();

                //$medicalRecord->labTests = $labTests;
                $medicalRecord["medications"] = $medications->map(function ($medication) {
                    return [
                        'id' => $medication->id,
                        'name' => trim($medication->name),

                    ];
                })->toArray();



                $diagnoses = DB::table('diagnosis_medical_history')
                    ->join('diagnoses', 'diagnosis_medical_history.diagnosis_id', '=', 'diagnoses.id')
                    ->where('diagnosis_medical_history.medical_history_id', $medicalRecord->id) // Corrected reference here
                    ->select('diagnoses.*')
                    ->get();


                //$medicalRecord->labTests = $labTests;
                $medicalRecord["diagnoses"] = $diagnoses->map(function ($diagnosis) {
                    return [
                        'id' => $diagnosis->id,
                        'name' => trim($diagnosis->name),

                    ];
                })->toArray();

                $specialities = DB::table('specialities')
                    ->join('medical_histories', 'medical_histories.medical_speciality_id', '=', 'specialities.id')
                    ->where('medical_histories.id', $medicalRecord->id)
                    ->select('english_name', 'arabic_name')
                    ->get();


                $medicalRecord["Medical Speciality"] = $specialities->map(function ($speciality) {
                    return [
                        'english_name' => $speciality->english_name,
                        'arabic_name' => $speciality->arabic_name,
                    ];
                })->toArray();
                $allSpecialities = array_merge($allSpecialities, $specialities->toArray());

                $Files = DB::table('medical_history_images')
                ->join('medical_histories', 'medical_history_images.medical_history_id', '=', 'medical_histories.id')
                ->where('medical_history_images.medical_history_id', $medicalRecord->id)
                ->select('image_path')
                ->get();
        
            $medicalRecord->Files= $Files->pluck('image_path')->map(function ($filePath) {
                return trim($filePath);
            })->toArray();
            }

            $uniqueSpecialities = collect($allSpecialities)->unique()->values()->all();
            $uniqueSpecialitiesWithAll = [
                [
                    'english_name' => 'All',
                    'arabic_name' => 'الكل',
                ],
                ...$uniqueSpecialities,
            ];

            return response()->json([
                'message' => 'Medical Records Retrieved Successfully',
                'Speciality Filters' => $uniqueSpecialitiesWithAll,
                'data' => $medicalRecords,

            ], 200);
        } catch (\Exception $e) {
            $response = [
                'message' => 'Failed to Retrieve Medical Records',
                'error' => $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }


    
    public function getMedicalRecordDetails(Request $request, $medicalRecordId)
    {
        try {
            $user = $request->user();
            $patient = Patient::where('user_id', $user->id)->first();

            if (!$patient) {
                return response()->json(['error' => 'Patient not found'], 404);
            }

            $medicalRecord = MedicalHistory::where('id', $medicalRecordId)
                ->where('patient_id', $patient->id)
                ->first();

            if (!$medicalRecord) {
                return response()->json(['error' => 'Medical record not found'], 404);
            }

            $specialities = DB::table('specialities')
                ->join('medical_histories', 'medical_histories.medical_speciality_id', '=', 'specialities.id')
                ->where('medical_histories.id', $medicalRecord->id)
                ->select('english_name', 'arabic_name')
                ->get();


            $medicalRecord["Medical Speciality"] = $specialities->map(function ($speciality) {
                return [
                    'english_name' => $speciality->english_name,
                    'arabic_name' => $speciality->arabic_name,
                ];
            })->toArray();

            $diagnoses = DB::table('diagnosis_medical_history')
                ->join('diagnoses', 'diagnosis_medical_history.diagnosis_id', '=', 'diagnoses.id')
                ->where('diagnosis_medical_history.medical_history_id', $medicalRecord->id)
                ->select('diagnoses.*')
                ->get();

            $medicalRecord["diagnoses"] = $diagnoses->map(function ($diagnosis) {
                return [
                    'id' => $diagnosis->id,
                    'name' => trim($diagnosis->name),
                ];
            })->toArray();

            $medications = DB::table('medication_medical_history')
                ->join('medications', 'medication_medical_history.medication_id', '=', 'medications.id')
                ->where('medication_medical_history.medical_history_id', $medicalRecord->id)
                ->select('medications.*')
                ->get();

            $medicalRecord["medications"] = $medications->map(function ($medication) {
                return [
                    'id' => $medication->id,
                    'name' => trim($medication->name),
                ];
            })->toArray();

            $labTests = DB::table('lab_test_medical_history')
                ->join('lab_tests', 'lab_test_medical_history.lab_test_id', '=', 'lab_tests.id')
                ->where('lab_test_medical_history.medical_history_id', $medicalRecord->id)
                ->select('lab_tests.*')
                ->get();

            $medicalRecord["Lab Tests"] = $labTests->map(function ($labTest) {
                return [
                    'id' => $labTest->id,
                    'arabic_name' => trim($labTest->arabic_name),
                    'english_name' => trim($labTest->english_name),
                ];
            })->toArray();


            $medicalRecord->notes = json_decode($medicalRecord->notes);
            $response = [
                'message' => 'Medical Record Details Retrieved Successfully',
                'data' => $medicalRecord,

            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = [
                'message' => 'Failed to Retrieve Medical Record Details',
                'error' => $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }
}
