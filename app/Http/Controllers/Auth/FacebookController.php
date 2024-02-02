<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

class FacebookController extends Controller
{
    public function facebookpage()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookredirect()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            $findUser = User::where('facebook_id', $user->getId())->first();
            
            if ($findUser) {
                Auth::login($findUser);
                $token = $findUser->createToken(request()->userAgent())->plainTextToken;
                $response = [
                    'user' => $findUser,
                    'token' => $token
                ];
                return response()->json($response, 200);
            } else {
                $newUser = User::create([
                    'email' => $user->getEmail(),
                    'name' => $user->getName(),
                    'facebook_id' => $user->getId(),
                    'password' => Hash::make($user->password),
                ]);
                Auth::login($newUser);
                $token = $newUser->createToken(request()->userAgent())->plainTextToken;
                $response = [
                    'user' => $newUser,
                    'token' => $token
                ];
                return response()->json($response, 200);
            }
        } catch (Exception $e) {
            // Log the exception for debugging purposes
            //Log::error('Facebook authentication failed: ' . $e->getMessage());
            return response()->json(['error' => 'Facebook authentication failed'], 401);
        }
    }
}