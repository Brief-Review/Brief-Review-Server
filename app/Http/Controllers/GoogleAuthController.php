<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
            $avatarUrl = $user_google->avatar;
            $uploadedFileUrl = Cloudinary::upload($avatarUrl)->getRealPath();
            $avatarFile = $uploadedFileUrl->getSecurePath();            

            $userNew = User::create([
                'name' => $user_google->name,
                'email' => $user_google->email,
                'avatar' => $avatarFile,
                'google_id' => $user_google->id,
            ]);

            Auth::login($userNew);
        }

        return response()->json(['message' => 'Authentication successful'], 200);
    }
}
