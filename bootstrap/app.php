<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
        // 1. Jika yang meminta adalah Flutter/Postman (Header Accept: application/json)
            if ($request->expectsJson()) {
                abort(response()->json([
                    'status' => 'error',
                    'pesan'  => 'Anda tidak memiliki akses (Unauthenticated).'
                ], 401));
            }
            // Kembalikan ke String URL untuk middleware ini
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            // Cek apakah ini Flutter (bukan Browser)
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Method tidak diizinkan untuk endpoint ini.'
                ], 405);
            }
            // Jika browser, arahkan pakai objek Redirect
            return redirect()->route('login');
        });
    })->create();
