<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login;

use App\Shared\Application\Job;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

final class LoginJob extends Job
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
     * @phpstan-param LoginProcess $process
     */
    public function handle(LoginProcess $process): void
    {
        $command = LoginCommand::fromArray($this->data);
        $result = $process->run(payload: $command);

        /** @phpstan-var array{access_token: string, access_token_ttl: int, refresh_token: string, refresh_token_ttl: int} $token */
        $token = $command->token;

        Cache::put(
            key: "login:{$this->jobId}",
            value: [
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'],
                'ttl' => $token['access_token_ttl']
            ],
            ttl: Carbon::now()->addSeconds(value: 60)
        );
    }
}
