<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailVerification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ResendEmailVerificationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        if (!is_null(auth()->user()->email_verified_at)) {
            return response()->json([
                'message' => 'Your email is already verified',
            ]);
        }

        SendEmailVerification::dispatch(auth()->user());
        return response()->json([
            'message' => 'Email verification notification sent.',
        ], Response::HTTP_ACCEPTED);
    }
}
