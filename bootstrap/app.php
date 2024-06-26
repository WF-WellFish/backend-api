<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                if($e instanceof  AuthenticationException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage(),
                        'data' => new \stdClass()
                    ], Response::HTTP_UNAUTHORIZED);
                }

                if($e instanceof NotFoundHttpException && $e->getPrevious() instanceof ModelNotFoundException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Data not found',
                        'data' => new \stdClass()
                    ], Response::HTTP_NOT_FOUND);
                }

                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'data' => new \stdClass()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        });
    })->create();
