<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmergencyData;
use App\Models\EmergencyDataHistory;
use App\Models\Patient;

class BuildHomeController extends Controller
{
    public function index(Request $request)
    {
        return $request->user();
    }
    public function build(Request $request)
    {
        try{
            $user = $this->index($request);
            $patientName = $user->name;
            $patient = Patient::where('user_id', $user->id)->first();
            if (!$patient) {
                return response()->json(['errors' => 'Patient not found'], 404);
            }
            $existingEmergencyData = EmergencyData::where('patient_id', $patient->id)->first();
            if ($existingEmergencyData) {
               
                $existingEmergencyData->update($request->all());
                EmergencyDataHistory::create([
                    'emergency_data_id' => $existingEmergencyData->id,
                    'systolic' => $existingEmergencyData->systolic,
                    'diastolic' => $existingEmergencyData->diastolic,
                    'blood_sugar' => $existingEmergencyData->blood_sugar,
                    'weight' => $existingEmergencyData->weight,
                    'height' => $existingEmergencyData->height,
                    'blood_type' => $existingEmergencyData->blood_type,
                    'chronic_diseases_bad_habits' => $existingEmergencyData->chronic_diseases_bad_habits,
                    'bloodPressure_change_date' => $existingEmergencyData->bloodPressure_change_date,
                    'bloodSugar_change_date'=> $existingEmergencyData->bloodSugar_change_date,
                    'weightHeight_change_date'=> $existingEmergencyData->weightHeight_change_date,
                ]);
                $emergencyData = $existingEmergencyData;
                return response()->json(['patient_name' => $patientName,'emergency_data' => $emergencyData,],200);
            } else { $emergencyData = EmergencyData::create([
                'patient_id' => $patient->id,
                'systolic' => $request->input('systolic'),
                'diastolic' => $request->input('diastolic'),
                'blood_sugar' => $request->input('blood_sugar'),
                'weight' => $request->input('weight'),
                'height' => $request->input('height'),
                'blood_type' => $request->input('blood_type'),
                'chronic_diseases_bad_habits' => $request->input('chronic_diseases_bad_habits'),
                'bloodPressure_change_date' => $request->input('bloodPressure_change_date'),
                'bloodSugar_change_date'=> $request->input('bloodSugar_change_date'),
                'weightHeight_change_date'=> $request->input('weightHeight_change_date'),

            ]);
            return response()->json(['patient_name' => $patientName,'emergency_data' => $emergencyData],200);}

           
        }catch (\Exception $e) {
            $response = [
                'message' => 'Save Emergency data failed',
                'errors' => $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }

    public function update(Request $request){
        $user = $this->index($request);
        $patient = Patient::where('user_id', $user->id)->first();
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }
        $emergencyData = EmergencyData::where('patient_id', $patient->id)->first();

        if (!$emergencyData) {
            return response()->json(['error' => 'Emergency data not found for the patient'], 404);
        }
        $emergencyData->update($request->all());

        return response()->json(['message' => 'Emergency data updated successfully', 
        'emergencyData' => $emergencyData]);
    }
}

