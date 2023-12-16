<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Http\Requests\Auth\RegisterationRequest;
use App\Models\EmergencyData;
use Illuminate\Support\Facades\Hash;
use App\Notifications\RegisterationNotification;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Auth\AuthenticationException;





class RegisterController extends Controller
{
    public function register(RegisterationRequest $request){

        
        try{

            $newuser = $request->validated();
            $newuser['password'] = Hash::make($newuser['password']);
            $newuser['name'] = $request->name;
            $newuser['role'] = $request->role;
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
                    'user' => $user,
                    'patient' => $patient,
                    'token' => $token
                ];

                return response()->json($response,200);
            } elseif ($newuser['role'] === 'doctor') {
                $doctor = Doctor::create([
                    'user_id' => $user->id,
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
        try{

            auth()->user()->tokens()->delete();
            $user=$request->user();
            $patient = Patient::where('user_id', $user['id'])->first();
            $patientEmergencyData = EmergencyData::where('patient_id',$patient['id'])->first();
            $doctor = Doctor::where('user_id' , $user['id'])->first();
            if($user)
            {
                if($user['role']==='patient')
                {
                    $user->delete();
                    $patient->delete();
                    $patientEmergencyData->delete();
                    return response()->json(['message' => 'Account deleted successfully'],200);

                }else{
                    $user->delete();
                    $doctor->delete();
                    return response()->json(['message' => 'Account deleted successfully'],200);
                }

            }else{
                return response()->json(['message' => 'User not found'], 404);
            }

        }catch (\Exception $e) {
            $response = [
                'message' => 'Internal Server Error',
                'errors' => 'error : '.$e,
            ];
            return response()->json($response, 500);
        }
        
        
    }
}
