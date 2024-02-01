<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\BloodPressureChange;
use App\Models\BloodSugarChange;
use App\Models\WeightHeightChange;
use Illuminate\Http\Request;
use App\Models\EmergencyData;
use App\Models\Patient;

use Carbon\Carbon;

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
                
                BloodPressureChange::create([
                    'emergency_data_id' => $existingEmergencyData->id,
                    'systolic' => $existingEmergencyData->systolic,
                    'diastolic' => $existingEmergencyData->diastolic,
                    'time' => $existingEmergencyData->bloodPressure_change_time,
                    'date' => $existingEmergencyData->bloodPressure_change_date,
                ]);
                BloodSugarChange::create([
                    'emergency_data_id' => $existingEmergencyData->id,
                    'blood_sugar' => $existingEmergencyData->blood_sugar,
                    'time' => $existingEmergencyData->bloodSugar_change_time,
                    'date' => $existingEmergencyData->bloodSugar_change_date,
                ]);

                WeightHeightChange::create([
                    'emergency_data_id' => $existingEmergencyData->id,
                    'weight' => $existingEmergencyData->weight,
                    'height' => $existingEmergencyData->height,
                    'time' => $existingEmergencyData->weightHeight_change_time,
                    'date' => $existingEmergencyData->weightHeight_change_date,
                ]);
                $emergencyData = $existingEmergencyData;
                return response()->json(['patient_name' => $patientName,'emergency_data' => $emergencyData],200);
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
                'bloodPressure_change_time' => $request->input('bloodPressure_change_time'),
                'bloodSugar_change_date' => $request->input('bloodSugar_change_date'),
                'bloodSugar_change_date' => $request->input('bloodSugar_change_date'),
                'weightHeight_change_date' => $request->input('weightHeight_change_date'),
                'weightHeight_change_time' => $request->input('weightHeight_change_time'),
                // 'bloodPressure_change_date' => Carbon::createFromFormat('d/m/y', $request->input('bloodPressure_change_date'))->format('Y-m-d'),
                // 'bloodPressure_change_time' => Carbon::createFromFormat('H:i:s',$request->input('bloodPressure_change_time'))->format('h:i'),
                // 'bloodSugar_change_date'=> Carbon::createFromFormat('d/m/Y', $request->input('bloodSugar_change_date'))->format('Y-m-d'),
                // 'bloodSugar_change_time'=> Carbon::createFromFormat('H:i:s',$request->input('bloodSugar_change_time'))->format('h:i'),
                // 'weightHeight_change_date'=> Carbon::createFromFormat('d/m/Y', $request->input('weightHeight_change_date'))->format('Y-m-d'),
                // 'weightHeight_change_time'=> Carbon::createFromFormat('H:i:s',$request->input('weightHeight_change_time'))->format('h:i'),

            ]);

            BloodPressureChange::create([
                'emergency_data_id' => $emergencyData->id,
                'systolic' => $emergencyData->systolic,
                'diastolic' => $emergencyData->diastolic,
                'time' => $emergencyData->bloodPressure_change_time,
                'date' => $emergencyData->bloodPressure_change_date,
            ]);

            BloodSugarChange::create([
                'emergency_data_id' => $emergencyData->id,
                'blood_sugar' => $emergencyData->blood_sugar,
                'time' => $emergencyData->bloodSugar_change_time,
                'date' => $emergencyData->bloodSugar_change_date,
            ]);

            WeightHeightChange::create([
                'emergency_data_id' => $emergencyData->id,
                'weight' => $emergencyData->weight,
                'height' => $emergencyData->height,
                'time' => $emergencyData->weightHeight_change_time,
                'date' => $emergencyData->weightHeight_change_date,
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

    // public function update(Request $request){
    //     $user = $this->index($request);
    //     $patient = Patient::where('user_id', $user->id)->first();
    //     if (!$patient) {
    //         return response()->json(['error' => 'Patient not found'], 404);
    //     }
    //     $emergencyData = EmergencyData::where('patient_id', $patient->id)->first();

    //     if (!$emergencyData) {
    //         return response()->json(['error' => 'Emergency data not found for the patient'], 404);
    //     }
    //     $emergencyData->update($request->all());

    //     return response()->json(['message' => 'Emergency data updated successfully', 
    //     'emergencyData' => $emergencyData]);
    // }
}

