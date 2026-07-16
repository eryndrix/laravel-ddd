<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token\Refresh;

use App\Shared\Application\UseCase;
use App\Identity\Application\Auth\Token\Refresh\Exception\RefreshTokenExceptionResolver;
use App\Shared\Application\Result\Result;

final class RefreshTokenUseCase extends UseCase
{
    /**
     * @phpstan-var RefreshTokenExceptionResolver
     */
    private readonly RefreshTokenExceptionResolver $exceptionResolver;

    /**
     * @phpstan-param RefreshTokenProcess $process
     */
    public function __construct(
        private readonly RefreshTokenProcess $process
    ) {
        $this->exceptionResolver = new RefreshTokenExceptionResolver();
    }

    /**
     * @phpstan-param RefreshTokenCommand $command
     * @phpstan-return Result<
     *     \App\Identity\Application\Auth\Token\TokenData,
     *     \Throwable
     * >
     */
    public function handle(RefreshTokenCommand $command): Result
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
