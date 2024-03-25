<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\SharingRequest;

class ViewRequests extends Controller
{
    public function index(Request $request)
    {
        return $request->user();
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
            ->with(['patient.user' => function ($query) use ($user) {
                $query->where('id', '!=', $user->id); // Exclude the current logged-in user
            }])
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
            ->with(['patient.user' => function ($query) use ($user) {
                $query->where('id', '!=', $user->id); // Exclude the current logged-in user
            }])
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
}
