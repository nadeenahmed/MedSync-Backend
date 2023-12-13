<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmergencyData;
use App\Models\Patient;

class BuildHomeController extends Controller
{
    public function get_patient(Request $request)
    {
        return $request->user();
    }
    public function build(Request $request)
    {
        try{
            $user = $this->get_patient($request);
            $patient = Patient::where('user_id', $user->id)->first();
            if (!$patient) {
                return response()->json(['errors' => 'Patient not found'], 404);
            }
            $existingEmergencyData = EmergencyData::where('patient_id', $patient->id)->first();
            if ($existingEmergencyData) {
                $existingEmergencyData->update($request->all());
                $emergencyData = $existingEmergencyData;
                return response()->json(['emergencyData' => $emergencyData],200);
            } else { $emergencyData = EmergencyData::create([
                'patient_id' => $patient->id,
                'high_blood_pressure' => $request->input('high_blood_pressure'),
                'low_blood_pressure' => $request->input('low_blood_pressure'),
                'sugar' => $request->input('sugar'),
                'weight' => $request->input('weight'),
                'height' => $request->input('height'),
                'blood_type' => $request->input('blood_type'),
                'chronic_diseases_bad_habits' => $request->input('chronic_diseases_bad_habits'),

            ]);
            return response()->json(['emergencyData' => $emergencyData],200);}

           
        }catch (\Exception $e) {
            $response = [
                'message' => 'Save Emergency data failed',
                'errors' => $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }

    public function update(Request $request){
        $user = $this->get_patient($request);
        $patient = Patient::where('user_id', $user->id)->first();
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }
        $emergencyData = EmergencyData::where('patient_id', $patient->id)->first();

        if (!$emergencyData) {
            return response()->json(['error' => 'Emergency data not found for the patient'], 404);
        }
        $emergencyData->update($request->all());

        return response()->json(['message' => 'Emergency data updated successfully', 'emergencyData' => $emergencyData]);
    }
}

