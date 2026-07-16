<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Verify;

use App\Shared\Application\UseCase;
use App\Identity\Application\Email\Verify\Exception\VerifyEmailExceptionResolver;
use App\Shared\Application\Result\Result;

final class VerifyEmailUseCase extends UseCase
{
    /**
     * @phpstan-var VerifyEmailExceptionResolver
     */
    private readonly VerifyEmailExceptionResolver $exceptionResolver;

    /**
     * @phpstan-param VerifyEmailProcess $process
     */
    public function __construct(
        private readonly VerifyEmailProcess $process
    ) {
        $this->exceptionResolver = new VerifyEmailExceptionResolver();
    }

    /**
     * @phpstan-param VerifyEmailQuery $query
     * @phpstan-return Result<null, \Throwable>
     */
    public function handle(VerifyEmailQuery $query): Result
    {
        try {
            /** @phpstan-ignore method.void */
            $result = $this->process->execute(query: $query);
            return Result::success(value: $result);
        }

        catch (\Throwable $e) {
            return $this->exceptionResolver->map(e: $e);
        }
    }
}
