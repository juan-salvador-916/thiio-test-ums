<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class UpdateProfileDataController extends Controller
{
    public function updateProfile(UpdateUserRequest $request)
    {
        $data = $request->validated();
        $response = ProfileService::updateData($data);
        return jsonResponse($response);
    }
}
