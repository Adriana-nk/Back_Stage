<?php

declare(strict_types=1);

namespace App\Adapter\Http\API\shared;

final class BaseResponse
{
    public function __construct() {}

    public static function result(array $data, int $status, string $msg): array
    {
        return [
            "code" => $status,
            "message" => $msg,
            "data" => $data
        ];
    }

    // ✅ 201: Ressource créée
    public static function created(string $msg, array $data): array
    {
        return BaseResponse::result($data, 201, $msg);
    }

    // ✅ 200: Succès générique
    public static function success(string $msg, array $data): array
    {
        return BaseResponse::result($data, 200, $msg);
    }

    // ✅ 500: Erreur serveur
    public static function error(string $msg, array $data): array
    {
        return BaseResponse::result($data, 500, $msg);
    }

    // ✅ 422: Erreur de validation
    public static function unprocess_entity(string $msg, array $data): array
    {
        return BaseResponse::result($data, 422, $msg);
    }

    // ✅ 400: Mauvaise requête
    public static function bad_error(string $msg, array $data): array
    {
        return BaseResponse::result($data, 400, $msg);
    }

    // 🔥 Ajouts utiles 🔥

    // ✅ 401: Utilisateur non authentifié
    public static function unauthorized(string $msg, array $data = []): array
    {
        return BaseResponse::result($data, 401, $msg);
    }

    // ✅ 403: Accès interdit
    public static function forbidden(string $msg, array $data = []): array
    {
        return BaseResponse::result($data, 403, $msg);
    }

    // ✅ 404: Ressource non trouvée
    public static function not_found(string $msg, array $data = []): array
    {
        return BaseResponse::result($data, 404, $msg);
    }

    // ✅ 409: Conflit
    public static function conflict(string $msg, array $data = []): array
    {
        return BaseResponse::result($data, 409, $msg);
    }
}
