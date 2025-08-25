<?php

declare(strict_types=1);

namespace App\Adapter\Http\API\Customer\Controllers;

use App\Adapter\Http\API\Customer\Services\CustomerService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class FavoritesController extends Controller
{
    private CustomerService $customerService;

    public function __construct()
    {
        $this->customerService = new CustomerService();
    }

    /**
     * Ajouter un produit aux favoris
     */
    public function add(Request $request): JsonResponse
    {
        $res = $this->customerService->addToFavorites($request, true);
        return response()->json($res, $res['code'] ?? 200);
    }

    /**
     * Retirer un produit des favoris
     */
    public function remove(Request $request): JsonResponse
    {
        $res = $this->customerService->removeFromFavorites($request, true);
        return response()->json($res, $res['code'] ?? 200);
    }
}
