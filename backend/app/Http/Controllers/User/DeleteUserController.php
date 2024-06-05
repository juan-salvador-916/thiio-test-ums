<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminDeleteUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class DeleteUserController extends Controller
{
    public function destroy(AdminDeleteUserRequest $request, string $id)
    {
        $response = UserService::deleteUser($id);
        return jsonResponse($response);
    }

}
