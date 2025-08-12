<?php

declare(strict_types=1);

namespace App\Adapter\Http\API\shared;


final class BaseResponse{

    public function __construct(){}

    public static function result(array $data, int $status, string $msg){
        return [
            "code" => $status,
            "message" => $msg,
            "data" => $data
        ];
    }

    public static function created(string $msg, array $data): array{
        return BaseResponse::result($data, 201, $msg);
    }
    public static function success(string $msg, array $data): array{
        return BaseResponse::result($data, 200, $msg);
    }
    public static function error(string $msg, array $data): array{
        return BaseResponse::result($data, 500, $msg);
    }
    public static function unprocess_entity(string $msg, array $data): array{
        return BaseResponse::result($data, 422, $msg);
    }

    public static function bad_error(string $msg, array $data): array{
        return BaseResponse::result($data, 400, $msg);
    }
}
