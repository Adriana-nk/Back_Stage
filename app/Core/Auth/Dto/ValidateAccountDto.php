<?php

declare(strict_types=1);

namespace App\Core\Auth\Dto;

use App\Core\Shared\Interfaces\IDto;

final readonly class ValidateAccountDto implements IDto
{
    public string $email;
    public string $validation_code;

    public function __construct(string $email, string $validation_code)
    {
        $this->email = $email;
        $this->validation_code = $validation_code;
    }

    public static function fromRequest(\Illuminate\Http\Request $request): self
    {
        return new self(
            $request->input('email'),
            $request->input('validation_code')
        );
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'validation_code' => $this->validation_code,
        ];
    }
}
