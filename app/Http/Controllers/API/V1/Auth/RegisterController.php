<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Auth\StoreUserRequest;
use App\Http\Resources\V1\UserResources;
use App\Jobs\SendEmailVerification;
use App\Models\User;
use Illuminate\Http\Response;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreUserRequest $request)
    {
        $user = User::create($request->validated());

        SendEmailVerification::dispatch($user);

        $device = substr($request->userAgent() ?? '', 0, 255);

        return response()->json([
            'access_token' => $user->createToken($device)->plainTextToken,
            'user' => UserResources::make($user)
        ], Response::HTTP_CREATED);
    }
}
