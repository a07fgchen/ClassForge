<?php

namespace App\Actions\Social;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLogin
{
    public function handle($social): void
    {
        $user = Socialite::driver($social)->stateless()->user();

        Log::info('社群登入回調', [
            'social' => $social,
            'user' => $user,
        ]);

        $authUser = User::updateOrCreate([
            'provider_id' => $user->getId(),
            'provider_name' => $social,
        ], [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => bcrypt(Str::random(32)),
        ]);

        Auth::login($authUser, true);
    }
}
