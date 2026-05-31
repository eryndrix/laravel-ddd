<?php declare(strict_types=1);

namespace App\Shared\Presentation;

use Illuminate\Foundation\Http\FormRequest;
use App\Shared\Presentation\Response\ValidationErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class Request extends FormRequest
{
    /**
     * @phpstan-return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @phpstan-return array<string,
     *     \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string
     * >
     */
    abstract public function rules(): array;

    /**
     * @phpstan-param Validator $validator
     * @phpstan-return void
     */
    protected function failedValidation(Validator $validator): void
    {
        $response = new ValidationErrorResponse(
            errors: $validator->errors()
        );
        
        throw new HttpResponseException(
            response: $response->toResponse(request: $this)
        );
    }
}
