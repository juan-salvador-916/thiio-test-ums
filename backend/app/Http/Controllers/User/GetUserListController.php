<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminGetUsersDataRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class GetUserListController extends Controller
{
    public function index(AdminGetUsersDataRequest $request)
    {
        $response = UserService::getUsers();
        return jsonResponse($response);
    }
}
