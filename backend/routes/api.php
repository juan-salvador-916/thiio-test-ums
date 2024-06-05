<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login',           [App\Http\Controllers\Auth\LoginController::class,                                 'login']);

Route::get('/users',            [App\Http\Controllers\User\GetUserListController::class,                           'index']);
Route::get('/users/{id}',       [App\Http\Controllers\User\GetUserController::class,                               'show']);
Route::post('/users',           [App\Http\Controllers\User\CreateUserController::class,                            'create']);
Route::put('/users/{id}',       [App\Http\Controllers\User\UpdateUserController::class,                            'update']);
Route::delete('/users/{id}',    [App\Http\Controllers\User\DeleteUserController::class,                            'destroy']);

Route::post('/register-user',   [App\Http\Controllers\Auth\RegisterController::class,                              'registerUser']);

Route::put('/profile',          [App\Http\Controllers\Profile\UpdateProfileDataController::class,                  'updateProfile']);

Route::put('/password',         [App\Http\Controllers\Profile\UpdateProfilePasswordController::class,              'updatePassword']);
