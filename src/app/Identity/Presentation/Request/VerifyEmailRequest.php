<?php declare(strict_types=1);

namespace App\Identity\Presentation\Request;

use Illuminate\Foundation\Http\FormRequest;

final class VerifyEmailRequest extends FormRequest
{
    /**
     * @phpstan-return array<string,
     *     \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string
     * >
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'bail', 'required', 'string:strict'
            ],
            'hash' => [
                'bail', 'required', 'string:strict'
            ],
        ];
    }

    /**
     * @phpstan-return void
     */
    protected function prepareForValidation(): void
    {
        $id = $this->query(key: 'id');
        $hash = $this->query(key: 'hash');

        $userId = is_string(value: $id)
            ? trim(string: $id)
            : '';

        $hash = is_string(value: $hash)
            ? trim(string: $hash)
            : '';

        $this->merge(input: [
            'user_id' => $userId,
            'hash' => $hash
        ]);
    }
}
