<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorApprovalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Mail\DoctorApprovalMail;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class DoctorApprovalRequestController extends Controller
{
    public function index()
    {
        $approvalRequests = DoctorApprovalRequest::all();
        if($approvalRequests->isEmpty()){
            $response = [
                'message' => 'No Approval request found',
            ];
            return response()->json($response, 200);
        }
        return response()->json(['approvalRequests' => $approvalRequests],200);
    }

    public function show($id)
    {
        try{
            $approvalRequest = DoctorApprovalRequest::findOrFail($id);
            return response()->json(['approvalRequest' => $approvalRequest]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $response = [
                'message' => 'Approval request not found',
                'errors' => 'error : ' . $e,
            ];
            return response()->json($response, 404);
        } catch (\Exception $e) {
            $response = [
                'message' => 'Internal Server Error',
                'errors' => 'error : ' . $e,
            ];
            return response()->json($response, 500);
        }
    }

    public function approve($id)
    {
        try {
            $approvalRequest = DoctorApprovalRequest::findOrFail($id);
            $approvalRequest->update(['request_status' => 'approved']);
            $approvalRequest->delete();
            $doctor_id = $approvalRequest->doctor_id;
            $doctor = Doctor::findOrFail($doctor_id);
            $user = User::where('id', $doctor->user_id)->first();
            $doctorName = $user->name;
            //Mail::to($user->email)->send(new DoctorApprovalMail($doctorName));
            return response()->json(['message' => 'Approval request approved']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $response = [
                'message' => 'Approval request not found',
                'errors' => 'error : ' . $e,
            ];
            return response()->json($response, 404);
        } catch (\Exception $e) {
            $response = [
                'message' => 'Internal Server Error',
                'errors' => 'error : ' . $e,
            ];
            return response()->json($response, 500);
        }
    }



    public function reject(Request $request,$id)
    {
        try {
            $reasons = [
                'License Not Supported' => 'Your license provided does not meet the requirements for approval. Please ensure that the license you submit is valid and supports our approval criteria.',
                'Unclear License' => 'The license you provided is not clear or legible, making it difficult for us to verify its authenticity. Please upload a clear copy of your license that is easy to read.',
                'Other Reasons' => 'Your approval request was rejected. Please review the requirements and ensure all necessary documents are provided correctly before reapplying.',
            ];
            $approvalRequest = DoctorApprovalRequest::findOrFail($id);
            $reasonCode = $request->input('reason');
            $reasonDescription = $reasons[$reasonCode] ?? '';
            $approvalRequest->update(['request_status' => 'pendeing']);
            $approvalRequest->delete();
            $doctor_id = $approvalRequest->doctor_id;
            $doctor = Doctor::findOrFail($doctor_id);
            $user = User::where('id', $doctor->user_id)->first();
            $doctorName = $user->name;
            //Mail::to($user->email)->send(new DoctorApprovalMail($doctorName));
            return response()->json([
                'message' => 'Approval request rejected','reason' => [
                'code' => $reasonCode,
                'description' => $reasonDescription
            ]]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $response = [
                'message' => 'Approval request not found',
                'errors' => 'error : ' . $e,
            ];
            return response()->json($response, 404);
        } catch (\Exception $e) {
            $response = [
                'message' => 'Internal Server Error',
                'errors' => 'error : ' . $e,
            ];
            return response()->json($response, 500);
        }
    }

    // public function destroy($id)
    // {
    //     try{
    //         $approvalRequest = DoctorApprovalRequest::findOrFail($id);
    //         $approvalRequest->delete();
    //         return response()->json(['message' => 'Approval request deleted successfully']);
    //     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    //         $response = [
    //             'message' => 'Approval request not found',
    //             'errors' => 'error : ' . $e,
    //         ];
    //         return response()->json($response, 404);
    //     } catch (\Exception $e) {
    //         $response = [
    //             'message' => 'Internal Server Error',
    //             'errors' => 'error : ' . $e,
    //         ];
    //         return response()->json($response, 500);
    //     }
    // }



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
