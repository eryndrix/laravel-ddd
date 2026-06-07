<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login\Output;

final class LoginSuccess
{
    /**
     * @phpstan-param array{
     *   access_token: string,
     *   refresh_token: string,
     *   ttl: int
     * } $result
     */
    public function __construct(
        public private(set) array $result
    ) {}
}
