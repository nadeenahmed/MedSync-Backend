<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function get_patient(Request $request)
    {
        return $request->user();
    }
    public function EditProfile(Request $request)
    {
        try{
            $user = $this->get_patient($request);
            $patient = Patient::where('user_id', $user->id)->first();
            if (!$patient) {
                return response()->json(['errors' => 'Patient not found'], 404);
            }
            $patient->update($request->all());
            $response=[
                'user' => $user,
                'patient' => $patient,
            ];
            return response()->json($response,200);
           
        }catch (\Exception $e) {
            $response = [
                'message' => 'Edit Profile failed',
                'errors' => $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }
}
