<?php

// namespace App\Http\Controllers\Auth;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Http\Requests\Auth\ResetPasswordRequest;
// use App\Models\User;
// use App\Notifications\ResetPasswordVerificationNotification;
// use Otp;
// use Hash;
// class ResetPasswordController extends Controller
// {
//     private $otp;
//     public function __construct(){
//         $this->otp = new Otp;
//     }
//     public function passwordReset(ResetPasswordRequest $request){
//         $otp2 = $this->otp->validate($request->email, $request->otp);
//         if(! $otp2->status){
//             return response()->json(['error'=>$otp2], 401);
//         }
//             $user = User::where('email', $request->email)->first();
//             $user->update(['password' => Hash::make($request->password)]);
//             $user->tokens()->delete();
//             $success['success'] = true;
//             return response()->json($success, 200);
//         }}
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use App\Notifications\ResetPasswordVerificationNotification;
use Otp;
use Hash;

class ResetPasswordController extends Controller
{
    private $otp;

    public function __construct()
    {
        $this->otp = new Otp;
    }


    // public function verifyOtp(ResetPasswordRequest $request)
    // {
    //     $otpValidation = $this->otp->validate($request->otp, $request->email);

    //     if (!$otpValidation->status) {
    //         return response()->json(['error' => $otpValidation], 401);
    //     }
    //     }

    //     public function resetPassword(ResetPasswordRequest $request)
    //     {
    //         // Retrieve the user based on the email in the request
    //         $user = User::where('email', $request->email)->first();
        
    //         if (!$user) {
    //             return response()->json(['success' => false, 'message' => 'User not found'], 404);
    //         }
        
    //         // Update the user's password
    //         $user->update(['password' => Hash::make($request->password)]);
        
    //         // Revoke all tokens associated with the user
    //         $user->tokens()->delete();
        
    //         return response()->json(['success' => true, 'message' => 'Password reset successfully'], 200);
    //     }
    public function verifyOtp(ResetPasswordRequest $request)
{
    $otpValidation = $this->otp->validate($request->email,$request->otp);

    if (!$otpValidation->status) {
        return response()->json(['error' => $otpValidation], 401);
    }
}

public function resetPassword(ResetPasswordRequest $request)
{
    $user = User::where('email', $request->email)->first();
    $user->update(['password' => Hash::make($request->password)]);
    $user->tokens()->delete();

    return response()->json(['success' => true, 'message' => 'Password reset successfully'], 200);
}
}