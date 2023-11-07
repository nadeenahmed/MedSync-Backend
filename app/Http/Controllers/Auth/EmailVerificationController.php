<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EmailVerificationRequest;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Http\Request;
use Otp;

class EmailVerificationController extends Controller
{
    private $otp;
    public function __construct()
    {
        $this->otp = new Otp;
    }

    public function send_email_verification(Request $request){
        $request->user()->notify(new EmailVerificationNotification());
        $response = [
            'message' => 'Your email verified Successfully',
        ];
        return response()->json($response,200);


    }
    public function email_verification(EmailVerificationRequest $request){
        $otp2 = $this->otp->validate($request->email,$request->otp);
        if(!$otp2->status){
            return response()->json(['error' => $otp2],401);
        }
        $user = User::where('email',$request->email)->first();
        $user->update(['email_verified_at' => now()]);
       
        $response = [
            'message' => 'Your email verified Successfully',
        ];
        return response()->json($response,200);


    }
}
