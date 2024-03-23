<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Auth\RegisterationRequest;
use App\Models\EmergencyData;
use Illuminate\Support\Facades\Hash;
use App\Notifications\RegisterationNotification;
use App\Notifications\EmailVerificationNotification;
use Closure;
use Illuminate\Support\Str;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    public function register(RegisterationRequest $request){
        try{
            $newuser = $request->validated();
            $newuser['password'] = Hash::make($newuser['password']);
            $newuser['name'] = $request->name;
            $newuser['role'] = $request->role;
            if ($request->hasFile('image')) {
                $profilePicture = $request->file('image');
                $uniqueFileName = Str::uuid() . '_' . $profilePicture->getClientOriginalName();
                $uploadDirectory = 'public/profile-pictures';
                $filePath = $profilePicture->storeAs($uploadDirectory, $uniqueFileName);
                $relativePath = 'storage/profile-pictures/';
                $imagePath = $relativePath . $uniqueFileName;
                $fullImageUrl = url($imagePath);
                $newuser['profile_photo_path'] = $fullImageUrl;
            } else {
                $newuser['profile_photo_path'] = null;
            }
            $newuser['status'] = 'pending';
            $user = User::create($newuser);
            $token = $user->createToken('user',['app:all'])->plainTextToken;
            $user->notify(new EmailVerificationNotification());
            if ($newuser['role'] === 'patient') {
                $patient = Patient::create([
                    'user_id' => $user->id,
                    'gender' => $request->input('gender'),
                    'age' => $request->input('age'),
                    'address' => $request->input('address'),
                    'phone' => $request->input('phone'),
                    'marital_status' => $request->input('marital_status'),
                    //'profile_picture' => $request->input('profile_picture'),
                ]);
                $PatientEmergencyData = EmergencyData::create([
                    'patient_id' => $patient->id,
                    'systolic' => $request->input('systolic'),
                    'diastolic' => $request->input('diastolic'),
                    'blood_sugar' => $request->input('blood_sugar'),
                    'weight' => $request->input('weight'),
                    'height' => $request->input('height'),
                    'blood_type' => $request->input('blood_type'),
                    'chronic_diseases_bad_habits' => $request->input('chronic_diseases_bad_habits'),
                ]);
                $response = [
                    //'image_path' => $imagePath,
                    //'profile_picture_url' => $profilePictureUrl,
                    'user' => $user,
                    'patient' => $patient,
                    'token' => $token
                ];
                return response()->json($response,200);
                //$user->notify(new RegisterationNotification());
            } elseif ($newuser['role'] === 'doctor') {
                $doctor = Doctor::create([
                    'user_id' => $user->id,
                    // 'gender' => $request->input('gender'),
                    // 'years_of_experience' => $request->input('years_of_experience'),
                    // 'medical_degree' => $request->input('medical_degree'),
                    // 'university' => $request->input('university'),
                    // 'speciality_id' => $request->input('speciality_id'),
                    // 'medical_board_organization' =>$request->input('medical_board_organization'),
                    // 'licence_information' => $$request->input('licence_information'),
                    // 'phone' => $request->input('phone'),
                    // 'profile_image' =>$request->input('profile_image'),
                ]);
                $response = [
                    'user' => $user,
                    'doctor' => $doctor,
                    'token' => $token
                ];

                return response()->json($response,200);
            }
            

        }catch(AuthenticationException $e)
        {
            $response = [
                'message' => 'Registeration failed',
                'errors' => $e,
            ];
    
            return response()->json($response, 401);
        }catch (\Exception $e) {
            $response = [
                'message' => 'Internal Server Error',
                'errors' => 'error : '.$e,
            ];
            return response()->json($response, 500);
        }

    }
    public function logout(Request $request){
        try{
            auth()->user()->tokens()->delete();
            return response()->json(['message' => 'Logged out'],200);
        }catch (\Exception $e) {
            $response = [
                'message' => 'Internal Server Error',
                'errors' => 'error : '.$e,
            ];
            return response()->json($response, 500);
        }
        
    }


        
        
    public function DeleteAccount(Request $request)
    {
        try {
            $user = $request->user();
            auth()->user()->tokens()->delete();
            
            if ($user) {
                if ($user['role'] === 'patient') {
                    $patient = Patient::where('user_id', $user->id)->first();
                    $patientEmergencyData = EmergencyData::where('patient_id', $patient->id)->first();
                    
                    if ($patient) {
                        $patient->delete();
                    }else{
                    }
                    if ($patientEmergencyData) {
                        $patientEmergencyData->delete();
                    }
                    $user->delete();
                    
                    return response()->json(['message' => 'Account deleted successfully'], 200);
                } elseif ($user['role'] === 'doctor') {
                    $doctor = Doctor::where('user_id', $user->id)->first();
                    
                    if ($doctor) {
                        $doctor->delete();
                    }
                    $user->delete();
                    
                    return response()->json(['message' => 'Account deleted successfully'], 200);
                }
            } else {
                return response()->json(['message' => 'User not found'], 404);
            }
        } catch (\Exception $e) {
            $response = [
                'message' => 'Internal Server Error',
                'errors' => 'error : ' . $e,
            ];
            return response()->json($response, 500);
        }
    }
}
