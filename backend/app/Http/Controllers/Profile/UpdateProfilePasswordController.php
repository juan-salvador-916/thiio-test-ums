<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UpdateProfilePasswordController extends Controller
{
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $password = $request->get('password');
        $response = ProfileService::updatePassword($password);
        return jsonResponse($response);
    }
}
