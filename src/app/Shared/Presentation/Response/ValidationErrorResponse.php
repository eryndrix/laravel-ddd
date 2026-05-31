<?php declare(strict_types=1);

namespace App\Shared\Presentation\Response;

use Illuminate\Http\Response as Status;
use Illuminate\Support\MessageBag;

final class ValidationErrorResponse extends Response
{
    /**
     * @phpstan-param MessageBag $errors
     */
    public function __construct(
        private readonly MessageBag $errors
    ) {
        parent::__construct(
            status: Status::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * @phpstan-return array{
     *     data: array{
     *         message: string,
     *         errors: MessageBag
     *     }
     * }
     */
    public function data(): array
    {
        return [
            'data' => [
                'message' => 'Validation error.',
                'errors'  => $this->errors
            ],
        ];
    }
}
