<?php

declare(strict_types=1);

namespace App\Adapter\Http\API\Auth\Controllers;

use App\Adapter\Http\API\Auth\Services\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  Request  $request  the inconming request object
     */
    public function __invoke(Request $request): JsonResponse
    {
        $res = (new AuthService())->login($request, true);

        return response()->json($res, $res["code"]);
    }
}