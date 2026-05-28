<?php

use App\Exceptions\ApiException;
use App\Helpers\ResponseHelper;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // log report
        $exceptions->report(function (Throwable $e) {
            if ($e instanceof AuthenticationException) {
                return false;
            }

            if ($e instanceof AccessDeniedHttpException) {
                return false;
            }

            if ($e instanceof HttpExceptionInterface && $e->getStatusCode() < 500) {
                return false;
            }

            Log::channel('api')->error('API Exception', [
                'type'    => class_basename($e),
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'url'     => request()->fullUrl(),
                'method'  => request()->method(),
                'ip'      => request()->ip(),
            ]);

            return false;
        });

        // log render
        $exceptions->render(function (Throwable $e, Request $request) {
            if (!$request->is('api/*')) {
                return null;
            }

            // custom api exception
            if ($e instanceof ApiException) {
                return ResponseHelper::error($e->getMessage(), [$e->errors], $e->status);
            }

            if ($e instanceof AuthenticationException) {
                return ResponseHelper::error("Unauthorized", ['exception' => 'Invalid otentikasi'], 401);
            }

            if ($e instanceof AccessDeniedHttpException) {
                return ResponseHelper::error("Akses tidak diperbolehkan", null, 403);
            }

            if ($e instanceof HttpExceptionInterface) {
                return ResponseHelper::error($e->getMessage() ?? 'Terjadi Kesalahan', ['exception' => class_basename($e)], $e->getStatusCode());
            }

            return ResponseHelper::error($e->getMessage(), ['exception' => class_basename($e)], 500);
        });
    })->create();
