<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index(RegisterRequest $request) {
        $user = $this->create($request->validated());

        return $this->sendSuccessResponse([], 'Registration successful! Please log in.');
    }

    public function create($requestData) : void {
        $requestData['password'] = Hash::make($requestData['password']);
        
        User::create($requestData);
    }
}
