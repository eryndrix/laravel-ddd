<?php declare(strict_types=1);

namespace App\Identity\Presentation\Auth\Token\Refresh;

use App\Shared\Presentation\Request;

final class RefreshTokenRequest extends Request
{
	/**
     * @phpstan-return array<string,
     *     \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string
     * >
     */
    public function rules(): array
    {
        return [
            'plain_refresh_token' => [
                'bail',
                'required',
                'string',
                'filled',
                'regex:/^[A-Za-z0-9\-_]+\.[A-Za-z0-9\-_]+\.[A-Za-z0-9\-_]+$/',
            ],
        ];
    }

    /**
     * @phpstan-return void
     */
    protected function prepareForValidation(): void
    {
        $refreshToken = $this->header(key: 'X-Refresh-Token');

        $this->merge(input: [
            'plain_refresh_token' => is_string(value: $refreshToken)
                ? $refreshToken
                : '',
        ]);
    }
}
