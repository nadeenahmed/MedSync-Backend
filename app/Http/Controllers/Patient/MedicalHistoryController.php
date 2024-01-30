<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\LabTestMedicalHistory;
use Illuminate\Http\Request;
use App\Models\MedicalHistory;
use App\Models\Patient;
use App\Models\Speciality;
use App\Models\LabTest;
use App\Models\Specialities;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\DB;

class MedicalHistoryController extends Controller
{
    public function index(Request $request)
    {
        return $request->user();
    }
    public function AddMedicalHistory(Request $request)
    {
        try{

            $user = $this->index($request);
            $patient = Patient::where('user_id', $user->id)->first();
            if (!$patient) {
                return response()->json(['error' => 'Patient not found'], 404);
            }
            $englishName = $request->input('medical_speciality_english');
            $arabicName = $request->input('medical_speciality_arabic');
    
            $medicalSpeciality = Specialities::where(function ($query) use ($englishName, $arabicName) {
                $query->where('english_name', $englishName)
                    ->orWhere('arabic_name', $arabicName);
            })->first();
    
            if (!$medicalSpeciality) {
                return response()->json(['error' => 'Medical speciality not found'], 404);
            }
            
            $englishNames = json_decode($request->input('lab_tests_english'), true) ?? [];
            $arabicNames = json_decode($request->input('lab_tests_arabic'), true) ?? [];
    
            $combinedLabTestNames = array_merge($englishNames, $arabicNames);
            $combinedLabTestNames = array_filter($combinedLabTestNames);
    
            if (empty($combinedLabTestNames)) {
                return response()->json(['error' => 'Lab tests not provided'], 400);
            }
    
            $labTests = LabTest::whereIn('english_name', $combinedLabTestNames)
                ->orWhereIn('arabic_name', $combinedLabTestNames)
                ->get();
    
            if ($labTests->isEmpty()) {
                return response()->json(['error' => 'Lab tests not found'], 404);
            }
            

            // $labTests = LabTest::where(function ($query) use ($englishNames, $arabicNames) {
            //     foreach ($englishNames as $englishName) {
            //         $query->orWhere('english_name', $englishName);
            //     }

            //     foreach ($arabicNames as $arabicName) {
            //         $query->orWhere('arabic_name', $arabicName);
            //     }
            // })->get();

            // if ($labTests->isEmpty()) {
            //     return response()->json(['error' => 'Lab tests not found'], 404);
            // }

            $medicalRecord = MedicalHistory::create([
                'patient_id' => $patient->id,
                'medical_speciality_id' => $medicalSpeciality->id,
                'diagnosis' => $request->input('diagnosis'),
                'prescription' => $request->input('prescription'),
                'reports' => $request->input('reports'),
                'files' => $request->input('files'),
                'notes' => $request->input('notes'),
            ]);
            $medicalRecord["Medical Speciality en"] = $medicalSpeciality->english_name;
            $medicalRecord["Medical Speciality ar"] = $medicalSpeciality->arabic_name;
            
                $labTestIds = $labTests->pluck('id')->toArray();
                //$labTestIds = json_decode($request->input('lab_tests'), true);
                if (is_array($labTestIds)) {
                    foreach ($labTestIds as $labTestId) {
                        LabTestMedicalHistory::create([
                            'lab_test_id' => $labTestId,
                            'medical_history_id' => $medicalRecord->id,
                        ]);
                    }
                } else {
                     return response()->json(['error' => 'Invalid lab tests format'], 400);
                }
            
            return response()->json([
                'message' => 'Medical Record Added Successfully',
                'data' => $medicalRecord,
                'lt' => $labTestIds
            ], 200);

        }catch (\Exception $e) {
            $response = [
                'message' => 'Faild to Add Medical Record',
                'error' => $e->getMessage(),
            ];
            return response()->json($response, 500);
        }

        
    }

    public function getAllMedicalRecords(Request $request)
    {
        try {
            $user = $this->index($request);
            $patient = Patient::where('user_id', $user->id)->first();

            if (!$patient) {
                return response()->json(['error' => 'Patient not found'], 404);
            }

           $medicalRecords = MedicalHistory::where('patient_id', $patient->id)->get();

        // Fetch lab tests for each medical record using DB::table
        foreach ($medicalRecords as $medicalRecord) {
            $labTests = DB::table('lab_test_medical_history')
                ->join('lab_tests', 'lab_test_medical_history.lab_test_id', '=', 'lab_tests.id')
                ->where('lab_test_medical_history.medical_history_id', $medicalRecord->id)
                ->select('lab_tests.*')
                ->get();

            // Add labTests property to the medical record
            $medicalRecord->labTests = $labTests;
        }

        foreach ($medicalRecords as $medicalRecord) {
            $labTests = DB::table('lab_test_medical_history')
                ->join('lab_tests', 'lab_test_medical_history.lab_test_id', '=', 'lab_tests.id')
                ->where('lab_test_medical_history.medical_history_id', $medicalRecord->id)
                ->select('lab_tests.*')
                ->get();

            // Add labTests property to the medical record
            $medicalRecord->labTests = $labTests;
        }

            return response()->json([
                'message' => 'Medical Records Retrieved Successfully',
                'data' => $medicalRecords
            ], 200);
        } catch (\Exception $e) {
            $response = [
                'message' => 'Failed to Retrieve Medical Records',
                'error' => $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }
}
