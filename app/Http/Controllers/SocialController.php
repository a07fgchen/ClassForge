<?php

namespace App\Http\Controllers;

use App\Actions\Social\SocialRedirect;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Socialite;

class SocialController extends Controller
{
    //
    public function redirect($social)
    {
        return Socialite::driver($social)->stateless()->redirect();
    }

    public function callback($social, SocialRedirect $socialRedirect)
    {
        $socialRedirect->handle($social);

        return redirect()->route('dashboard');
    }
}
