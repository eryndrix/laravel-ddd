<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Update\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Password\Update\UpdatePasswordCommand;
use App\Shared\Application\Handler\HandlerException;
use App\Identity\Application\Password\Update\UpdatePasswordError;
use Illuminate\Support\Facades\Auth;

final class LoadAuthUserHandler extends Handler
{
    /**
     * @phpstan-param UpdatePasswordCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws HandlerException<UpdatePasswordError>
     */
    public function handle(
        UpdatePasswordCommand $command, \Closure $next): mixed
    {
        $user = Auth::user();
        
        if (is_null(value: $user)) {
            throw new HandlerException(
                error: UpdatePasswordError::Failed
            );
        }

        $command->user = $user;

        return $next($command);
    }
}
