<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailVerificationRequest;
use Illuminate\Auth\Events\Verified;

class VerifyEmailController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(EmailVerificationRequest $request)
    {

        if ($request->user->hasVerifiedEmail()) {
           /* return redirect()->intended(
                config('app.frontend_url').RouteServiceProvider::HOME.'?verified=1'
            );*/
            return 'already verified';
        }


        if ($request->user->markEmailAsVerified()) {
            event(new Verified($request->user));
        }

        /*return redirect()->intended(
            config('app.frontend_url').RouteServiceProvider::HOME.'?verified=1'
        );*/
        return 'verified go to frontend url';
    }
}
