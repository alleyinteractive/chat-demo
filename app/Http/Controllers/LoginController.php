<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }

        // Only allow people with @alley emails to login.
        [$username, $domain] = explode("@", $user->email);
        if (!in_array($domain, ['alley.co', 'alleyinteractive.com'], true)){
            return redirect()->to('/login');
        }

        // Check if they're an existing user.
        $existingUser = User::where('email', $user->email)->first();
        if ($existingUser){
            // Log the user in.
            auth()->login($existingUser, true);
        } else {
            $username = $user->getNickname() ?: $username;

            // Create a new user.
            $newUser            = new User;
            $newUser->name      = $user->getName();
            $newUser->email     = $user->getEmail();
            $newUser->password  = Hash::make(Str::random(32));
            $newUser->save();

            auth()->login($newUser, true);
        }

        return redirect()->to('/dashboard');
    }
}
