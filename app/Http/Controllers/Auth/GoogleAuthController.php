<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class GoogleAuthController extends Controller
{
    public function handleGoogleCallback(Request $request)
    {

        $request->validate([
            'access_token' => 'required|string',
        ]);


        $response = Http::withToken($request->access_token)->get('https://openidconnect.googleapis.com/v1/userinfo');


        if ($response->successful()) {

            $userData = $response->json();
            $email = $userData['email'];
            $name = $userData['name'] ?? null;
            $user = User::where('email', $email)->first();
            if (!$user) {
                // If the user doesn't exist, create a new user record
                $user = User::create([
                    'email' => $email,
                    'name' => $name,
                ]);
                Auth::login($user);
                 $user->save();
            }
            $token = $user->createToken(request()->userAgent())->plainTextToken;
            $response = [
                'user' => $user,
                'token' => $token
            ];
            return response()->json($response, 200);
        } else {
            return response()->json(['error' => 'Failed to fetch user information from Google'], 500);
 }
}
}
