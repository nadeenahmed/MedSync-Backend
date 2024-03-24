<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\SharingRequest;
use App\Models\MedicalHistory;

class SharingController extends Controller
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

    public function approvedRequests(Request $request)
    {
        try{
            $user = $this->index($request);
            $doctor = Doctor::where('user_id', $user->id)->first();
            if (!$doctor) {
                return response()->json(['errors' => 'Doctor not found'], 404);
            }
        $approvedSharingRequests = SharingRequest::where('doctor_id', $doctor->id)
            ->where('status', 'approved')
            ->get();

        return response()->json(['approved_sharing_requests' => $approvedSharingRequests], 200);
    }catch (\Exception $e) {
    $response = [
        'message' => 'Server Error',
        'errors' => $e->getMessage(),
    ];
    return response()->json($response, 500);
}
    }

    public function pendingRequests(Request $request)
    {
        try{
            $user = $this->index($request);
            $doctor = Doctor::where('user_id', $user->id)->first();
            if (!$doctor) {
                return response()->json(['errors' => 'Doctor not found'], 404);
            }
        $pendingSharingRequests = SharingRequest::where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->get();

        return response()->json(['pending_sharing_requests' => $pendingSharingRequests], 200);
    }catch (\Exception $e) {
        $response = [
            'message' => 'Server Error',
            'errors' => $e->getMessage(),
        ];
        return response()->json($response, 500);
    }
    }
    public function approveSharing(Request $request, $sharing_request_id)
    {
        try{
            $user = $this->index($request);
            $doctor = Doctor::where('user_id', $user->id)->first();
            if (!$doctor) {
                return response()->json(['errors' => 'Doctor not found'], 404);
            }
            $sharingRequest = SharingRequest::findOrFail($sharing_request_id);
            $sharingRequest->update(['status' => 'approved']);
    
            return response()->json(['message' => 'Sharing request approved'], 200);
    }catch (\Exception $e) {
    $response = [
        'message' => 'Server Error',
        'errors' => $e->getMessage(),
    ];
    return response()->json($response, 500);
}      
    }

    public function rejectSharing(Request $request, $sharing_request_id)
    {
        try{
            $user = $this->index($request);
            $doctor = Doctor::where('user_id', $user->id)->first();
            if (!$doctor) {
                return response()->json(['errors' => 'Doctor not found'], 404);
            }
            $sharingRequest = SharingRequest::findOrFail($sharing_request_id);
            $sharingRequest->update(['status' => 'rejected']);
    
            return response()->json(['message' => 'Sharing request rejected'], 200);
    }catch (\Exception $e) {
    $response = [
        'message' => 'Server Error',
        'errors' => $e->getMessage(),
    ];
    return response()->json($response, 500);
}      
    }
}
