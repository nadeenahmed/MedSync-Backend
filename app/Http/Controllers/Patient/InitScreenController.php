<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmergencyData;
use App\Models\Patient;

class InitScreenController extends Controller
{
    public function store(Request $request)
    {
        

        // Save data to the emergencydata_for_patients table
       // $patient = Patient::findOrFail($validatedData['patient_id']);
       $user_id = $request->input('user_id');

        // Find the patient by user_id
        //$patient = Patient::where('user_id', $user_id)->firstOrFail();
        $emergencyData =EmergencyData::create([
            'patient_id' => $user_id,
            'high_blood_pressure' => $request->input('high_blood_pressure'),
            'low_blood_pressure' => $request->input('low_blood_pressure'),
            'high_sugar' => $request->input('high_sugar'),
            'low_sugar' =>$request->input('low_sugar'),
            'weight' => $request->input('weight'),
            'height' => $request->input('height'),
            'blood_type' => $request->input('blood_type'),
            'chronic_diseases' => $request->input('chronic_diseases'),
            'bad_habits' => $request->input('bad_habits'),
        ]);


        return response()->json(['message' => 'Screen One saved successfully', 'emergencyData' => $emergencyData]);
    }
}
