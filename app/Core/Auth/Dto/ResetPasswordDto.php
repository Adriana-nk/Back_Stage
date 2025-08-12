<?php

declare(strict_types=1);

namespace App\Core\Auth\Dto;

use App\Core\Shared\Interfaces\IDto;

final readonly class ResetPasswordDto implements IDto
{
    public string $token;
    public string $password;
    public string $password_confirmation;
    public string $email;

    public function __construct(string $token, string $password, string $password_confirmation, string $email)
    {
        $this->token = $token;
        $this->password = $password;
        $this->password_confirmation = $password_confirmation;
        $this->email = $email;
    }

    public static function fromRequest(\Illuminate\Http\Request $request): self
    {
        return new self(
            $request->input('token'),
            $request->input('password'),
            $request->input('password_confirmation'),
            $request->input('email')
        );
    }

    public function toArray(): array
    {
        return [
            'token' => $this->token,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'email' => $this->email,
        ];
    }
}
