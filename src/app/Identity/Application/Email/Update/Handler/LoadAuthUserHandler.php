<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Update\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Email\Update\UpdateEmailCommand;
use Illuminate\Support\Facades\Auth;

final class LoadAuthUserHandler extends Handler
{
    /**
     * @phpstan-param UpdateEmailCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws \LogicException
     */
    public function handle(
        UpdateEmailCommand $command, \Closure $next): mixed
    {
        $user = Auth::user();
        
        if (is_null(value: $user)) {
            throw new \LogicException(
                message: 'User is not authenticated.'
            );
        }

        $command->user = $user;

        return $next($command);
    }
}
