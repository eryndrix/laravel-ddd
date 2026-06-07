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
        /** @phpstan-var \App\Identity\Application\Auth\Login\Output\LoginSuccess $resource */
        $resource = $this->resource;
        $data = $resource->result;

        $accessToken = (string) ($data['access_token'] ?? '');
        $refreshToken = (string) ($data['refresh_token'] ?? '');
        $ttl = (int) ($data['ttl'] ?? 0);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
            'expires_in' => $ttl * 60,
        ];
    }
}
