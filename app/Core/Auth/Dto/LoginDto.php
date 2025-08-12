<?php

declare(strict_types=1);

namespace App\Core\Auth\Dto;

use App\Core\Shared\Interfaces\IDto;
use Illuminate\Http\Request;

final readonly class LoginDto implements IDto
{
    public string $email;
    public string $password;

    public function __construct(
        string $email,
        string $password
    ) {
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Create a DTO instance from the given HTTP request.
     *
     * @param  Request  $request
     * @return self
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            email: $request->input('email'),
            password: $request->input('password')
        );
    }

    /**
     * Convert the DTO instance to an associative array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
