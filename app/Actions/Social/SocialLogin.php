<?php

namespace App\Actions\Social;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;

class SocialLogin
{
    public function handle(string $social): User
    {
        /** @var AbstractProvider $provider */
        $provider = Socialite::driver($social);
        $socialUser = $provider->stateless()->user();

        Log::info('社群登入回調', [
            'social' => $social,
            'provider_id' => $socialUser->getId(),
            'email' => $socialUser->getEmail(),
        ]);

        return DB::transaction(function () use ($social, $socialUser): User {
            $socialAccount = SocialAccount::query()
                ->with('user')
                ->where('provider_id', $socialUser->getId())
                ->where('provider_name', $social)
                ->first();

            if ($socialAccount !== null) {
                Auth::login($socialAccount->user, true);

                return $socialAccount->user;
            }

            $user = User::query()
                ->firstOrCreate([
                    'email' => $socialUser->getEmail()
                ], [
                    'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Unknown',
                    'email' => $socialUser->getEmail(),
                    'password' => null,
                ]);

            $user->socialAccounts()->updateOrCreate(
                ['provider_name' => $social],
                ['provider_id' => $socialUser->getId()]
            );

            Auth::login($user, true);

            return $user;
        });
    }
}
