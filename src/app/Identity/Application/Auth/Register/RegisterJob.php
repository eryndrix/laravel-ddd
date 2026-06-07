<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Register;

use App\Shared\Application\Job;

final class RegisterJob extends Job
{
    /**
     * @phpstan-param RegisterCommand $command
     */
    public function __construct(
        private readonly RegisterCommand $command
    ) {}

    /**
     * @phpstan-param RegisterProcess $process
     * @phpstan-return void
     */
    public function handle(RegisterProcess $process): void
    {
        $process->run(payload: $this->command);
    }
}
