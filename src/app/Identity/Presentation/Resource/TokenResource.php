<?php declare(strict_types=1);

namespace App\Identity\Presentation\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

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
        /** @phpstan-var array<string, int> $tokensData */
        $tokensData = $this->resource;

        return [
            'access_token' => (string) ($tokensData['access_token'] ?? ''),
            'refresh_token' => (string) ($tokensData['refresh_token'] ?? ''),
            'token_type' => 'Bearer',
            'expires_in' => (int) ($tokensData['ttl'] ?? 0) * 60,
        ];
    }
}
