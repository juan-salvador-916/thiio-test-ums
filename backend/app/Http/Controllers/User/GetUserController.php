<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminGetUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class GetUserController extends Controller
{
    public function show(AdminGetUserRequest $request, string $id)
    {
        $response = UserService::getUser($id);
        return jsonResponse($response);
    }
}
