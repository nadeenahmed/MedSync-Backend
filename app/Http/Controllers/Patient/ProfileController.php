<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
            // if ($user->profile_photo_path) {
            //     $previousPhotoPath = str_replace(url('storage'), 'public', $user->profile_photo_path);
            //     if (Storage::exists($previousPhotoPath)) {
            //         Storage::delete($previousPhotoPath);
            //     }
            // }
    
            if ($request->hasFile('image')) {
                $profilePicture = $request->file('image');
                $uniqueFileName = Str::uuid() . '_' . $profilePicture->getClientOriginalName();
                $uploadDirectory = 'public/profile-pictures';
                $filePath = $profilePicture->storeAs($uploadDirectory, $uniqueFileName);
                $relativePath = 'storage/profile-pictures/';
                $imagePath = $relativePath . $uniqueFileName;
                $fullImageUrl = url($imagePath);
                $user->profile_photo_path = $fullImageUrl;
            } else {
                $user->profile_photo_path = null;
            }
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
