<?php

declare(strict_types=1);

namespace App\Core\Auth\Interfaces;

use App\Core\Auth\Dto\LoginDto;
use App\Core\Auth\Dto\RegisterDto;

interface IAuthentification
{
    /**
     * Handle user registration using the provided RegisterDto.
     *
     * @param  RegisterDto  $registerDto  The data transfer object containing user registration details.
     * @return array The response containing the outcome of the registration operation.
     */
    /**
     * Handle user login using the provided LoginDto.
     *
     * @param  LoginDto  $loginDto  The data transfer object containing user login details.
     * @return array The response containing the outcome of the login operation.
     */
    public function login(LoginDto $loginDto): array;
    public function  register(RegisterDto $registerDto): array;
}
