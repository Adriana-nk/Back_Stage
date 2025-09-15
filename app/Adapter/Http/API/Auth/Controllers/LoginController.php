<?php

declare(strict_types=1);

namespace App\Adapter\Http\API\Auth\Controllers;

use App\Adapter\Http\API\Auth\Services\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class LoginController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle the incoming login request.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $response = $this->authService->login($request, true);

        return response()->json(
            $response,
            $response['code'] ?? 500 // fallback si code absent
        );
    }
}
