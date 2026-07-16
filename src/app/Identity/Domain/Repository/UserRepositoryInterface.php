<?php declare(strict_types=1);

namespace App\Identity\Domain\Repository;

use App\Shared\Domain\Id\UserId;
use App\Identity\Domain\Access\TokenHash;
use App\Identity\Domain\Email\Email;
use App\Identity\Domain\User;

interface UserRepositoryInterface
{
    /**
     * @phpstan-param UserId $id
     * @phpstan-return User|null
     */
    public function findById(UserId $id): ?User;

    /**
     * @phpstan-param Email $email
     * @phpstan-return User|null
     */
    public function findByEmail(Email $email): ?User;

    /**
     * @phpstan-param UserId $id
     * @phpstan-param TokenHash $token
     * 
     * @phpstan-return User|null
     */
    public function findByToken(
        UserId $id, TokenHash $token): ?User;
    
    /**
     * @phpstan-param User $user
     * @phpstan-return void
     */
    public function save(User $user): void;
    
    /**
     * @phpstan-param User $user
     * @phpstan-return void
     */
    public function remove(User $user): void;
}
