<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedicalHistory;
use App\Models\Patient;
use App\Models\Speciality;
use App\Models\LabTests;


class MedicalHistoryController extends Controller
{
    public function get_patient(Request $request)
    {
        return $request->user();
    }
    public function AddMedicalHistory(Request $request)
    {
        $user = $this->get_patient($request);
        $patient = Patient::where('user_id', $user->id)->first();
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }
        $medicalRecord = MedicalHistory::create([
            'patient_id' => $patient->id,
            'speciality_id' => $request->input('speciality_id'),
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
