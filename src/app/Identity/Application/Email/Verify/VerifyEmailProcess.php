<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Verify;

use App\Shared\Application\Process;
use App\Identity\Application\Email\Verify\Handler\LoadUserByIdHandler;
use App\Identity\Application\Email\Verify\Handler\ValidateEmailNotVerifiedHandler;
use App\Identity\Application\Email\Verify\Handler\ValidateHashHandler;
use App\Identity\Application\Email\Verify\Handler\MarkEmailAsVerifiedHandler;
use App\Shared\Application\Result\Result;
use App\Shared\Application\Handler\HandlerException;
use Illuminate\Support\Facades\Log;

/**
 * @phpstan-extends Process<VerifyEmailQuery, mixed>
 */
final class VerifyEmailProcess extends Process
{
    /**
     * @phpstan-var list<class-string>
     */
    protected array $handlers = [
        LoadUserByIdHandler::class,
        ValidateEmailNotVerifiedHandler::class,
        ValidateHashHandler::class,
        MarkEmailAsVerifiedHandler::class
    ];

    /**
     * @phpstan-param VerifyEmailQuery $query
     * @phpstan-return Result<string, VerifyEmailError>
     */
    public function __invoke(VerifyEmailQuery $query): Result
    {
        try {
            $this->run(payload: $query);

            return Result::success(
                value: 'identity.email.verify.success'
            );
        }

        catch (HandlerException $e) {
            /** @phpstan-var VerifyEmailError $error */
            $error = $e->getError();
            return Result::failure(error: $error);
        }

        catch (\Throwable $e) {
            Log::critical(message: $e::class, context: [
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]);

            return Result::failure(
                error: VerifyEmailError::Unknown
            );
        }
    }
}
