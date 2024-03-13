<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    

    public function login(LoginRequest $request){
        try{

            $email = $request->email;
            $password = $request->password;
            $user = User::where('email', $email)->first();
        
            if (!$user) {
                $response = [
                    'message' => 'email not found',
                    'errors' => 'email not found'
                ];
                return response()->json($response, 401);
            }
            
           // if($user->status === "active"){

                if ((Hash::check($password, $user->password))||($user->password == null)) {
                    $user->tokens()->delete();
                    $token = $user->createToken(request()->userAgent())->plainTextToken;
                    $userData = [
                        'user' => $user,
                        'token' => $token,
                    ];
        
                    if ($user->role === 'patient') {
                        $patient = Patient::where('user_id', $user->id)->first();
                        $userData['patient'] = $patient;
                    } elseif ($user->role === 'doctor') {
                        $doctor = Doctor::where('user_id', $user->id)->first();
                        $userData['doctor'] = $doctor;
                    }
        
                    return response()->json($userData, 200);
                } else {
                    $response = [
                        'message' => 'Incorrect Password',
                        'errors' => 'Incorrect Password'
                    ];
                    return response()->json($response, 401);
                }

            //}else{
            //     $response = [
            //         'message' => 'Verify Your Email First',
            //         'errors' => 'Login Process Faild'
            //     ];
            //     return response()->json($response, 401);
            // }
            
        }catch (\Exception $e) {
            $response = [
                'message' => 'Internal Server Error',
                'errors' => 'error : '.$e,
            ];
            return response()->json($response, 500);
        }

    }
}
