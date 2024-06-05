<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: 'api/v1',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ValidationException $throwable) {
            return jsonResponse([
                'data' => [],
                'message' => $throwable->getMessage(),
                'status' => config('http_constants.unprocessable_entity'),
                'errors' => $throwable->errors()
            ]);
        });
        
        $exceptions->render(function (HttpException $throwable) {
            return jsonResponse([
                'data' => [],
                'message' => $throwable->getMessage(),
                'status' => $throwable->getStatusCode(),
                'errors' => [$throwable->getMessage()]
            ]);
        });
        

    })->create();
