<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request) {
        return $this->sendSuccessResponse(new UserResource(auth()->user()), 'User profile retrieved successfully.');
    }

    public function update(ProfileRequest $request) {
        $user = auth()->user();
        $user->update($request->validated());

        return $this->sendSuccessResponse(new UserResource($user), 'User profile updated successfully.');
    }
}
