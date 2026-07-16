<?php declare(strict_types=1);

namespace App\Shared\Infrastructure;

use App\Shared\Domain\Contract\UnitOfWorkInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @implements UnitOfWorkInterface<mixed>
 * @phpstan-impure
 */
final class UnitOfWork implements UnitOfWorkInterface
{
    /**
     * @phpstan-param EntityManagerInterface $entityManager
     */
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @phpstan-return void
     * @phpstan-impure
     */
    public function flush(): void
    {
        $this->entityManager->flush();
    }
    
    /**
     * @phpstan-param callable():mixed $callback
     * @phpstan-return mixed
     * @phpstan-impure
     */
    public function transactional(callable $callback): mixed
    {
        $conn = $this->entityManager->getConnection();
        $conn->beginTransaction();

        try {
            $result = $callback();
            $this->entityManager->flush();
            $conn->commit();

            return $result;
        }

        catch (\Throwable $e) {
            if ($conn->isTransactionActive()) {
                $conn->rollBack();
            }

            throw $e;
        }
    }
}
