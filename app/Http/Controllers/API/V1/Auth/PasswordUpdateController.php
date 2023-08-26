<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Auth\UpdatePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PasswordUpdateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdatePasswordRequest $request)
    {
        auth()->user()->update([
            'password' => $request->validated('password')
        ]);

        return response()->json([
            'message' => 'Your password has been updated.',
        ], Response::HTTP_ACCEPTED);
    }
}
