<?php declare(strict_types=1);

namespace App\Identity\Presentation\Auth\Token;

use Illuminate\Console\Command;
use App\Identity\Domain\Repository\TokenRepositoryInterface;
use App\Shared\Domain\Contract\UnitOfWorkInterface;

final class RefreshTokensRotator extends Command
{
    /**
     * @phpstan-var string
     */
    protected $signature = 'tokens:cleanup {--dry-run}';
    
    /**
     * @phpstan-var string
     */
    protected $description = 'Cleanup expired refresh tokens';

    /**
     * @phpstan-param TokenRepositoryInterface $repository
     */
    public function __construct(
        private TokenRepositoryInterface $repository
    ) {
        parent::__construct();
    }

    /**
     * @phpstan-param UnitOfWorkInterface<mixed> $unitOfWork
     * @phpstan-return int
     */
    public function handle(UnitOfWorkInterface $unitOfWork): int
    {
        $now = new \DateTimeImmutable();
        $tokens = $this->repository->allExpired(now: $now);
        $count = count(value: $tokens);

        if ($this->option(key: 'dry-run') === true) { 
            $this->warn(
                string: 'Dry run mode - no tokens will be deleted.'
            );

            $this->info(
                string: "Would clean up {$count} expired refresh tokens."
            );
            
            return self::SUCCESS;
        }

        foreach ($tokens as $token) {
            $this->repository->remove(token: $token);
        }
        
        $unitOfWork->flush();
        
        $this->info(
            string: "Cleaned up {$count} expired refresh tokens."
        );
        
        return self::SUCCESS;
    }
}
