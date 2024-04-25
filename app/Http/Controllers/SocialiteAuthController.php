<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteAuthController extends Controller
{
    public function index()
    {
        return Socialite::driver('github')->redirect();
    }

    public function store()
    {
        $providerUser = Socialite::driver('github')->user();
        // dd($providerUser);
        $user = User::where('email', $providerUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $providerUser->getName(),
                'email' => $providerUser->getEmail(),
            ]);
        }

        $user->authProviders()->updateOrCreate([
            'provider' => 'github',  
        ], [
            'provider_id' => $providerUser->getId(),
            'avatar' => $providerUser->getAvatar(),
            'token' => $providerUser->token,
            'nickname' => $providerUser->getNickname(),
            'login_at' => Carbon::now(),
        ]);

        Auth::login($user);

        return redirect()->to('/profile');

    }
}
