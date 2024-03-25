<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\SharingRequest;

class ApproveRequests extends Controller
{
    public function index(Request $request)
    {
        return $request->user();
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
}
