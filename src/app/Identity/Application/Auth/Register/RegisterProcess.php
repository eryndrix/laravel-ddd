<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Register;

use App\Shared\Application\Process;
use App\Identity\Application\Auth\Register\Handler\AttachDefaultRoleHandler;
use App\Identity\Application\Auth\Register\Handler\RegisterUserHandler;
use App\Shared\Application\Result\Result;
use Illuminate\Support\Facades\{Cache, Log};
use Illuminate\Support\Str;

/**
 * @phpstan-extends Process<
 *     RegisterCommand,
 *     Result<string, RegisterError>
 * >
 */
final class RegisterProcess extends Process
{
    /**
     * @phpstan-var string
     */
    private const SUCCESS = 'auth.registration.success';
    
    /**
     * @phpstan-var list<class-string>
     */
    protected array $handlers = [
        AttachDefaultRoleHandler::class,
        RegisterUserHandler::class
    ];

    /**
     * @phpstan-param RegisterCommand $command
     * @phpstan-return Result<string, RegisterError>
     */
    public function __invoke(RegisterCommand $command): Result
    {
        try {
            $jobId = Str::uuid7()->toString();
            /** @phpstan-var array<string, mixed> $data */
            $data = $command->toArray();

            dispatch_sync(
                new RegisterJob(jobId: $jobId, data: $data)
            );

            /**
             * @phpstan-var \App\Shared\Application\Result\Failure<
             *     RegisterError
             * > $result
             */
            $result = Cache::get(key: "register:{$jobId}");

            if ($result->isFailure()) {
                return $result;
            }

            return Result::success(value: self::SUCCESS);
        }

        catch (\Throwable $e) {
            Log::critical(message: $e::class, context: [
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]);

            return Result::failure(
                error: RegisterError::Unknown
            );
        }
    }
}
