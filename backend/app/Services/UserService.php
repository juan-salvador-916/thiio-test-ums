<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserService.
 */
class UserService
{
    public static function createUser($data)
    {
        $user = User::create($data);
        return UserService::respondWithUserCreated($user);
    }

    public static function respondWithUserCreated($user)
    {
        return createResponseData(
            data:['user' => formatUserData($user)], 
            message: 'User Created',
            status: config('http_constants.created')
        );
    }

    public static function deleteUser($id)
    {
        $user = User::find($id);
        if(!$user){
            throwHttpException(config('http_constants.not_found'), 'User not found');
        }
        $authenticatedUser = AuthService::getAuthenticatedUser();
        if($user->id === $authenticatedUser->id){
            throwHttpException(config('http_constants.forbidden'), 'You cannot delete your own user');
        }
        $adminUserId = 1;
        if($user->id === $adminUserId){
            throwHttpException(config('http_constants.forbidden'), 'You cannot delete the admin root user');
        }
        $user->delete();
        return UserService::respondWithUserDeleted();
    } 

    public static function respondWithUserDeleted()
    {
        return createResponseData( 
            message: 'User Deleted'
        );
    }

    public static function getUser($id)
    {
        $user = User::find($id);
        if(!$user){
            throwHttpException(config('http_constants.not_found'), 'User not found');
        }
        return UserService::respondWithUserData($user);
    }

    public static function respondWithUserData($user)
    {
        return createResponseData( 
            data: ['user' => formatUserData($user)]
        );
    }

    public static function getUsers()
    {
        $users = User::all()->toArray();
        return UserService::respondWithUsersList($users);
    }

    public static function respondWithUsersList($users)
    {
        return createResponseData( 
            data: ['users' => mapArrayFormatUsersData($users)]
        );
    }

    public static function updateUser($id, $fields)
    {
        $user = User::find($id);
        if(!$user){
            throwHttpException(config('http_constants.not_found'), 'User not found');
        }
        $userUpdated = UserService::updateUserFields($fields, $user);
        return UserService::respondWithUserUpdated($userUpdated);
    }

    public static function updateUserFields($fields, $user)
    {
        //dd($user);
        foreach ($fields as $key => $value) {
            if ($key === 'id') continue;
            if ($key === 'password') {
                $user[$key] = Hash::make($value);
            } else {
                $user[$key] = $value;
            }
        }
        $user->save();
        $userUpdated = $user->fresh();
        
        return $userUpdated;
    }

    public static function respondWithUserUpdated($user)
    {
        return createResponseData( 
            data: ['user' => formatUserData($user)],
            message: 'User Updated',
        );
    }

}
