<?php declare(strict_types=1);

namespace App\Shared\Application;

use App\Shared\Domain\Storage\UnitOfWorkInterface;
use Illuminate\Pipeline\Pipeline;

/**
 * @phpstan-template TPayload of mixed
 * @phpstan-template TResult
 */
abstract class Process
{
    /**
     * @phpstan-var list<class-string>
     */
    protected array $handlers = [];

    /**
     * @phpstan-var bool
     */
    protected bool $useTransaction = true;

    /**
     * @phpstan-param TPayload $payload
     * @phpstan-return TResult
     */
    public function run(mixed $payload)
    {
        $unitOfWork = app()->make(
            abstract: UnitOfWorkInterface::class
        );

        $process = resolve(name: Pipeline::class)
            ->send(passable: $payload)
            ->through(pipes: $this->handlers);
    
        if (!$this->useTransaction) {
            /** @phpstan-var TResult */
            $result = $process->thenReturn();
            $unitOfWork->flush();
            
            return $result;
        }

        /** @phpstan-var TResult */
        return $unitOfWork->transactional(
            callback: fn() => $process->thenReturn()
        );
    }
}
