<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login;

use App\Shared\Application\UseCase;
use App\Identity\Application\Auth\Login\Exception\LoginExceptionResolver;
use App\Shared\Application\Result\Result;

final class LoginUseCase extends UseCase
{
    /**
     * @phpstan-var LoginExceptionResolver
     */
    private readonly LoginExceptionResolver $exceptionResolver;

    /**
     * @phpstan-param LoginProcess $process
     */
    public function __construct(
        private readonly LoginProcess $process
    ) {
        $this->exceptionResolver = new LoginExceptionResolver();
    }

    /**
     * @phpstan-param LoginCommand $command
     * @phpstan-return Result<
     *     \App\Identity\Application\Auth\Token\TokenData,
     *     \Throwable
     * >
     */
    public function handle(LoginCommand $command): Result
    {
        try {
            $result = $this->process->execute(command: $command);
            return Result::success(value: $result);
        }

        catch (\Throwable $e) {
            return $this->exceptionResolver->map(e: $e);
        }
    }
}
