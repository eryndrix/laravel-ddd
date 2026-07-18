<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Update;

use App\Shared\Application\UseCase;
use App\Identity\Application\Email\Update\Exception\UpdateEmailExceptionResolver;
use App\Shared\Application\Result\Result;

final class UpdateEmailUseCase extends UseCase
{
    /**
     * @phpstan-var UpdateEmailExceptionResolver
     */
    private readonly UpdateEmailExceptionResolver $exceptionResolver;

    /**
     * @phpstan-param UpdateEmailProcess $process
     */
    public function __construct(
        private readonly UpdateEmailProcess $process
    ) {
        $this->exceptionResolver = new UpdateEmailExceptionResolver();
    }

    /**
     * @phpstan-param UpdateEmailCommand $command
     * @phpstan-return Result<null, \Throwable>
     */
    public function handle(UpdateEmailCommand $command): Result
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
