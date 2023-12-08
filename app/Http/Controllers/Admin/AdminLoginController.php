<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Models\Admin;
use Hash;
class AdminLoginController extends Controller
{
    public function adminLogin(AdminLoginRequest $request){

        $email = $request->email;
        $password = $request->password;
    
        // Check if a user with the provided email exists
        $admin = Admin::where('email', $email)->first();
    
        if (!$admin) {
            // If the user with the provided email doesn't exist, return an unauthorized response
            return response()->json(['error' => 'email not found'], 401);
        }
        //if ($user->email_verified_at !== null) {
            // User is logged in and email is verified

            //check the password
            if ((Hash::check($password, $admin->password))||($admin->password == null)) {
                // Password is correct, generate a new token
                $admin->tokens()->delete();
                $token = $admin->createToken(request()->userAgent())->plainTextToken;
        
                // Return the user and token in the response
                $response = [
                    'admin' => $admin,
                    'token' => $token,
                ];
        
                return response()->json($response, 200);
            } else {
                // Password is incorrect, return an unauthorized response
                return response()->json(['error' => 'Incorrect Password'], 401);
            }
    }
}
