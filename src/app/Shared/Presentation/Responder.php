<?php declare(strict_types=1);

namespace App\Shared\Presentation;

use App\Shared\Presentation\Response\ApiResponse;
use App\Shared\Application\Result\Result;

/**
 * @phpstan-template TSuccess
 * @phpstan-template TError
 *
 * @phpstan-method ApiResponse respond(Result<TSuccess, TError> $result)
 */
abstract class Responder
{
	/**
     * @phpstan-param Result<TSuccess, TError> $result
     * @phpstan-return ApiResponse
     */
	abstract public function respond(Result $result): ApiResponse;
}
