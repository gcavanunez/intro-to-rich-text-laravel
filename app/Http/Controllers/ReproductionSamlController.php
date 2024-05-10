<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class ReproductionSamlController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        /** @var \SocialiteProviders\Saml2\Provider $driver */
        $driver = Socialite::driver('saml2');

        /** @var SocialiteUser $samlUser */
        $samlUser = $driver->stateless()->user();

        // Logging for testing purposes.
        // if (config('app.env') !== 'testing') {
        // Log::debug('here');
        Log::debug(['samlUser' => $samlUser]);
        // }

        $user = User::create([
            'email' => $samlUser->email,
            'name' => $samlUser->first_name.' '.$samlUser->last_name,
            'password' => 'password',
        ]);

        Auth::login($user);

        return redirect()->intended('/dashboard');
    }
}
