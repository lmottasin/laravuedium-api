<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use ErrorException;
use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GithubProvider;

class SocialLoginController extends Controller
{
    /**
     * @throws RequestException
     * @throws ErrorException
     * @throws Exception
     */
    public function handleProviderCallback($provider, Request $request)
    {

        $data = Http::acceptJson()
            ->post('https://github.com/login/oauth/access_token', [
                'client_id' => config('services.github.client_id'),
                'client_secret' => config('services.github.client_secret'),
                'code' => $request->get('code'),
                'redirect_uri' => config('services.github.redirect'),
            ])
            ->throw()
            ->json();

        if (isset($data['error'])) {
            return throw new ErrorException($data['error_description']);
        }


        $socialUser = Socialite::driver($provider)->stateless()->userFromToken($data['access_token']);

        if (is_null($socialUser['email'])) {
            $publicEmailsUrl = 'https://api.github.com/user/public_emails';
            $response = Http::acceptJson()->withToken($data['access_token'])->get($publicEmailsUrl)->throw()->json();

            foreach ($response as $email) {
                if ($email['primary'] && $email['verified']) {
                    $socialUser['email'] = $email['email'];
                    break;
                }
            }
        }

        $user = $this->oauthLogin($socialUser['name'], $socialUser['email']);

        $user['token'] = $user->createToken(now().random_int(1, 100))->plainTextToken;

        return response()->json($user);

    }


    public function oauthLogin(string $name, string $email)
    {
        $user = User::firstOrCreate(
            [
                'name' => $name,
                'email' => $email,
            ],
            [
                'name' => $name,
                'email' => $email,
                'password' => Hash::make(md5(uniqid().now())),
                'email_verified_at' => now()
            ]
        );

        return $user;
    }
}
