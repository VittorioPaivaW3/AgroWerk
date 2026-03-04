<?php

use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule): void {
        // E-mail desativado: mantenha comando sem agendar, ou ajuste aqui se quiser reativar.
        // $schedule->command('alertas:disparar')->hourly();
    })
    ->withMiddleware(function (Middleware $middleware): void {
        // Aliases para middlewares do Spatie Permission
        $middleware->alias([
            // Spatie Permission middlewares (note: namespace é Middleware, no singular)
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (PostTooLargeException $exception, Request $request) {
            $limit = ini_get('post_max_size');

            return back()->withErrors([
                'anexos' => "O upload excedeu o limite total da requisicao ({$limit}). Envie arquivos menores ou em menos quantidade.",
            ]);
        });
    })->create();
