<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\EmergencyData;
use App\Models\Patient;
use App\Models\EmergencyDataHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientSatisticsController extends Controller
{
    public function index(Request $request)
    {
        return $request->user();
    }
    public function getBloodPressureHistory(Request $request)
    {
        try {
            $user = $this->index($request);
            if (!$user){
                return response()->json(['error' => 'User not found'], 404);
            }
            $patient = Patient::where('user_id', $user->id)->first();
            if (!$patient) {
                return response()->json(['error' => 'Patient not found'], 404);
            }
            $bloodPressureHistory = DB::table('emergency_data_histories')
                ->join('emergency_data', 'emergency_data_histories.emergency_data_id', '=', 'emergency_data.id')
                ->where('emergency_data.patient_id', $patient->id)
                ->orderByDesc('emergency_data_histories.bloodPressure_change_date')
                ->select(
                    'emergency_data_histories.bloodPressure_change_date',
                    'emergency_data_histories.systolic',
                    'emergency_data_histories.diastolic'
                )
                ->get();
    
            return response()->json(['Blood Pressure History' => $bloodPressureHistory], 200);
        } catch (\Exception $e) {
            $response = [
                'message' => 'Failed to retrieve blood pressure history',
                'errors' => $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }

    public function getBWeightHistory(Request $request)
    {
        try {
            $user = $this->index($request);
            if (!$user){
                return response()->json(['error' => 'User not found'], 404);
            }
            $patient = Patient::where('user_id', $user->id)->first();
            if (!$patient) {
                return response()->json(['error' => 'Patient not found'], 404);
            }
            $weightHistory = DB::table('emergency_data_histories')
                ->join('emergency_data', 'emergency_data_histories.emergency_data_id', '=', 'emergency_data.id')
                ->where('emergency_data.patient_id', $patient->id)
                ->orderByDesc('emergency_data_histories.weightHeight_change_date')
                ->select(
                    'emergency_data_histories.weightHeight_change_date',
                    'emergency_data_histories.weight',
                )
                ->get();
    
            return response()->json(['Weight History' => $weightHistory], 200);
        } catch (\Exception $e) {
            $response = [
                'message' => 'Failed to retrieve weight history',
                'errors' => $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }

    public function getBloodSugarHistory(Request $request)
    {
        try {
            $user = $this->index($request);
            if (!$user){
                return response()->json(['error' => 'User not found'], 404);
            }
            $patient = Patient::where('user_id', $user->id)->first();
            if (!$patient) {
                return response()->json(['error' => 'Patient not found'], 404);
            }
            $bloodSugarHistory = DB::table('emergency_data_histories')
                ->join('emergency_data', 'emergency_data_histories.emergency_data_id', '=', 'emergency_data.id')
                ->where('emergency_data.patient_id', $patient->id)
                ->orderByDesc('emergency_data_histories.bloodSugar_change_date')
                ->select(
                    'emergency_data_histories.bloodSugar_change_date',
                    'emergency_data_histories.blood_sugar',
                )
                ->get();
    
            return response()->json(['Blood Sugar History' => $bloodSugarHistory], 200);
        } catch (\Exception $e) {
            $response = [
                'message' => 'Failed to retrieve blood sugar history',
                'errors' => $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }
}
