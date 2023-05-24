<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user_google = Socialite::driver('google')->user();
        
        $userExists = User::where('google_id', $user_google->id)->first();

        if ($userExists) {
            Auth::login($userExists);
        } else {
            $userNew = User::create([
                'name' => $user_google->name,
                'email' => $user_google->email,
                'avatar' => $user_google->avatar,
                'google_id' => $user_google->id,
            ]);

            Auth::login($userNew);
        }

        return response()->json(['message' => 'Authentication successful'], 200);
    }
}
