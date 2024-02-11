<?php

namespace App\Http\Controllers\Patient;

use Twilio\Rest\Client;
use App\Http\Controllers\Controller;
use App\Models\Diagnoses;
use App\Models\DiagnosesMedicalHistory;
use App\Models\LabTestMedicalHistory;
use Illuminate\Http\Request;
use App\Models\MedicalHistory;
use App\Models\Patient;
use App\Models\LabTest;
use App\Models\Medication;
use App\Models\MedicationMedicalHistory;
use App\Models\Specialities;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class MedicalHistoryController extends Controller
{
    public function index(Request $request)
    {
        return $request->user();
    }
    public function AddMedicalHistory(Request $request)
    {
       

            $user = $this->index($request);
            $patient = Patient::where('user_id', $user->id)->first();
            if (!$patient) {
                return response()->json(['error' => 'Patient not found'], 404);
            }


            // patient add medical speciality    *required*
            // $specialityEnglishName = $request->input('medical_speciality_english');
            // $specialityArabicName = $request->input('medical_speciality_arabic');

            $specialityEnglishName = str_replace('"', '', $request->input('medical_speciality_english'));
            $specialityArabicName = str_replace('"', '', $request->input('medical_speciality_arabic'));

            // patient could provide arabic or english name but not both
            $medicalSpeciality = Specialities::where(function ($query) use ($specialityEnglishName, $specialityArabicName) {
                $query->where('english_name', $specialityEnglishName)
                    ->orWhere('arabic_name', $specialityArabicName);
            })->first();
            // if (!$medicalSpeciality) {
            //     return response()->json(['error' => 'Medical speciality not found'], 404);
            // }
           // Get the 'lab_tests' input from the request

            $labTestsInput = $request->input('lab_tests');

            // Check if the input is not empty
            if (!empty($labTestsInput)) {
                // Trim whitespace and remove enclosing square brackets if present
                $labTestsInput = trim($labTestsInput, '[]');
            }
            // Split the input into an array based on commas
            $labTestsArray = explode(',', $labTestsInput);
            // Remove any leading or trailing whitespaces from each element
            $labTestsArray = array_map('trim', $labTestsArray);
            // Filter out any empty values from the array
            $combinedLabTestNames = array_filter($labTestsArray);




            
            // Check if any lab tests are not found
            
                $labTests = LabTest::whereIn('english_name', $combinedLabTestNames)
                    ->orWhereIn('arabic_name', $combinedLabTestNames)
                    ->get();
    
                $createdLabTests = [];
    
                foreach ($combinedLabTestNames as $labTestName) {
                    // Check if the lab test name is in English or Arabic
                    $isEnglish = preg_match('/^[A-Za-z0-9\s]+$/', $labTestName);
                    $isArabic = preg_match('/^[\p{Arabic}\s]+$/u', $labTestName);
    
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
            //}

            $Diagnosis = Diagnoses::whereIn('name', $combinedDiagnosisNames)->get();

            // Check if any diagnoses are not found
            if (count($combinedDiagnosisNames) !== count($Diagnosis)) {
                return response()->json(['error' => 'Some Diagnoses not found or could not be created'], 404);
            }

            // patient provide medication     (optional)
            // $medicationNames = json_decode($request->input('medication_name'), true) ?? [];
            // $combinedMedicationNames = array_filter($medicationNames);

            $MedicationInput = $request->input('medication_name');

            // Check if the input is not empty
            if (!empty($MedicationInput)) {
                // Trim whitespace and remove enclosing square brackets if present
                $MedicationInput = trim($MedicationInput, '[]');
            }
            // Split the input into an array based on commas
            $MedicationArray = explode(',', $MedicationInput);
            // Remove any leading or trailing whitespaces from each element
            $MedicationArray = array_map('trim', $MedicationArray);
            // Filter out any empty values from the array
            $combinedMedicationNames = array_filter($MedicationArray);

           // if (!empty($combinedMedicationNames)) {
                $createdMedications = [];
                foreach ($combinedMedicationNames as $medicationName) {
                    $medication = Medication::firstOrCreate(['name' => $medicationName]);
                    $createdMedications[] = $medication;
                }
            //}
            $Medications = Medication::whereIn('name', $combinedMedicationNames)->get();
            // Check if any medications are not found
            if (count($combinedMedicationNames) !== count($Medications)) {
                return response()->json(['error' => 'Some medications not found or could not be created'], 404);
            }

            //create and display medical record
            
            $medicalRecord = MedicalHistory::create([
                'patient_id' => $patient->id,
                'medical_speciality_id' => $medicalSpeciality->id,
                //'notes' => $request->input('notes'),
                'by_who' => $user->role == 'patient' ? 'by me' : 'by ' . $user->name,
            ]);
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
            $medicalRecord->notes = json_decode($medicalRecord->notes);
            return response()->json([
                'message' => 'Medical Record Added Successfully',
                'data' => $medicalRecord,
            ], 200);
       
    }

    public function getAllMedicalRecords(Request $request)
    {
        try {
            $user = $this->index($request);
            $patient = Patient::where('user_id', $user->id)->first();

            if (!$patient) {
                return response()->json(['error' => 'Patient not found'], 404);
            }

            $medicalRecords = MedicalHistory::where('patient_id', $patient->id)->orderBy('created_at', 'desc') // Add this line to order by created_at in descending order
            ->get();
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
            }

            $uniqueSpecialities = collect($allSpecialities)->unique()->values()->all();
            $uniqueSpecialitiesWithAll = [
                [
                    'english_name' => 'All',
                    'arabic_name' => 'كل',
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


    public function filterMedicalHistoryBySpecialty(Request $request)
    {
        
            $user = $this->index($request);
            $patient = Patient::where('user_id', $user->id)->first();
            if (!$patient) {
                return response()->json(['error' => 'Patient not found'], 404);
            }
            $specialityEnglishName = str_replace('"', '', $request->input('medical_speciality_english'));
            $specialityArabicName = str_replace('"', '', $request->input('medical_speciality_arabic'));
            

            $medicalSpeciality = Specialities::where(function ($query) use ($specialityEnglishName, $specialityArabicName) {
                $query->where('english_name', $specialityEnglishName)
                    ->orWhere('arabic_name', $specialityArabicName);
            })->firstOrFail();;

            if (!$medicalSpeciality) {
                return response()->json(['error' => 'Medical speciality not found'], 404);
            }


            $filteredMedicalHistory = DB::table('medical_histories')
                ->where('patient_id', $patient->id)
                ->where('medical_speciality_id', $medicalSpeciality->id)
                ->get();
            foreach ($filteredMedicalHistory as $medicalRecord) {
                $medicalRecord->notes = json_decode($medicalRecord->notes);
                $medicalRecord->medical_speciality = [
                    'id' => $medicalSpeciality->id,
                    'english_name' => $medicalSpeciality->english_name,
                    'arabic_name' => $medicalSpeciality->arabic_name,
                ];
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
            }

            return response()->json([
                'message' => 'Medical History Filtered Successfully',
                'data' => $filteredMedicalHistory,
            ], 200);
        
    }



    public function getMedicalRecordDetails(Request $request, $medicalRecordId)
    {
        try {
            $user = $this->index($request);
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

    public function deleteMedicalRecord($id)
    {
        // Find the medical record by ID
        $medicalRecord = MedicalHistory::find($id);

        // Check if the medical record exists
        if (!$medicalRecord) {
            return response()->json(['message' => 'Medical record not found'], 404);
        }

        // Delete the medical record
        $medicalRecord->delete();

        // Respond with a success message
        return response()->json(['message' => 'Medical record deleted successfully']);
    }

    public function update(Request $request, $id)
    {
        $medicalHistory = MedicalHistory::find($id);

        // Check if the medical history record exists
        if (!$medicalHistory) {
            return response()->json(['message' => 'Medical history record not found'], 404);
        }


        // Update the medical history record with the validated data
        $medicalHistory->update($request->all());

        if ($request->has('medications')) {
            $medicalHistory->medications()->update($request->input('medications'));
        }

        // Update lab tests
        if ($request->has('labTests')) {
            $medicalHistory->labTests()->update($request->input('lab_tests'));
        }

        // Update diagnoses
        if ($request->has('diagnosis')) {
            $medicalHistory->diagnosis()->update($request->input('diagnosis'));
        }

        // Respond with the updated medical history record
        return response()->json(['message' => 'Medical history record and related records updated successfully', 'data' => $medicalHistory]);
    }

       
    




    // public function sendWhatsAppMessage()
    // {

    //     // Update the path below to your autoload.php,
    //     // see https://getcomposer.org/doc/01-basic-usage.md

    //     $twilioSid = env('TWILIO_SID');
    //     $twilioToken = env('TWILIO_AUTH_TOKEN');
    //     $twilioSWhatsAppNumber = env('TWILIO_WHATSAPP_NUMBER');
    //     $recipientNumber = 'whatsapp:+201118566002';
    //     $msg = "Hello from medsync";
    //     //require_once '/path/to/vendor/autoload.php';



    //     $twilio = new Client($twilioSid, $twilioToken);

    //     $message = $twilio->messages
    //         ->create(
    //             "whatsapp:+201155813632", // to
    //             array(
    //                 "from" => "whatsapp:+14155238886",
    //                 "body" => "Hello from medsync"
    //             )
    //         );

    //     print($message->sid);
    //     return response()->json(['message' => 'whatsapp meg sent successully']);
    // }
}
