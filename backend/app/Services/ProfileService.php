<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

/**
 * Class ProfileService.
 */
class ProfileService
{

    public static function updateData($data)
    {
        $authenticatedUser = AuthService::getAuthenticatedUser();
        $authenticatedUser->update($data);
        $user = formatUserData($authenticatedUser->fresh());
        return ProfileService::respondWithProfileDataUpdated($user);
    }

    public static function updatePassword($password)
    {
        $authenticatedUser = AuthService::getAuthenticatedUser();
        $authenticatedUser->update([
            'password' => Hash::make($password)
        ]);
        $user = formatUserData($authenticatedUser->fresh());
        return ProfileService::respondWithProfilePasswordUpdated($user);
    }


    public static function respondWithProfileDataUpdated($user)
    {
        return createResponseData(data: ['user' => $user], message: 'Profile Updated');
    }

    public static function respondWithProfilePasswordUpdated($user)
    {
        return createResponseData(data: ['user' => $user], message: 'Profile Password Updated');
    }
}
