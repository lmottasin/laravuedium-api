<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GithubProvider;

class SocialLoginController extends Controller
{
    public function handleProviderCallback($provider, Request $request)
    {
        try {
            $data = Http::acceptJson()
                ->post('https://github.com/login/oauth/access_token', [
                    'client_id' => config('services.github.client_id'),
                    'client_secret' => config('services.github.client_secret'),
                    'code' => $request->get('code'),
                    'redirect_uri' => config('services.github.redirect'),
                ])
                ->throw()
                ->json();

            // $socialUser = Socialite::driver($provider)->stateless()->userFromToken($data['access_token']);

            $socialUser = $this->getUserByToken($data['token']);

            return response()->json($socialUser);

            $user = $this->oauthLogin($socialUser);

            $user['token'] = $user->createToken('main')->plainTextToken;

            return response()->json($user);

        } catch (Exception $exception) {
            return response()->json($exception);
        }
    }

    public function oauthLogin($user)
    {
        $user = User::firstOrCreate(
            [
                'name' => $user->name ?: $user->nickname,
                'email' => $user->email,
            ],
            [
                'name' => $user->name ?: $user->nickname,
                'email' => $user->email,
                'password' => Hash::make(md5(uniqid().now())),
                'email_verified_at' => now()
            ]
        );

        return $user;
    }

    /**
     * @throws RequestException
     */
    protected function getUserByToken($token)
    {
        $userUrl = 'https://api.github.com/user';

        $user = Http::get($userUrl, [
            'Authorization' => 'token '.$token,
        ])->throw()->json();

        return $user;

        if (in_array('user:email', $this->scopes, true)) {
            $user['email'] = $this->getEmailByToken($token);
        }

        return $user;
    }
}
