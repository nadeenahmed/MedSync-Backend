<?php

namespace App\Http\Controllers\Doctor;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\User;
use App\Models\SharingRequest;
use App\Models\MedicalHistory;
use App\Models\Specialities;
use App\Models\BloodPressureChange;
use App\Models\BloodSugarChange;
use App\Models\WeightHeightChange;
use App\Models\EmergencyData;
class ViewPatient extends Controller
{
    public function index(Request $request)
    {
        return $request->user();
    }
    public function patientProfile(Request $request, $sharing_request_id)
{
    try {
        $sharingRequest = SharingRequest::findOrFail($sharing_request_id);
       
        $patient = Patient::findOrFail($sharingRequest->patient_id);

        $user = User::where('id', $patient->user_id)->first();

        $existingEmergencyData = EmergencyData::where('patient_id', $patient->id)->first();
        $bloodPressureHistory = DB::table('blood_pressure_changes')
                ->join('emergency_data', 'blood_pressure_changes.emergency_data_id', '=', 'emergency_data.id')
                ->where('emergency_data.patient_id', $patient->id)
                ->orderByDesc('blood_pressure_changes.date')
                ->orderByDesc('blood_pressure_changes.time')
                ->groupBy(
                    'blood_pressure_changes.date',
                    'blood_pressure_changes.time',
                    'blood_pressure_changes.systolic',
                    'blood_pressure_changes.diastolic',
                    'blood_pressure_changes.color',
                    'blood_pressure_changes.color_description',
                    'blood_pressure_changes.emergency_data_id'
                )
                ->select(
                    'blood_pressure_changes.date',
                    'blood_pressure_changes.time',
                    'blood_pressure_changes.systolic',
                    'blood_pressure_changes.diastolic',
                    'blood_pressure_changes.color',
                    'blood_pressure_changes.color_description'
                )
                ->distinct()
                ->get();
        $bloodSugarHistory = DB::table('blood_sugar_changes')
                ->join('emergency_data', 'blood_sugar_changes.emergency_data_id', '=', 'emergency_data.id')
                ->where('emergency_data.patient_id', $patient->id)
                ->orderByDesc('blood_sugar_changes.date')
                ->orderByDesc('blood_sugar_changes.time')
                ->groupBy(
                    'blood_sugar_changes.date',
                    'blood_sugar_changes.time',
                    'blood_sugar_changes.blood_sugar',
                    'blood_sugar_changes.emergency_data_id'
                )
                ->select(
                    'blood_sugar_changes.date',
                    'blood_sugar_changes.time',
                    'blood_sugar_changes.blood_sugar',
                    
                )
                ->distinct()
                ->get();

        // $bloodSugarChange = BloodSugarChange::where('emergency_data_id', $existingEmergencyData->id)->first();

        // $weightHeightChange = WeightHeightChange::where('emergency_data_id', $existingEmergencyData->id)->first();
        $weightHistory = DB::table('weight_height_changes')
        ->join('emergency_data', 'weight_height_changes.emergency_data_id', '=', 'emergency_data.id')
        ->where('emergency_data.patient_id', $patient->id)
        ->orderByDesc('weight_height_changes.date')
        ->orderByDesc('weight_height_changes.time')
        ->groupBy(
            'weight_height_changes.date',
            'weight_height_changes.time',
            'weight_height_changes.weight',
            'weight_height_changes.height',
            'weight_height_changes.emergency_data_id'
        )
        ->select(
            'weight_height_changes.date',
            'weight_height_changes.time',
            'weight_height_changes.weight',
            'weight_height_changes.height'
        )
        ->distinct()
        ->get();
        return response()->json([
            'user' => $user,
            'patient' => $patient,
            'emergency_data' => $existingEmergencyData,
            'pressure_history' => $bloodPressureHistory,
            'sugar_history' => $bloodSugarHistory,
            'weight_height_history' => $weightHistory
        ], 200);
    } catch (\Exception $e) {
        $response = [
            'message' => 'Server Error',
            'errors' => $e->getMessage(),
        ];
        return response()->json($response, 500);
    }
}

public function patientHistory(Request $request, $sharing_request_id)
{
    try {
        $sharingRequest = SharingRequest::findOrFail($sharing_request_id);
       
        $patient = Patient::findOrFail($sharingRequest->patient_id);

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

public function filterHistory(Request $request, $sharing_request_id)
{
    try {
        $sharingRequest = SharingRequest::findOrFail($sharing_request_id);
       
        $patient = Patient::findOrFail($sharingRequest->patient_id);

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
    }catch (\Exception $e) {
    $response = [
        'message' => 'Failed to Retrieve Medical Records',
        'error' => $e->getMessage(),
    ];
    return response()->json($response, 500);
}
}
}
