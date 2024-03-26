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
        //     $sharing_duration = $request->input('sharing_duration');

        // if ($sharing_duration) {
        //     $expiration_time = now()->addHours($sharing_duration);
        // } else {
        //     $expiration_time = now()->addHours(1);
        // }
        SharingRequest::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor_id,
            'status' => 'pending',
            // 'expiration_time' => $expiration_time,
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

    public function cancelSharing(Request $request, $doctor_id)
    {
        try {
            $user = $this->index($request);
            $patient = Patient::where('user_id', $user->id)->first();
            $patient_id = $patient->id;
            $sharingRequest = SharingRequest::where('patient_id', $patient_id)
                ->where('doctor_id', $doctor_id)
                ->first();
    
            if (!$sharingRequest) {
                return response()->json(['error' => 'Sharing request not found.'], 404);
            }

            $sharingRequest->delete();
    
            return response()->json(['message' => 'Sharing request canceled successfully.'], 200);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
}
}