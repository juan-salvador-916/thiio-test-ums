<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function registerUser(RegisterUserRequest $request)
    {
        $user = User::create($request->all());
        return jsonResponse(data: ['user' => $user]);
    }
}
