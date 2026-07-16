<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\User;

use App\Shared\Application\UseCase;
use App\Identity\Application\Auth\User\UserExceptionResolver;
use App\Shared\Application\Result\Result;

final class UserUseCase extends UseCase
{
    /**
     * @phpstan-var UserExceptionResolver
     */
    private readonly UserExceptionResolver $exceptionResolver;

    /**
     * @phpstan-param UserHandler $handler
     */
    public function __construct(
        private readonly UserHandler $handler
    ) {
        $this->exceptionResolver = new UserExceptionResolver();
    }

    /**
     * @phpstan-param UserQuery<\App\Identity\Domain\User> $query
     * @phpstan-return Result<UserData, \Throwable>
     */
    public function handle(UserQuery $query): Result
    {
        try {
            $result = $this->handler->execute(query: $query);
            return Result::success(value: $result);
        }

        catch (\Throwable $e) {
            return $this->exceptionResolver->map(e: $e);
        }
    }
}
