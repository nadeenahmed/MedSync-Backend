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
            $specialityEnglishName = $request->input('medical_speciality_english');
            $specialityArabicName = $request->input('medical_speciality_arabic');
    
            $medicalSpeciality = Specialities::where(function ($query) use ($specialityEnglishName, $specialityArabicName) {
                $query->where('english_name', $specialityEnglishName)
                    ->orWhere('arabic_name', $specialityArabicName);
            })->first();
    
            if (!$medicalSpeciality) {
                return response()->json(['error' => 'Medical speciality not found'], 404);
            }
            
            $labTestsEnglishNames = json_decode($request->input('lab_tests_english'), true) ?? [];
            $labTestsArabicNames = json_decode($request->input('lab_tests_arabic'), true) ?? [];
            $combinedLabTestNames = array_merge($labTestsEnglishNames, $labTestsArabicNames);
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
            
            $medicalRecord = MedicalHistory::create([
                'patient_id' => $patient->id,
                'medical_speciality_id' => $medicalSpeciality->id,
                'diagnosis' => $request->input('diagnosis'),
                'prescription' => $request->input('prescription'),
                'reports' => $request->input('reports'),
                'files' => $request->input('files'),
                'notes' => $request->input('notes'),
            ]);
            $medicalRecord["Medical Speciality English"] = $medicalSpeciality->english_name;
            $medicalRecord["Medical Speciality Arabic"] = $medicalSpeciality->arabic_name;
            $labTestIds = $labTests->pluck('id')->toArray();
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
            $medicalRecord["Lab Tests"] = $labTests;
            return response()->json([
                'message' => 'Medical Record Added Successfully',
                'data' => $medicalRecord,
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

            foreach ($medicalRecords as $medicalRecord) {
                $labTests = DB::table('lab_test_medical_history')
                    ->join('lab_tests', 'lab_test_medical_history.lab_test_id', '=', 'lab_tests.id')
                    ->where('lab_test_medical_history.medical_history_id', $medicalRecord->id)
                    ->select('lab_tests.*')
                    ->get();

                $medicalRecord->labTests = $labTests;

                $speciality = DB::table('specialities')
                ->join('medical_histories', 'medical_histories.medical_speciality_id', '=', 'specialities.id')
                ->where('medical_histories.id', $medicalRecord->id)
                ->select('english_name','arabic_name')
                ->first();

                $medicalRecord["Medical Speciality English"]= $speciality ? $speciality->english_name : null;
                $medicalRecord["Medical Speciality arabic"]= $speciality ? $speciality->arabic_name : null;
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
