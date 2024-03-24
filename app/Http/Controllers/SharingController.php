<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\SharingRequest;
use App\Models\MedicalHistory;

class SharingController extends Controller
{
    public function requestSharing(Request $request, Patient $patient)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
        ]);
        
        $patient = Patient::findOrFail($request->patient_id);

        $medicalHistories = MedicalHistory::where('patient_id', $patient->id)->pluck('id')->toArray();
        $serializedMedicalHistoryIds = json_encode($medicalHistories);
        SharingRequest::create([
            'patient_id' => $patient->id,
            'doctor_id' => $request->doctor_id,
            'medical_history_ids' => $serializedMedicalHistoryIds, 
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Sharing request sent successfully'], 200);
    }

    public function approveSharing(Request $request, SharingRequest $sharingRequest)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'sharing_request_id' => 'required|exists:sharing_requests,id',
        ]);

        $sharingRequest = SharingRequest::findOrFail($request->sharing_request_id);
        $sharingRequest->update(['status' => 'approved']);

        // Additional logic if needed, such as notifying the patient

        return response()->json(['message' => 'Sharing request approved'], 200);
    }

    public function rejectSharing(Request $request, SharingRequest $sharingRequest)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'sharing_request_id' => 'required|exists:sharing_requests,id',
        ]);

        // Retrieve the sharing request
        $sharingRequest = SharingRequest::findOrFail($request->sharing_request_id);

        // Update the status of the sharing request to rejected
        $sharingRequest->update(['status' => 'rejected']);

        // Additional logic if needed, such as notifying the patient

        return response()->json(['message' => 'Sharing request rejected'], 200);
    }
}
