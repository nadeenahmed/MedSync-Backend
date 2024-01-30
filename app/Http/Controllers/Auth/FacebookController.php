<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;
class FacebookController extends Controller
{
    public function facebookpage(){
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookredirect(){
        try{
            $user = Socialite::driver('facebook')->user();
            $findUser = User::where('facebook_id', $user->getId())->first();
            if($findUser){
                Auth::login($findUser);
                $token = $findUser->createToken(request()->userAgent())->plainTextToken;
                $response = [
                    'user' => $findUser,
                    'token' => $token
                ];
                return response()->json($response,200);
            }
            else{
                $newUser = User::create([
                    'email'=>$user->getEmail()],[
                    'name'=>$user->getName(),
                    'facebook_id'=>$user->getId(),
                    'password'=>encrypt($user->assword),
                ]);
                Auth::login($newUser);
                $token = $newUser->createToken(request()->userAgent())->plainTextToken;
                $response = [
                    'user' => $newUser,
                    'token' => $token
                ];
                return response()->json($response, 200);
            }
            }
            catch(Exception $e){
                return response()->json(['error' => 'Facebook authentication failed'.$e], 401);
            }
        }
    }