<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Traits\FileUploadTrait;
class ProfileController extends Controller
{
    use FileUploadTrait;

    public function EditProfile(Request $request)
    {
        try{
            $user = $request->user();
            $patient = Patient::where('user_id', $user->id)->first();
            if (!$patient) {
                return response()->json(['errors' => 'Patient not found'], 404);
            }
            $image = $this->handleFileUpload($request, 'image', 'public/profile-pictures', 'storage/profile-pictures/');
            $user->profile_photo_path = $image;
           
            $user->update($request->all());
            $patient->update([
                'gender' => $request->gender,//$request->input('gender'), // Assuming 'gender' is also part of the 'patient' model
                'age' => $request->input('age'),
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'marital_status' => $request->input('marital_status'),
            ]);
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
