<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Socialite;

class SocialController extends Controller
{
    //
    public function redirect($social)
    {
        return Socialite::driver($social)->redirect();
    }

    public function callback($social)
    {
        $user = Socialite::driver($social)->user();
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
            'password' => bcrypt(str_random(32)),
        ]);

        Auth::login($authUser, true);

        return redirect()->route('dashboard');
    }
}
