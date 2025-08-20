<?php

declare(strict_types=1);

namespace App\Core\Customer\Response;

use App\Core\Shared\Interfaces\IBaseResponse;

final class BaseResponse implements IBaseResponse
{
    public static function success(string $msg, array $data = []): array
    {
        return [
            "success" => true,
            "code" => 200,
            "message" => $msg,
            "data" => $data,
        ];
    }

    public static function created(string $msg, array $data = []): array
    {
        return [
            "success" => true,
            "code" => 201,
            "message" => $msg,
            "data" => $data,
        ];
    }

    public static function unauthorized(string $msg, array $data = []): array
    {
        return [
            "success" => false,
            "code" => 401,
            "message" => $msg,
            "data" => $data,
        ];
    }

    public static function validationError(string $msg, array $errors = []): array
    {
        return [
            "success" => false,
            "code" => 422,
            "message" => $msg,
            "data" => $errors,
        ];
    }

    public static function serverError(string $msg, array $data = []): array
    {
        return [
            "success" => false,
            "code" => 500,
            "message" => $msg,
            "data" => $data,
        ];
    }
}
