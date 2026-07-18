<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Reset;

use App\Shared\Application\UseCase;
use App\Identity\Application\Password\Reset\Exception\ResetPasswordExceptionResolver;
use App\Shared\Application\Result\Result;

final class ResetPasswordUseCase extends UseCase
{
    /**
     * @phpstan-var ResetPasswordExceptionResolver
     */
    private readonly ResetPasswordExceptionResolver $exceptionResolver;

    /**
     * @phpstan-param ResetPasswordProcess $process
     */
    public function __construct(
        private readonly ResetPasswordProcess $process
    ) {
        $this->exceptionResolver = new ResetPasswordExceptionResolver();
    }

    /**
     * @phpstan-param ResetPasswordCommand $command
     * @phpstan-return Result<null, \Throwable>
     */
    public function handle(ResetPasswordCommand $command): Result
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
