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
                    'blood_pressure_changes.emergency_data_id'
                )
                ->select(
                    'blood_pressure_changes.date',
                    'blood_pressure_changes.time',
                    'blood_pressure_changes.systolic',
                    'blood_pressure_changes.diastolic'
                )
                ->distinct()
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
