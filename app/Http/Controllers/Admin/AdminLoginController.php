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
            $response = [
                'message' => 'Email not found',
                'errors' => $email,
            ];
            return response()->json($response,401);
        }
        //if ($user->email_verified_at !== null) {
            // User is logged in and email is verified

            //check the password
            if ((Hash::check($password, $admin->password))||($admin->password == null)) {
                // Password is correct, generate a new token
                $admin->tokens()->delete();
                //$token = $admin->createToken(request()->userAgent())->plainTextToken;
        
                $response = [
                    'message' => 'Logged in successfully',
                ];
                return response()->json($response,200);
            } else {
                $response = [
                    'message' => 'Incorrect Password',
                    'errors' => $password,
                ];
                return response()->json($response,401);
            }
    }
}
