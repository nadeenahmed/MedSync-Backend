<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(){
        return Socialite::driver('google')->redirect();
    }

    public function callback(){
        try{

            $google_user = Socialite::driver('google')->user();
            $user = User::where('google_id',$google_user->getId())->first();
            if(!$user){

                $new_user = User::create([
                    'name' => $google_user->getName(),
                    'email' => $google_user->getEmail(),
                    'google_id' =>$google_user->getId(),
                    
                ]);

                Auth::login($new_user);
                $token = $new_user->createToken(request()->userAgent())->plainTextToken;
                $response = [
                    'user' => $new_user,
                    'token' => $token
                ];
                return response()->json($response,200);

            }else{
                Auth::login($user);
                $token = $user->createToken(request()->userAgent())->plainTextToken;
                $response = [
                    'user' => $user,
                    'token' => $token
                ];
                return response()->json($response,200);

            }

        }catch(\Exception $e){
            return response()->json(['error' => 'Google authentication failed'.$e], 401);

        }
    }
}
