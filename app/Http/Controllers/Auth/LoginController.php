<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    

    public function login(LoginRequest $request){

        $email = $request->email;
        $password = $request->password;
    
        // Check if a user with the provided email exists
        $user = User::where('email', $email)->first();
    
        if (!$user) {
            // If the user with the provided email doesn't exist, return an unauthorized response
            return response()->json(['error' => 'email not found'], 401);
        }
        //if ($user->email_verified_at !== null) {
            // User is logged in and email is verified

            //check the password
            if ((Hash::check($password, $user->password))||($user->password == null)) {
                // Password is correct, generate a new token
                $user->tokens()->delete();
                $token = $user->createToken(request()->userAgent())->plainTextToken;
        
                // Return the user and token in the response
                $response = [
                    'user' => $user,
                    'token' => $token,
                ];
        
                return response()->json($response, 200);
            } else {
                // Password is incorrect, return an unauthorized response
                return response()->json(['error' => 'Incorrect Password'], 401);
            }
            
        //}else{
            //return response()->json(['error' => 'Email Not Verified'], 401);
        //}
    
        
        
    }
}
