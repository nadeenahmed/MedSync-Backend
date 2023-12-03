<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\ForgetPasswordRequest;
use App\Models\User;
use App\Notifications\ResetPasswordVerificationNotification;

class ForgetPasswordController extends Controller
{
    public function forgotPassword(ForgetPasswordRequest $request){
        $input = $request->only('email');
        $user = User::where('email',$input)->first();
        $user->notify(new ResetPasswordVerificationNotification());
        $response = 'check your email';
        return response()->json($response, 200);
    }
}
