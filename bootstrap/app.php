<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (Throwable $e, Request $request) {
            // Log critical errors via ResilienceLogger
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface && $e->getStatusCode() >= 500) {
                \App\Core\Services\ResilienceLogger::logCritical($e);
            } elseif (!($e instanceof \Illuminate\Validation\ValidationException) && !($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException)) {
                \App\Core\Services\ResilienceLogger::logCritical($e);
            }

            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'error' => 'System Error',
                    'message' => config('app.debug') ? $e->getMessage() : 'An internal error occurred. Please contact support.',
                    'code' => $e->getCode() ?: 500,
                ], 500);
            }

            return null; // Allow default Laravel rendering for web
        });
    })->create();
