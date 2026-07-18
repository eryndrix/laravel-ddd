<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Forgot;

use App\Shared\Application\UseCase;
use App\Identity\Application\Password\Forgot\Exception\ForgotPasswordExceptionResolver;
use App\Shared\Application\Result\Result;

final class ForgotPasswordUseCase extends UseCase
{
    /**
     * @phpstan-var ForgotPasswordExceptionResolver
     */
    private readonly ForgotPasswordExceptionResolver $exceptionResolver;

    /**
     * @phpstan-param ForgotPasswordProcess $process
     */
    public function __construct(
        private readonly ForgotPasswordProcess $process
    ) {
        $this->exceptionResolver = new ForgotPasswordExceptionResolver();
    }

    /**
     * @phpstan-param ForgotPasswordCommand $command
     * @phpstan-return Result<null, \Throwable>
     */
    public function handle(ForgotPasswordCommand $command): Result
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
