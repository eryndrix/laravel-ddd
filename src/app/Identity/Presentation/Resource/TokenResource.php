<?php declare(strict_types=1);

namespace App\Identity\Presentation\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

/**
 * @mixin \App\Identity\Application\Auth\Login\Output\LoginSuccess
 */
final class TokenResource extends JsonResource
{
    /**
     * @phpstan-param Request $request
     * @phpstan-return array{
     *   access_token: string,
     *   refresh_token: string,
     *   token_type: string,
     *   expires_in: int
     * }
     */
    public function toArray(Request $request): array
    {
        /** @phpstan-var array{access_token: string, refresh_token: string, ttl: int} $data */
        $data = $this->resource;

        return [
            'access_token' => (string) ($data['access_token'] ?? ''),
            'refresh_token' => (string) ($data['refresh_token'] ?? ''),
            'token_type' => 'Bearer',
            'expires_in' => (int) ($data['ttl'] ?? 0) * 60,
        ];
    }
}
