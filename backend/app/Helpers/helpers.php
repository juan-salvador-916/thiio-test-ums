<?php

use App\Http\Resources\UserResource;

function jsonResponse($dataResponse)
{
    return response()->json($dataResponse, $dataResponse['status']);
}

function createResponseData($data = [], $message = 'OK', $status = 200, $errors = [])
{
    return ['data' => $data, 'message' => $message, 'status' => $status, 'errors' =>  $errors];
}

function throwHttpException($httpStatusCode, $message)
{
    abort($httpStatusCode, $message);
}

function formatUserData($data)
{
    return UserResource::make($data);
}

function mapArrayFormatUsersData($array)
{
    return array_map(fn($user) => [
        'id' => $user['id'],
        'name' => $user['name'],
        'last_name' => $user['last_name'],
        'email' => $user['email'],
        'role' => $user['role']   
    ], $array);
}
