<?php

declare(strict_types=1);

namespace App\Core\Auth\Dto;

use App\Core\Shared\Interfaces\IDto;

final readonly class ForgotPasswordDto implements IDto
{
    public string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public static function fromRequest(\Illuminate\Http\Request $request): self
    {
        return new self(
            $request->input('email')
        );
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
        ];
    }
}
