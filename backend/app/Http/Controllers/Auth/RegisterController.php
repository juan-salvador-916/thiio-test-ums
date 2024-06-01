<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function registerUser(RegisterUserRequest $request)
    {
        $user = User::create($request->all());
        return jsonResponse(data: ['user' => UserResource::make($user)]);
    }
}
