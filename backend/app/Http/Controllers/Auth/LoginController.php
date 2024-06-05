<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $response = AuthService::signIn($credentials);
        return jsonResponse($response);
    }

}
