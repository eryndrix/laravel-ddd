<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Register;

use App\Shared\Application\Job;
use App\Shared\Application\Result\Failure;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

final class RegisterJob extends Job
{
    /**
     * @phpstan-param string $jobId
     * @phpstan-param array<string, mixed> $data
     */
    public function __construct(
        private readonly string $jobId,
        private readonly array $data
    ) {}

    /**
     * @phpstan-param RegisterProcess $process
     * @phpstan-return void
     */
    public function handle(RegisterProcess $process): void
    {
        $command = RegisterCommand::fromArray($this->data);
        $result = $process->run(payload: $command);

        if ($result instanceof Failure) {
            Cache::put(
                key: "register:{$this->jobId}",
                value: $result,
                ttl: Carbon::now()->addSeconds(value: 60)
            );
        }
    }
}
