<?php declare(strict_types=1);

namespace App\Identity\Presentation\Auth\Check;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @mixin \App\Identity\Application\Auth\Check\UserData
 */
final class UserResource extends JsonResource
{
    /**
     * @phpstan-param Request $request
     * @phpstan-return array{
     *   id: string,
     *   avatar: string|null,
     *   first_name: string,
     *   last_name: string,
     *   email: string,
     *   role_id: string
     * }
     */
    public function toArray(Request $request): array
    {
        /** @phpstan-var \App\Identity\Application\Auth\Check\UserData $user */
        $user = $this->resource;

        return [
            'id' => $user->id->value(),
            'avatar' => $user->avatar?->value(),
            'first_name' => $user->firstName,
            'last_name' => $user->lastName,
            'email' => $user->email->value(),
            'role_id' => $user->roleId->value()
        ];
    }
}
