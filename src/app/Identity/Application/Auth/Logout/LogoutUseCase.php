<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Logout;

use App\Shared\Application\UseCase;
use App\Identity\Application\Auth\Logout\Exception\LogoutExceptionResolver;
use App\Shared\Application\Result\Result;

final class LogoutUseCase extends UseCase
{
    /**
     * @phpstan-var LogoutExceptionResolver
     */
    private readonly LogoutExceptionResolver $exceptionResolver;

    /**
     * @phpstan-param LogoutProcess $process
     */
    public function __construct(
        private readonly LogoutProcess $process
    ) {
        $this->exceptionResolver = new LogoutExceptionResolver();
    }

    /**
     * @phpstan-param LogoutCommand $command
     * @phpstan-return Result<null, \Throwable>
     */
    public function handle(LogoutCommand $command): Result
    {
        try {
            /** @phpstan-ignore method.void */
            $result = $this->process->execute(command: $command);
            return Result::success(value: $result);
        }

        catch (\Throwable $e) {
            return $this->exceptionResolver->map(e: $e);
        }
    }
}
