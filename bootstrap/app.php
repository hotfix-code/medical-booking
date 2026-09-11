<?php

use App\Http\Middleware\SetLocale;
use App\Support\AppResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: SetLocale::class);
        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_FOR |
            Request::HEADER_X_FORWARDED_HOST |
            Request::HEADER_X_FORWARDED_PORT |
            Request::HEADER_X_FORWARDED_PROTO |
            Request::HEADER_X_FORWARDED_PREFIX
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (HttpException $e, Request $request) {
            if (in_array($e->getStatusCode(), [404, 419], true)) {
                app(SetLocale::class)->apply($request);
            }

            if ($e instanceof NotFoundHttpException && $request->expectsJson()) {
                return AppResponse::error([
                    'resource' => __('messages.not_found')
                ], status: 404);
            }
        });
    })
    ->booting(function () {
        if (app()->environment('production'))
        {
            URL::forceScheme('https');
        }
    })
    ->create();
