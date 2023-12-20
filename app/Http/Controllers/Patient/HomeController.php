<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\EmergencyData;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        return $request->user();
    }
    public function view(Request $request)
    {
        try{
            $user = $this->index($request);
            $patient = Patient::where('user_id', $user->id)->first();
            if (!$patient) {
                return response()->json(['message' => 'Patient not found','errors' => 'Patient not found'], 404);
            }
            $patientName = $user->name;
            $emergencyData = EmergencyData::where('patient_id', $patient->id)->first();
            $emergencyData->chronic_diseases_bad_habits = json_decode($emergencyData->chronic_diseases_bad_habits);
            if (!$emergencyData) {
                return response()->json(['message' => 'Emergency data not found for the patient','errors' => 'Emergency data not found for the patient'], 404);
            }
            return response()->json([
                'patient_name' => $patientName,
                'emergency_data' => $emergencyData,
            ]);

        }catch (\Exception $e) {
            $response = [
                'message' => 'Loadling Home Page Failed',
                'errors' => $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
        
    }
}
