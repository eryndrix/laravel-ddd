<?php declare(strict_types=1);

namespace App\Identity\Application\Profile\Update\Password\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Profile\Update\Password\UpdatePasswordCommand;
use Illuminate\Support\Facades\Auth;

final class LoadAuthUserHandler extends Handler
{
    /**
     * @phpstan-param UpdatePasswordCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws \LogicException
     */
    public function handle(
        UpdatePasswordCommand $command, \Closure $next): mixed
    {
        $auth = Auth::user();
        $user = $auth->unwrap();
        
        if (is_null(value: $user)) {
            throw new \LogicException();
        }

        $command->user = $user;

        return $next($command);
    }
}
