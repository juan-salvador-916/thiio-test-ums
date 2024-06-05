<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCreateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class CreateUserController extends Controller
{
    public function create(AdminCreateUserRequest $request)
    {
        $data = $request->validated();
        $response = UserService::createUser($data);
        return jsonResponse($response);
    }
}
