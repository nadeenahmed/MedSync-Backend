<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\Auth\RegisterationRequest;
use Illuminate\Support\Facades\Hash;
use App\Notifications\RegisterationNotification;
use App\Notifications\EmailVerificationNotification;

class RegisterController extends Controller
{
    public function register(RegisterationRequest $request){

        

       $newuser = $request->validated();
       $newuser['password'] = Hash::make($newuser['password']);
       $newuser['role'] = 'user';
       $newuser['status'] = 'active';

       

        $user = User::create($newuser);

        $token = $user->createToken('user',['app:all'])->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];
        $user->notify(new EmailVerificationNotification());
        //$user->notify(new RegisterationNotification());

        return response()->json($response,200);

    }


    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return [
            'message' => 'logged out'
        ];
    }
}
