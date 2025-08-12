<?php

declare(strict_types=1);

namespace App\Core\Auth\Dto;

use App\Core\Shared\Interfaces\IDto;

final readonly class ForgotPasswordRequestDto implements IDto
{
    public ?string $email;
    public ?string $telephone;

    public function __construct(?string $email = null, ?string $telephone = null)
    {
        $this->email = $email;
        $this->telephone = $telephone;
    }

    public static function fromRequest(\Illuminate\Http\Request $request): self
    {
        return new self(
            $request->input('email'),
            $request->input('telephone')
        );
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'telephone' => $this->telephone,
        ];
    }
}
