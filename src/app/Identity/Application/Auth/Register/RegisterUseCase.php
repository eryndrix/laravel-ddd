<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Register;

use App\Shared\Application\UseCase;
use App\Identity\Application\Auth\Register\Exception\RegisterExceptionResolver;
use App\Shared\Application\Result\Result;

final class RegisterUseCase extends UseCase
{
    /**
     * @phpstan-var RegisterExceptionResolver
     */
    private readonly RegisterExceptionResolver $exceptionResolver;

    /**
     * @phpstan-param RegisterProcess $process
     */
    public function __construct(
        private readonly RegisterProcess $process
    ) {
        $this->exceptionResolver = new RegisterExceptionResolver();
    }

    /**
     * @phpstan-param RegisterCommand $command
     * @phpstan-return Result<null, \Throwable>
     */
    public function handle(RegisterCommand $command): Result
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
