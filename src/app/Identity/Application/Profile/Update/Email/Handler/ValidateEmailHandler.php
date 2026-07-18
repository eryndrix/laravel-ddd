<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Update\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Email\Update\UpdateEmailCommand;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Identity\Domain\Access\Auth\UserAdapterInterface;
use App\Identity\Application\Email\Update\Exception\EmailUnchangedException;
use App\Shared\Application\Exception\UserNotFoundException;
use Illuminate\Support\Facades\Auth;

final class ValidateEmailHandler extends Handler
{
    /**
     * @phpstan-param UpdateEmailCommand $command
     * @phpstan-param \Closure(UpdateEmailCommand):mixed $next
     *
     * @phpstan-return mixed
     *
     * @throws UserNotFoundException
     * @throws EmailUnchangedException
     */
    public function handle(
        UpdateEmailCommand $command, \Closure $next): mixed
    {
        $authUser = Auth::user();

        if (!$authUser instanceof Authenticatable
            || !$authUser instanceof UserAdapterInterface
        ) {
            throw new UserNotFoundException();
        }

        $user = $authUser->unwrap();

        if ($user->email->value() === $command->email) {
            throw new EmailUnchangedException();
        }

        $command->user = $user;

        return $next($command);
    }
}
