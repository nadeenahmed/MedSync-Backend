<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Diagnoses;
use App\Models\Medication;
use App\Models\MedicalHistory;
use App\Models\DiagnosesMedicalHistory;
use App\Models\LabTestMedicalHistory;
use App\Models\MedicalHistoryImage;
use App\Models\Specialities;
use App\Models\LabTest;
use App\Models\MedicationMedicalHistory;
use Illuminate\Support\Str;

class AddRecord extends Controller
{
    public function index(Request $request)
    {
        return $request->user();
    }
public function addRecord(Request $request, $patient_id)
{
    try {
        // Retrieve the logged-in user (doctor)
        $user = $request->user();

        // Validate if the user is a doctor
        $doctor = Doctor::where('user_id', $user->id)->first();
        if (!$doctor) {
            return response()->json(['error' => 'Only doctors can add medical history'], 403);
        }

        // Retrieve patient data using the provided patient ID
        $patient = Patient::findOrFail($patient_id);
        $files = $request->file('files');

        // patient add medical speciality    *required*
        $specialityEnglishName = str_replace('"', '', $request->input('medical_speciality_english'));
        $specialityArabicName = str_replace('"', '', $request->input('medical_speciality_arabic'));

        // patient could provide arabic or english name but not both
        $medicalSpeciality = Specialities::where(function ($query) use ($specialityEnglishName, $specialityArabicName) {
            $query->where('english_name', $specialityEnglishName)
                ->orWhere('arabic_name', $specialityArabicName);
        })->first();

        $labTestsInput = $request->input('lab_tests');
        if (!empty($labTestsInput)) {
            $labTestsInput = trim($labTestsInput, '[]');
        }
        $labTestsArray = explode(',', $labTestsInput);
        $labTestsArray = array_map('trim', $labTestsArray);
        $combinedLabTestNames = array_filter($labTestsArray);

        // Check if any lab tests are not found
        $labTests = LabTest::whereIn('english_name', $combinedLabTestNames)
            ->orWhereIn('arabic_name', $combinedLabTestNames)
            ->get();
        $createdLabTests = [];

        foreach ($combinedLabTestNames as $labTestName) {
            // Check if the lab test name is in English or Arabic
            $isEnglish = preg_match('/^[A-Za-z0-9\s()]+$/', $labTestName);
            $isArabic = preg_match('/^[\p{Arabic}\s()]+$/u', $labTestName);

            // Create LabTest based on language
            if ($isEnglish) {
                $labTest = LabTest::firstOrCreate([
                    'english_name' => $labTestName,
                ]);
            } elseif ($isArabic) {
                $labTest = LabTest::firstOrCreate([
                    'arabic_name' => $labTestName,
                ]);
            } else {
                // Invalid lab test name format (neither English nor Arabic)
                return response()->json(['error' => 'Invalid lab test name format'], 400);
            }

            $createdLabTests[] = $labTest;
        }


        $diagnosisInput = $request->input('diagnosis_name');

        // Check if the input is not empty
        if (!empty($diagnosisInput)) {
            // Trim whitespace and remove enclosing square brackets if present
            $diagnosisInput = trim($diagnosisInput, '[]');
        }
        // Split the input into an array based on commas
        $diagnosisArray = explode(',', $diagnosisInput);
        // Remove any leading or trailing whitespaces from each element
        $diagnosisArray = array_map('trim', $diagnosisArray);
        // Filter out any empty values from the array
        $combinedDiagnosisNames = array_filter($diagnosisArray);

        // patient provide diagnosis     (required)
        // $diagnosisNames = json_decode($request->input('diagnosis_name'), true) ?? [];
        // $combinedDiagnosisNames = array_filter($diagnosisNames);
        // if (empty($combinedDiagnosisNames)) {
        //     return response()->json(['error' => 'Diagnosis not provided'], 400);
        // }

        // if (!empty($combinedDiagnosisNames)) {
        $createdDiagnosis = [];

        foreach ($combinedDiagnosisNames as $diagnosisName) {
            $diagnosis = Diagnoses::firstOrCreate(['name' => $diagnosisName]);
            $createdDiagnosis[] = $diagnosis;
        }
        $Diagnosis = Diagnoses::whereIn('name', $combinedDiagnosisNames)->get();

        // Check if any diagnoses are not found
        if (count($combinedDiagnosisNames) !== count($Diagnosis)) {
            return response()->json(['error' => 'Some Diagnoses not found or could not be created'], 404);
        }
        $MedicationInput = $request->input('medication_name');

        if (!empty($MedicationInput)) {
            $MedicationInput = trim($MedicationInput, '[]');
        }
        $MedicationArray = explode(',', $MedicationInput);
        $MedicationArray = array_map('trim', $MedicationArray);
        $combinedMedicationNames = array_filter($MedicationArray);

        $createdMedications = [];
        foreach ($combinedMedicationNames as $medicationName) {
            $medication = Medication::firstOrCreate(['name' => $medicationName]);
            $createdMedications[] = $medication;
        }

        $Medications = Medication::whereIn('name', $combinedMedicationNames)->get();
        // Check if any medications are not found
        if (count($combinedMedicationNames) !== count($Medications)) {
            return response()->json(['error' => 'Some medications not found or could not be created'], 404);
        }

        $medicalRecord = MedicalHistory::create([
            'patient_id' => $patient->id,
            'medical_speciality_id' => $medicalSpeciality->id,
            'by_who' => $user->role == 'patient' ? 'by me' : 'by Dr. ' . $user->name,
        ]);
        $uploadedFiles = [];
        if ($request->hasFile('files')) {
            //$uploadedFiles = [];
            foreach ($files as $key => $file) {
                $uniqueFileName = Str::uuid() . '_' . $file->getClientOriginalName();
                $uploadDirectory = 'public/medical-history-files';
                $filePath = $file->storeAs($uploadDirectory, $uniqueFileName);
                $filesPath = 'storage/medical-history-files/' . $uniqueFileName;
                $fullFilesUrl = url($filesPath);

                MedicalHistoryImage::create([
                    'medical_history_id' => $medicalRecord->id,
                    'image_path' => $fullFilesUrl,
                ]);

                $uploadedFiles[] = $fullFilesUrl;
            }
            $medicalRecord->makeHidden('files');
        }


        //display speciality
        $medicalRecord["Medical Speciality"] =
            [
                "english name" => $medicalSpeciality->english_name,
                "arabic name" => $medicalSpeciality->arabic_name,
            ];
        //display diagnosis
        $DiagnosisIds = $Diagnosis->pluck('id')->toArray();
        if (is_array($DiagnosisIds)) {
            foreach ($DiagnosisIds as $DiagnosisId) {
                DiagnosesMedicalHistory::create([
                    'diagnosis_id' => $DiagnosisId,
                    'medical_history_id' => $medicalRecord->id,
                ]);
            }
        } else {
            return response()->json(['error' => 'Invalid Diagnosis format'], 400);
        }
        $medicalRecord["Diagnosis"] = $Diagnosis->map(function ($Diagnoses) {
            return [
                'id' => $Diagnoses->id,
                'name' => trim($Diagnoses->name),
            ];
        })->toArray();
        //display labtests
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
        $medicalRecord["Lab Tests"] = $labTests->map(function ($labTest) {
            return [
                'id' => $labTest->id,
                'arabic_name' => trim($labTest->arabic_name),
                'english_name' => trim($labTest->english_name),
            ];
        })->toArray();
        //display medication
        $MediactionIds = $Medications->pluck('id')->toArray();
        if (is_array($MediactionIds)) {
            foreach ($MediactionIds as $MediactionId) {
                MedicationMedicalHistory::create([
                    'medication_id' => $MediactionId,
                    'medical_history_id' => $medicalRecord->id,
                ]);
            }
        } else {
            return response()->json(['error' => 'Invalid Medication format'], 400);
        }
        $medicalRecord["Medications"] = $Medications->map(function ($Medication) {
            return [
                'id' => $Medication->id,
                'name' => trim($Medication->name),
            ];
        })->toArray();

        $medicalRecord["Files"] = $uploadedFiles;
        $medicalRecord->notes = json_decode($medicalRecord->notes);
        return response()->json([
            'message' => 'Medical Record Added Successfully',
            'data' => $medicalRecord,
        ], 200);
    } catch (\Exception $e) {
        $response = [
            'message' => 'Failed to Add Medical Record',
            'error' => $e->getMessage(),
        ];
        return response()->json($response, 500);
    }
}
}
