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

            $user = User::create($newuser);

            $token = $user->createToken('user',['app:all'])->plainTextToken;

        
            $user->notify(new EmailVerificationNotification());
            if ($newuser['role'] === 'patient') {
                $patient = Patient::create([
                    'user_id' => $user->id,
                ]);
                $response = [
                    'patient' => $patient,
                    'token' => $token
                ];

                return response()->json($response,200);
            } elseif ($newuser['role'] === 'doctor') {
                $doctor = Doctor::create([
                    'user_id' => $user->id,
                ]);
                $response = [
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
        }

    }


    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return [
            'message' => 'logged out'
        ];
    }
}
