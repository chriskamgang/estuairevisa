<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SocialAuthController extends Controller
{
    // Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->stateless()->user();
        $this->loginOrRegister($user, 'google');
        return redirect()->route('user.dashboard');
    }

    // Facebook
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->stateless()->user();
        $this->loginOrRegister($user, 'facebook');
    }

    private function loginOrRegister($socialUser, $provider)
    {

        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'username' => $socialUser->getEmail(),
                'password' => Hash::make($socialUser->getEmail()),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'status' => 1,
                'ev' => 1
            ]
        );

        Auth::login($user);

        return redirect()->route('user.dashboard');
    }
}
