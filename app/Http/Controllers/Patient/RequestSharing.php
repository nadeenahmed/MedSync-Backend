<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\User;
use App\Models\SharingRequest;

class RequestSharing extends Controller
{
    public function index(Request $request)
    {
        return $request->user();
    }
    public function requestSharing(Request $request, $doctor_id)
    {
        try{
            $user = $this->index($request);
            $patient = Patient::where('user_id', $user->id)->first();
            if (!$patient) {
                return response()->json(['errors' => 'Patient not found'], 404);
            }
        SharingRequest::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor_id,
            'status' => 'pending',
        ]);
        return response()->json(['message' => 'Sharing request sent successfully'], 200);
    }
    catch (\Exception $e) {
        $response = [
            'message' => 'Sharing History Failed',
            'errors' => $e->getMessage(),
        ];
        return response()->json($response, 500);
    }
    }
}
