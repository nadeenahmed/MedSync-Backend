<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;


class GoogleAuthController extends Controller
{
    public function handleGoogleCallback(Request $request)
    {
        // Validate the request to ensure it contains the access token
        $request->validate([
            'access_token' => 'required|string',
        ]);

        // Send a request to Google's userinfo endpoint to fetch user information
        $response = Http::withToken($request->access_token)->get('https://openidconnect.googleapis.com/v1/userinfo');

        // Check if the response is successful
        if ($response->successful()) {
            // Parse the user information from the response
            $userData = $response->json();
            $email = $userData['email'];
            $name = $userData['name'] ?? null;

            // Check if the user already exists in the database
            $user = User::where('email', $email)->first();

            if (!$user) {
                // If the user doesn't exist, create a new user record
                $user = new User();
                $user->name = $name;
                $user->email = $email;
                // You can add more fields here if needed
                $user->save();
            }

            // At this point, $user contains the authenticated user
            // You can perform additional actions like logging in the user or generating a JWT token
            
            return response()->json(['user' => $user]);
        } else {
            // Handle the case when the request fails
            return response()->json(['error' => 'Failed to fetch user information from Google'], 500);
 }
}

    // public function redirect()
    // {
    //     return Socialite::driver('google')->redirect();
    // }

    // public function callback()
    // {
    //     try {
    //         $google_user = Socialite::driver('google')->user();
    //         $user = User::where('email', $google_user->email)->first();

    //         if (!$user) {
    //             // If the user is not found, proceed with registration
    //             return $this->RegisterWithGoogle($google_user);
    //         } else {
    //             // If the user is found, proceed with login
    //             return $this->loginWithGoogle($user);
    //         }
    //     } catch (ValidationException $e) {
    //         $response = [
    //             'message' => 'Google authentication failed',
    //             'errors' => $e->getMessage(),
    //         ];
    //         return response()->json($response, 401);
    //     } catch (\Exception $e) {
    //         $response = [
    //             'message' => 'Google authentication failed',
    //             'errors' => $e->getMessage(),
    //         ];
    //         return response()->json($response, 401);
    //     }
    // }

    // private function RegisterWithGoogle($google_user)
    // {
        
    //     $this->validate(request(), [
    //         'email' => 'email|unique:users',
    //     ]);

    //     $new_user = User::create([
    //         'name' => $google_user->getName(),
    //         'email' => $google_user->getEmail(),
    //         'google_id' => $google_user->getId(),
    //         'email_verified_at' => now(),
    //     ]);
    //     $token = $new_user->createToken(request()->userAgent())->plainTextToken;
    //     $response = [
    //         'user' => $new_user,
    //         'token' => $token,
    //     ];

    //     return response()->json($response, 200);
    // }

    // private function loginWithGoogle($user)
    // {
    //     Auth::login($user);
    //     $token = $user->createToken(request()->userAgent())->plainTextToken;
    //     $response = [
    //         'user' => $user,
    //         'token' => $token,
    //     ];
    //     return response()->json($response, 200);
    // }
}
