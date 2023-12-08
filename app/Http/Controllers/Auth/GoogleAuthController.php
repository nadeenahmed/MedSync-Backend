<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\ValidationException;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $google_user = Socialite::driver('google')->user();
            $user = User::where('email', $google_user->email)->first();

            if (!$user) {
                // If the user is not found, proceed with registration
                return $this->RegisterWithGoogle($google_user);
            } else {
                // If the user is found, proceed with login
                return $this->loginWithGoogle($user);
            }
        } catch (ValidationException $e) {
            $response = [
                'message' => 'Google authentication failed',
                'errors' => $e->getMessage(),
            ];
            return response()->json($response, 401);
        } catch (\Exception $e) {
            $response = [
                'message' => 'Google authentication failed',
                'errors' => $e->getMessage(),
            ];
            return response()->json($response, 401);
        }
    }

    private function RegisterWithGoogle($google_user)
    {
        
        $this->validate(request(), [
            'email' => 'email|unique:users',
        ]);

        $new_user = User::create([
            'name' => $google_user->getName(),
            'email' => $google_user->getEmail(),
            'google_id' => $google_user->getId(),
            'email_verified_at' => now(),
        ]);
        $token = $new_user->createToken(request()->userAgent())->plainTextToken;
        $response = [
            'user' => $new_user,
            'token' => $token,
        ];

        return response()->json($response, 200);
    }

    private function loginWithGoogle($user)
    {
        Auth::login($user);
        $token = $user->createToken(request()->userAgent())->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token,
        ];
        return response()->json($response, 200);
    }
}
