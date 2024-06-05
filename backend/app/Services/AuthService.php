<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;

/**
 * Class AuthService.
 */
class AuthService
{
    public static function signIn($credentials)
    {
        $token = AuthService::attemptAuthentication($credentials);

        if (!$token) throwHttpException(config('http_constants.unauthorized'), 'Unauthorized');

        return AuthService::respondWithToken($token);
    }

    public static function signUp($data)
    {
        $userCreated = User::create($data);
        return AuthService::respondWithUserRegistered($userCreated);
    }

    public static function respondWithUserRegistered($user)
    {
        return createResponseData(
            data:['user' => formatUserData($user)], 
            message: 'User Registered',
            status: config('http_constants.created')
        );
    }

    public static function respondWithToken($token)
    {
        return createResponseData(
            data: [
                'token' => $token,
                'expires_in' => auth()->factory()->getTTL() * 60
            ], 
            message: 'Access Granted'
        );
    }

    public static function attemptAuthentication($credentials)
    {
        return auth()->attempt($credentials);
    }

    public static function getAuthenticatedUser()
    {
        return auth()->user();
    }
}
