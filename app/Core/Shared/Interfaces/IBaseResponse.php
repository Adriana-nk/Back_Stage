<?php

declare(strict_types=1);

namespace App\Core\Shared\Interfaces;

interface IBaseResponse
{

  
    public static function created(string $msg, array $data): array;
    public static function success(string $msg, array $data): array;

    public static function unauthorized(string $msg, array $data): array;
    public static function validationError(string $msg, array $errors): array;
    public static function serverError(string $msg, array $data): array;
    
}
