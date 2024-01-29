<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\LabTestMedicalHistory;
use Illuminate\Http\Request;
use App\Models\MedicalHistory;
use App\Models\Patient;
use App\Models\Speciality;
use App\Models\LabTests;
use App\Models\Specialities;

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
        $medicalSpeciality = Specialities::firstOrCreate(
            ['english_name' => $request->input('medical_speciality')],
        );
        $medicalRecord = MedicalHistory::create([
            'patient_id' => $patient->id,
            'medical_speciality_id' => $medicalSpeciality->id,
            'diagnosis' => $request->input('diagnosis'),
            'prescription' => $request->input('prescription'),
            'reports' => $request->input('reports'),
            'files' => $request->input('files'),
            'notes' => $request->input('notes'),
        ]);
        return response()->json([
            'message' => 'Medical record added successfully',
            
            'data' => $medicalRecord
        ], 201);
    }
}
