<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function registerUser(RegisterUserRequest $request)
    {
        $userData = $request->validated();
        $response = AuthService::signUp($userData);
        return jsonResponse($response);
    }
}
