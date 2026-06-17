<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Verify;

use Illuminate\Http\Request;
use App\Shared\Application\Query\Query;
use App\Identity\Domain\User;

final class VerifyEmailQuery extends Query
{
    /**
     * @phpstan-param string $userId
     * @phpstan-param string $hash
     */
    public function __construct(
        public private(set) string $userId,
        public private(set) string $hash,
        public private(set) ?User $user = null
    ) {}

    /**
     * @phpstan-param Request $request
     * @phpstan-return self
     */
    public static function fromRequest(Request $request): self
    {
        $userId = $request->string(key: 'user_id');
        $hash = $request->string(key: 'hash');

        return new self(
            userId: $userId->trim()->toString(),
            hash: $hash->trim()->toString(),
        );
    }

    /**
     * @phpstan-param User $user
     * @phpstan-return self
     */
    public function withUser(User $user): self
    {
        $clone = clone $this;
        $clone->user = $user;

        return $clone;
    }
}
