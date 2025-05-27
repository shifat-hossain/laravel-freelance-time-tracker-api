<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function authenticated(LoginRequest $request) {
        if (! auth()->attempt($request->validated())) {
            return $this->sendErrorResponse('These credentials do not match our records.', 401);
        }
        
        $data['user'] = new UserResource(auth()->user());
        $data['token_type'] = 'Bearer';
        $data['token'] = auth()->user()->createToken('Time Tracker API')->plainTextToken;
        
        return $this->sendSuccessResponse($data, 'Login successful');
    }

    public function logout() {
        auth()->user()->tokens()->delete();
        return $this->sendSuccessResponse([], 'Logout successful');
    }
}
