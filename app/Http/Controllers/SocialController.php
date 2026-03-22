<?php

namespace App\Http\Controllers;

use App\Actions\Social\SocialLogin;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    //
    public function redirect($social)
    {
        return Socialite::driver($social)->stateless()->redirect();
    }

    public function callback($social, SocialLogin $socialLogin)
    {
        $socialLogin->handle($social);

        return redirect()->route('dashboard');
    }
}
