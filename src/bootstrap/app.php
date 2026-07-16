<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Console\Scheduling\Schedule;
use App\Identity\Presentation\Auth\Token\RefreshTokensRotator;
use Eryndrix\Middleware\SecurityHeaders;
use WendellAdriel\Idempotency\Http\Middleware\Idempotent;
use Eryndrix\Middleware\ApiRequestLogger;
use Illuminate\Http\Request;

return Application::configure(
    basePath: dirname(__DIR__)
)->withRouting(
    web: __DIR__ . '/../routes/web.php',
    api: __DIR__ . '/../routes/api.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
)->withMiddleware(
    callback: function (Middleware $middleware): void {
        $middleware->alias(aliases: [
            'idempotent' => Idempotent::class
        ]);

        $middleware->appendToGroup(
            group: 'api',
            middleware: [
                HandleCors::class,
                SecurityHeaders::class,
                ApiRequestLogger::class
            ]
        );

        $middleware->redirectGuestsTo(
            redirect: fn (Request $request): null => null
        );
    }
)->withExceptions(
    using: function (Exceptions $exceptions): void {
        $exceptions->level(
            type: \PDOException::class,
            level: \Psr\Log\LogLevel::CRITICAL
        );
    }
)->withSchedule(
    callback: function (Schedule $schedule): void {
        $schedule->command(
            command: 'tokens:cleanup'
        )->dailyAt(
            time: '00:00'
        );
    }
)->withCommands(
    commands: [RefreshTokensRotator::class]
)->create();
