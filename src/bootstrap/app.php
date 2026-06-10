<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use App\Shared\Presentation\Response\ResultResponse;
use Illuminate\Console\Scheduling\Schedule;
use App\Identity\Presentation\RefreshTokensRotator;
use Eryndrix\Middleware\SecurityHeaders;
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
        $middleware->appendToGroup(
            group: 'api',
            middleware: [
                HandleCors::class,
                SecurityHeaders::class,
                ApiRequestLogger::class
            ]
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
