<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorApprovalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Mail\DoctorApprovalMail;
use App\Models\Doctor;
use Illuminate\Support\Facades\Mail;

class DoctorApprovalRequestController extends Controller
{
    public function index()
    {
        $approvalRequests = DoctorApprovalRequest::all();
        return response()->json(['approvalRequests' => $approvalRequests]);
    }

    public function destroy($id)
    {
        $approvalRequest = DoctorApprovalRequest::findOrFail($id);
        $approvalRequest->delete();
        return response()->json(['message' => 'Approval request deleted successfully']);
    }

    public function approve($id)
    {
        $approvalRequest = DoctorApprovalRequest::findOrFail($id);
        $doctor_id = $approvalRequest->doctor_id;
        $doctor = Doctor::findOrFail($doctor_id);
        $approvalRequest->update(['request_status' => 'approved']);
        

        Mail::to($doctor->email)->send(new DoctorApprovalMail());
        return response()->json(['message' => 'Approval request approved']);
    }

    public function reject($id)
    {
        // Reject a specific approval request
        $approvalRequest = DoctorApprovalRequest::findOrFail($id);
        $approvalRequest->update(['request_status' => 'rejected']);

        return response()->json(['message' => 'Approval request rejected']);
    }

    public function sendToCareerV($id)
    {
        // Send a specific approval request to the external system
        $approvalRequest = DoctorApprovalRequest::findOrFail($id);

        // Example: Send data to external system using HTTP request
        $response = Http::post('external-system-api-endpoint', [
            'data' => $approvalRequest->toArray(),
        ]);

        // Handle response from the external system
        if ($response->successful()) {
            // External system accepted the request
            $approvalRequest->update(['status' => 'sent_to_external_system']);
            return response()->json(['message' => 'Approval request sent to the external system']);
        } else {
            // External system rejected the request
            return response()->json(['error' => 'Failed to send request to the external system'], 500);
        }
    }
}
