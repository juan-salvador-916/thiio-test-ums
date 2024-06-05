<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUpdateUserDataRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UpdateUserController extends Controller
{
    public function update(AdminUpdateUserDataRequest $request, string $id)
    {
        $response = UserService::updateUser($id, $request->validated());
        return jsonResponse($response);
    }

}
