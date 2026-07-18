<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Verify;

use App\Shared\Application\Process;
use App\Identity\Application\Email\Verify\Handler\LoadUserByIdHandler;
use App\Identity\Application\Email\Verify\Handler\ValidateEmailNotVerifiedHandler;
use App\Identity\Application\Email\Verify\Handler\ValidateHashHandler;
use App\Identity\Application\Email\Verify\Handler\MarkEmailAsVerifiedHandler;

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
     * @phpstan-return void
     */
    public function execute(VerifyEmailQuery $query): void
    {
        $this->run(payload: $query);
    }
}
