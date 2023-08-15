<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Auth\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProfileController extends Controller
{
    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function show(Request $request)
    {
        return response()->json($request->user()->only(['name', 'email']));
    }

    /**
     * @param  UpdateProfileRequest  $request
     * @return JsonResponse
     */
    public function update(UpdateProfileRequest $request)
    {
        auth()->user()->update($request->validated());

        return response()->json(auth()->user()->only(['name', 'email']), Response::HTTP_ACCEPTED);
    }
}
